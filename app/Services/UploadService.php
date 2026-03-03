<?php

namespace App\Services;

use App\Models\ExcelModel;
use App\Models\ProjectModel;
use App\Models\UploadModel;

class UploadService
{
    protected $uploadModel;
    protected $excelModel;
    protected $projectModel;

    public function __construct()
    {
        $this->uploadModel = new UploadModel();
        $this->excelModel = new ExcelModel();
        $this->projectModel = new ProjectModel();
    }

    public function handleUpload($file): array
    {
        $startTime = microtime(true);
        if (!$file || !$file->isValid()) {
            return ['success' => false, 'message' => 'File tidak valid atau tidak ditemukan.'];
        }

        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
            return ['success' => false, 'message' => 'Hanya file .xlsx/.xls/.csv yang diperbolehkan.'];
        }

        log_message('info', 'Starting file move...');
        $filePath = WRITEPATH . 'uploads/' . $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/', basename($filePath));
        log_message('info', 'File move completed in ' . round(microtime(true) - $startTime, 4) . 's');

        // Create upload record
        $uploadId = $this->uploadModel->createUpload([
            'filename' => $file->getName(),
            'original_filename' => $file->getClientName(),
            'file_path' => $filePath,
            'status' => 'uploaded'
        ]);

        // Validate columns
        log_message('info', 'Starting column validation...');
        $validationStartTime = microtime(true);
        $validation = $this->excelModel->validateColumns($filePath);
        log_message('info', 'Column validation completed in ' . round(microtime(true) - $validationStartTime, 4) . 's');

        if (!$validation['valid']) {
            $this->uploadModel->updateStatus($uploadId, 'failed', [
                'error_message' => 'Kolom tidak lengkap: ' . implode(', ', $validation['missing'])
            ]);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return [
                'success' => false,
                'message' => 'Data gagal diproses, kolom tidak lengkap: ' . implode(', ', $validation['missing'])
            ];
        }

        log_message('info', 'Total handleUpload time: ' . round(microtime(true) - $startTime, 4) . 's');
        return [
            'success' => true,
            'uploadId' => $uploadId,
            'message' => 'File berhasil diupload. Silakan lengkapi metadata sebelum memproses data.'
        ];
    }

    public function processMetadata(array $metadata): array
    {
        if (!$this->validateMetadataFields($metadata)) {
            return ['success' => false, 'message' => 'Semua field metadata harus diisi.'];
        }

        // Normalize quarter to Q1, Q2, etc.
        $metadata['quarter'] = strtoupper(trim($metadata['quarter']));
        if (!empty($metadata['quarter']) && !str_starts_with($metadata['quarter'], 'Q')) {
            $metadata['quarter'] = 'Q' . $metadata['quarter'];
        }

        $upload = $this->uploadModel->getUploadById($metadata['upload_id']);
        if (!$upload || $upload['status'] !== 'uploaded') {
            return ['success' => false, 'message' => 'Upload tidak valid.'];
        }

        // Validate for duplicates
        $validation = $this->uploadModel->validateMetadata(
            $metadata['upload_id'],
            $metadata['quarter'],
            $metadata['year']
        );

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $this->buildDuplicateErrorMessage($validation)
            ];
        }

        // Update metadata
        $this->uploadModel->update($metadata['upload_id'], [
            'upload_name' => $metadata['upload_name'],
            'quarter' => $metadata['quarter'],
            'year' => $metadata['year'],
            'usd_value' => $metadata['usd_value'],
            'status' => 'processing'
        ]);

        try {
            // Process data
            $totalRecords = $this->excelModel->processData($upload['file_path'], $metadata['upload_id']);

            // Update status to completed
            $this->uploadModel->updateStatus($metadata['upload_id'], 'completed', [
                'total_records' => $totalRecords,
                'processed_records' => $totalRecords
            ]);

            if (file_exists($upload['file_path'])) {
                unlink($upload['file_path']);
            }

            return [
                'success' => true,
                'message' => "Data berhasil diproses. Total {$totalRecords} record diproses."
            ];
        } catch (\Exception $e) {
            $this->uploadModel->updateStatus($metadata['upload_id'], 'failed', [
                'error_message' => $e->getMessage()
            ]);
            if (file_exists($upload['file_path'])) {
                unlink($upload['file_path']);
            }
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data: ' . $e->getMessage()
            ];
        }
    }

    public function updateMetadata(array $metadata): array
    {
        if (!$this->validateMetadataFields($metadata)) {
            return ['success' => false, 'message' => 'Semua field metadata harus diisi.'];
        }

        // Normalize quarter to Q1, Q2, etc.
        $metadata['quarter'] = strtoupper(trim($metadata['quarter']));
        if (!empty($metadata['quarter']) && !str_starts_with($metadata['quarter'], 'Q')) {
            $metadata['quarter'] = 'Q' . $metadata['quarter'];
        }

        $upload = $this->uploadModel->getUploadById($metadata['upload_id']);
        if (!$upload) {
            return ['success' => false, 'message' => 'Upload tidak ditemukan.'];
        }

        // Validate for duplicates (exclude current upload)
        $validation = $this->uploadModel->validateMetadata(
            $metadata['upload_id'],
            $metadata['quarter'],
            $metadata['year']
        );

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $this->buildDuplicateErrorMessage($validation)
            ];
        }

        try {
            $updateData = [
                'upload_name' => $metadata['upload_name'],
                'quarter' => $metadata['quarter'],
                'year' => $metadata['year'],
                'usd_value' => $metadata['usd_value']
            ];

            $result = $this->uploadModel->update($metadata['upload_id'], $updateData);

            if ($result === false) {
                log_message('error', 'Failed to update metadata for upload ID ' . $metadata['upload_id']);
                return ['success' => false, 'message' => 'Gagal memperbarui metadata. Silakan coba lagi.'];
            }

            // Verify the update
            $updatedUpload = $this->uploadModel->getUploadById($metadata['upload_id']);
            if (!$this->verifyMetadataUpdate($updatedUpload, $metadata)) {
                log_message('error', 'Metadata update verification failed for upload ID ' . $metadata['upload_id']);
                return [
                    'success' => false,
                    'message' => 'Metadata berhasil disimpan tetapi verifikasi gagal. Silakan refresh halaman.'
                ];
            }

            return ['success' => true, 'message' => 'Metadata berhasil diperbarui.'];
        } catch (\Exception $e) {
            log_message('error', 'Exception during metadata update: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui metadata: ' . $e->getMessage()
            ];
        }
    }

    public function deleteUpload(string $uploadId): array
    {
        if (!$uploadId) {
            return ['success' => false, 'message' => 'ID upload tidak valid.'];
        }

        $upload = $this->uploadModel->getUploadById($uploadId);
        if (!$upload) {
            return ['success' => false, 'message' => 'Upload tidak ditemukan.'];
        }

        try {
            // Delete associated project data
            $this->projectModel->deleteProjectsByUpload($uploadId);

            // Delete upload record
            $this->uploadModel->delete($uploadId);

            // Delete file if exists
            if (file_exists($upload['file_path'])) {
                unlink($upload['file_path']);
            }

            return ['success' => true, 'message' => 'Upload dan data terkait berhasil dihapus.'];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus upload: ' . $e->getMessage()
            ];
        }
    }

    public function getUploadById(string $uploadId): ?array
    {
        return $this->uploadModel->getUploadById($uploadId);
    }

    private function validateMetadataFields(array $metadata): bool
    {
        return !empty($metadata['upload_id']) &&
            !empty($metadata['upload_name']) &&
            !empty($metadata['quarter']) &&
            !empty($metadata['year']) &&
            !empty($metadata['usd_value']);
    }

    private function buildDuplicateErrorMessage(array $validation): string
    {
        $duplicate = $validation['duplicate'];
        $message = $validation['message'];

        if ($duplicate) {
            $message .= "<br><br><strong>Detail Upload yang Sudah Ada:</strong>";
            $message .= "<br>• Nama Upload: " . htmlspecialchars($duplicate['upload_name']);
            $message .= "<br>• Quarter: " . htmlspecialchars($duplicate['quarter']);
            $message .= "<br>• Tahun: " . htmlspecialchars($duplicate['year']);
            $message .= "<br>• Total Records: " . number_format($duplicate['total_records']);
            $message .= "<br>• Tanggal Upload: " . date('d/m/Y H:i', strtotime($duplicate['upload_date']));
        }

        return $message;
    }

    private function verifyMetadataUpdate(?array $updatedUpload, array $metadata): bool
    {
        if (!$updatedUpload)
            return false;

        // Normalize comparing values (ensure year and usd_value are compared numerically/loosely)
        $qMatch = $updatedUpload['quarter'] === $metadata['quarter'];
        $nMatch = $updatedUpload['upload_name'] === $metadata['upload_name'];
        $yMatch = (int) $updatedUpload['year'] === (int) $metadata['year'];
        $uMatch = abs((float) $updatedUpload['usd_value'] - (float) $metadata['usd_value']) < 0.01;

        return $qMatch && $nMatch && $yMatch && $uMatch;
    }
}
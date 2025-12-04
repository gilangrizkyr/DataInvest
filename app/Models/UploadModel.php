<?php

namespace App\Models;

use CodeIgniter\Model;

class UploadModel extends Model
{
    protected $table = 'uploads';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'uploaded_by',
        'upload_date',
        'status',
        'total_records',
        'processed_records',
        'error_message',
        'upload_name',
        'quarter',
        'year',
        'usd_value'
    ];

    protected $useTimestamps = false;

    /**
     * Check if upload with same quarter and year already exists
     * 
     * @param string $quarter Quarter value (1, 2, 3, 4 or Q1, Q2, Q3, Q4)
     * @param int $year Year value
     * @param int|null $excludeId Upload ID to exclude from check (for edit)
     * @return array|null Existing upload data if found, null otherwise
     */
    public function checkDuplicateUpload($quarter, $year, $excludeId = null)
    {
        // Normalize quarter format - PASTIKAN SELALU FORMAT Q1, Q2, Q3, Q4
        $quarter = strtoupper(trim($quarter));
        if (!str_starts_with($quarter, 'Q')) {
            $quarter = 'Q' . $quarter;
        }
        
        // Log untuk debugging
        log_message('debug', "=== DUPLICATE CHECK START ===");
        log_message('debug', "Checking duplicate: Quarter={$quarter}, Year={$year}, ExcludeId={$excludeId}");
        
        // Query dengan kondisi yang jelas
        $builder = $this->db->table($this->table);
        $builder->where('quarter', $quarter);
        $builder->where('year', $year);
        
        // Cek semua status KECUALI 'failed' dan 'uploaded' (yang masih menunggu metadata)
        $builder->where('status !=', 'failed');
        $builder->where('status !=', 'uploaded');
        
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        
        $result = $builder->get()->getRowArray();
        
        // Log hasil
        if ($result) {
            log_message('debug', "✅ DUPLICATE FOUND!");
            log_message('debug', "ID: {$result['id']}, Quarter: {$result['quarter']}, Year: {$result['year']}, Status: {$result['status']}, Name: {$result['upload_name']}");
        } else {
            log_message('debug', "❌ NO DUPLICATE - Data aman untuk diproses");
        }
        log_message('debug', "=== DUPLICATE CHECK END ===");
        
        return $result;
    }

    /**
     * Create new upload record with duplicate check
     * 
     * @param array $data Upload data
     * @return int|false Upload ID if successful, false if duplicate found
     */
    public function createUpload($data)
    {
        // Don't check for duplicates at creation since metadata not yet filled
        return $this->insert($data);
    }

    /**
     * Validate metadata before processing to prevent duplicates
     * 
     * @param int $uploadId Upload ID
     * @param string $quarter Quarter value
     * @param int $year Year value
     * @return array ['valid' => bool, 'message' => string, 'duplicate' => array|null]
     */
    public function validateMetadata($uploadId, $quarter, $year)
    {
        // Normalize quarter untuk perbandingan
        $normalizedQuarter = str_replace('Q', '', strtoupper(trim($quarter)));
        
        log_message('debug', "ValidateMetadata called - UploadID: {$uploadId}, Quarter: {$quarter}, Normalized: {$normalizedQuarter}, Year: {$year}");
        
        $duplicate = $this->checkDuplicateUpload($quarter, $year, $uploadId);
        
        if ($duplicate) {
            $message = "❌ Data untuk Quarter {$normalizedQuarter} (Q{$normalizedQuarter}) Tahun {$year} sudah ada!";
            $message .= " Upload sebelumnya: '{$duplicate['upload_name']}'";
            $message .= " (ID: {$duplicate['id']}, Status: {$duplicate['status']})";
            
            log_message('warning', $message);
            
            return [
                'valid' => false,
                'message' => $message,
                'duplicate' => $duplicate
            ];
        }
        
        log_message('debug', "Validation passed - no duplicate found");
        
        return [
            'valid' => true,
            'message' => 'Validasi berhasil - tidak ada duplikat',
            'duplicate' => null
        ];
    }

    /**
     * Update upload status
     */
    public function updateStatus($uploadId, $status, $additionalData = [])
    {
        $data = array_merge(['status' => $status], $additionalData);
        return $this->update($uploadId, $data);
    }

    /**
     * Get latest upload
     */
    public function getLatestUpload()
    {
        return $this->orderBy('upload_date', 'DESC')->first();
    }

    /**
     * Get upload by ID
     */
    public function getUploadById($uploadId)
    {
        return $this->find($uploadId);
    }

    /**
     * Get all uploads with statistics
     */
    public function getUploadsWithStats()
    {
        $builder = $this->db->table('uploads u');
        $builder->select('u.*, us.total_projects_pma, us.total_projects_pmdn, us.total_investment_pma, us.total_investment_pmdn');
        $builder->join('upload_statistics us', 'u.id = us.upload_id', 'left');
        $builder->orderBy('u.upload_date', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Get all uploads ordered by upload date
     */
    public function getAllUploads()
    {
        return $this->orderBy('upload_date', 'DESC')->findAll();
    }

    /**
     * Get uploads by quarter and year
     * 
     * @param string $quarter Quarter value
     * @param int $year Year value
     * @return array List of uploads
     */
    public function getUploadsByQuarterYear($quarter, $year)
    {
        return $this->where('quarter', $quarter)
                    ->where('year', $year)
                    ->orderBy('upload_date', 'DESC')
                    ->findAll();
    }
}
<?php

namespace App\Controllers;

use App\Services\DashboardService;
use App\Services\UploadService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
    protected $dashboardService;
    protected $uploadService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->dashboardService = new DashboardService();
        $this->uploadService = new UploadService();
    }

    public function index()
    {
        $filters = $this->getFilters();
        $data = $this->dashboardService->getDashboardData($filters);

        // ── NEW: Tahun + Triwulan filter aggregation ─────────────────────────────
        $statYear = $filters['stat_year'] ?? 'all';
        $statQuarters = $filters['stat_quarters'] ?? [];
        $currency = $filters['currency'] ?? 'IDR';
        $usdRate = (float) ($data['usd_rate'] ?? 16653);

        // Get distinct years for the year dropdown
        $uploadModel = new \App\Models\UploadModel();

        $statFilterData = null;
        $isStatFilterActive = ($statYear !== 'all' || !empty($statQuarters));
        if ($isStatFilterActive) {
            $uploadIds = $uploadModel->getUploadIdsByYearQuarters($statYear, $statQuarters);
            if (!empty($uploadIds)) {
                // KPI stats aggregation
                $statFilterData = $this->dashboardService->getAggregatedStatsByFilter(
                    $statYear,
                    $statQuarters,
                    $currency,
                    $usdRate
                );
                // Full charts + tables aggregation — merge into $data to override all sections
                $fullAggregated = $this->dashboardService->getFullAggregatedData($uploadIds, $filters);
                if (!empty($fullAggregated)) {
                    $data = array_merge($data, $fullAggregated);
                }
            }
        }

        // Get distinct years for the year dropdown
        // ($uploadModel already instantiated above)
        $availableYears = $uploadModel->getAvailableYears();
        // ─────────────────────────────────────────────────────────────────────────

        return view('dashboard_modern', [
            'data' => $data,
            'title' => 'Dashboard Statistik',
            'stat_filter_data' => $statFilterData,
            'available_years' => $availableYears,
            'is_stat_filter_active' => $isStatFilterActive,
        ]);
    }

    public function upload()
    {
        $file = $this->request->getFile('excel_file');
        $result = $this->uploadService->handleUpload($file);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        if ($result['success']) {
            return redirect()->to('/dashboard/metadata/' . $result['uploadId'])
                ->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function metadata($uploadId)
    {
        $upload = $this->uploadService->getUploadById($uploadId);

        if (!$upload || $upload['status'] !== 'uploaded') {
            return redirect()->to('/dashboard')->with('error', 'Upload tidak ditemukan atau tidak valid.');
        }

        return view('upload_metadata', ['upload' => $upload]);
    }

    public function processMetadata()
    {
        $metadata = [
            'upload_id' => $this->request->getPost('upload_id'),
            'upload_name' => $this->request->getPost('upload_name'),
            'quarter' => $this->request->getPost('quarter'),
            'year' => $this->request->getPost('year'),
            'usd_value' => $this->request->getPost('usd_value')
        ];

        $result = $this->uploadService->processMetadata($metadata);

        if ($result['success']) {
            return redirect()->to('/dashboard')->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function editMetadata($uploadId)
    {
        $upload = $this->uploadService->getUploadById($uploadId);

        if (!$upload) {
            return redirect()->to('/dashboard')->with('error', 'Upload tidak ditemukan.');
        }

        return view('upload_metadata', ['upload' => $upload, 'isEdit' => true]);
    }

    public function updateMetadata()
    {
        $metadata = [
            'upload_id' => $this->request->getPost('upload_id'),
            'upload_name' => $this->request->getPost('upload_name'),
            'quarter' => $this->request->getPost('quarter'),
            'year' => $this->request->getPost('year'),
            'usd_value' => $this->request->getPost('usd_value')
        ];

        $result = $this->uploadService->updateMetadata($metadata);

        if ($result['success']) {
            return redirect()->to('/dashboard')->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function deleteUpload()
    {
        $uploadId = $this->request->getPost('upload_id');
        $result = $this->uploadService->deleteUpload($uploadId);

        if ($result['success']) {
            return redirect()->to('/dashboard')->with('success', $result['message']);
        }

        return redirect()->to('/dashboard')->with('error', $result['message']);
    }

    public function download()
    {
        $result = $this->dashboardService->generateExcelDownload();

        if (!$result['success']) {
            return redirect()->to('/dashboard')->with('error', $result['message']);
        }

        // Output Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($result['spreadsheet']);
        $filename = 'hasil_analisis_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function setLanguage()
    {
        $language = $this->request->getPost('language');

        if (!in_array($language, ['id', 'en'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid language']);
        }

        // Set the locale in the session
        session()->set('locale', $language);
        service('request')->setLocale($language);

        // Force reload the language service with new locale
        $languageService = \Config\Services::language();
        $languageService->setLocale($language);
        $languageService->getLine('Dashboard.dashboard_title');

        return $this->response->setJSON(['success' => true, 'message' => 'Language set successfully']);
    }

    private function getFilters(): array
    {
        // Normalize stat_quarters from GET array
        $rawQuarters = $this->request->getGet('stat_quarters');
        $statQuarters = [];
        if (is_array($rawQuarters)) {
            $statQuarters = array_filter($rawQuarters, fn($q) => $q !== 'all' && $q !== '');
        } elseif (is_string($rawQuarters) && $rawQuarters !== 'all' && $rawQuarters !== '') {
            $statQuarters = [$rawQuarters];
        }

        return [
            'upload' => 'all',  // Selalu gunakan upload terbaru; filter via Tahun+Triwulan
            'quarter' => $this->request->getGet('quarter') ?? 'all',
            'year' => $this->request->getGet('year') ?? 'all',
            'quarterly_year' => $this->request->getGet('quarterly_year') ?? 'all',
            'currency' => $this->request->getGet('currency') ?? 'IDR',
            // NEW: Tahun + Triwulan filter
            'stat_year' => $this->request->getGet('stat_year') ?? 'all',
            'stat_quarters' => $statQuarters,
        ];
    }

    public function downloadSectorLKPM()
    {
        $type = $this->request->getGet('type'); // PMA or PMDN
        $format = $this->request->getGet('format'); // pdf or excel

        if (!in_array($type, ['PMA', 'PMDN']) || !in_array($format, ['pdf', 'excel'])) {
            return redirect()->back()->with('error', 'Parameter tidak valid.');
        }

        $filters = $this->getFilters();
        $data = $this->dashboardService->getDashboardData($filters);

        $sectorData = $data['sector_count_by_company'][$type]['data'] ?? [];
        $total = $data['sector_count_by_company'][$type]['total'] ?? [];

        if (empty($sectorData)) {
            return redirect()->back()->with('error', "Tidak ada data $type untuk diunduh.");
        }

        if ($format === 'excel') {
            return $this->downloadSectorExcel($type, $sectorData, $total);
        } else {
            return $this->downloadSectorPDF($type, $sectorData, $total);
        }
    }

    private function downloadSectorExcel($type, $sectorData, $total)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setTitle("LKPM $type");
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'DPMPTSP KABUPATEN TANAH BUMBU');
        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', "LAPORAN LKPM - $type");
        $sheet->mergeCells('A3:E3');
        $sheet->setCellValue('A3', 'Jumlah Sektor per Perusahaan');

        // Info
        $sheet->setCellValue('A5', 'Tanggal: ' . date('d F Y'));
        $sheet->setCellValue('A6', 'Total Tambahan Investasi: ' . number_format($total['total_tambahan_investasi']));
        $sheet->setCellValue('A7', 'Total TKI: ' . number_format($total['total_tki']));
        $sheet->setCellValue('B7', 'Total TKA: ' . number_format($total['total_tka']));
        $sheet->setCellValue('C7', 'Total Proyek: ' . $total['total_proyek']);

        // Table header
        $sheet->setCellValue('A9', 'No');
        $sheet->setCellValue('B9', 'Nama Perusahaan');
        $sheet->setCellValue('C9', 'Tambahan Investasi');
        $sheet->setCellValue('D9', 'TKI');
        $sheet->setCellValue('E9', 'TKA');
        $sheet->setCellValue('F9', 'Jumlah Proyek');

        $row = 10;
        $no = 1;
        foreach ($sectorData as $data) {
            $sheet->setCellValue("A$row", $no++);
            $sheet->setCellValue("B$row", $data['nama_perusahaan']);
            $sheet->setCellValue("C$row", $data['total_tambahan_investasi']);
            $sheet->setCellValue("D$row", $data['total_tki']);
            $sheet->setCellValue("E$row", $data['total_tka']);
            $sheet->setCellValue("F$row", $data['total_proyek']);
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "LKPM_{$type}_" . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    private function downloadSectorPDF($type, $sectorData, $total)
    {
        $pdf = new \TCPDF();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // Title
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'DPMPTSP KABUPATEN TANAH BUMBU', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, "LAPORAN LKPM - $type", 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Jumlah Sektor per Perusahaan', 0, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Tanggal: ' . date('d F Y'), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Total Tambahan Investasi: ' . number_format($total['total_tambahan_investasi']), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Total TKI: ' . number_format($total['total_tki']) . ', TKA: ' . number_format($total['total_tka']), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Total Proyek: ' . $total['total_proyek'], 0, 1, 'L');

        // Table
        $html = '<table border="1" cellpadding="5">
        <thead>
            <tr style="background-color:#cccccc;font-weight:bold;">
                <th width="5%">No</th>
                <th width="40%">Nama Perusahaan</th>
                <th width="15%">Tambahan Investasi</th>
                <th width="10%">TKI</th>
                <th width="10%">TKA</th>
                <th width="20%">Jumlah Proyek</th>
            </tr>
        </thead><tbody>';

        $no = 1;
        foreach ($sectorData as $data) {
            $html .= '<tr>
            <td align="center">' . $no++ . '</td>
            <td>' . htmlspecialchars($data['nama_perusahaan']) . '</td>
            <td align="right">' . number_format($data['total_tambahan_investasi']) . '</td>
            <td align="right">' . number_format($data['total_tki']) . '</td>
            <td align="right">' . number_format($data['total_tka']) . '</td>
            <td align="center">' . $data['total_proyek'] . '</td>
        </tr>';
        }

        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = "LKPM_{$type}_" . date('YmdHis') . '.pdf';
        $pdf->Output($filename, 'D');
        exit;
    }

    public function profile()
    {
        $userSession = session()->get('user');
        if (!$userSession) {
            return redirect()->to('auth/login');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userSession['id']);

        $data = [
            'title' => 'Profil Saya',
            'user' => $user
        ];

        return view('profile', $data);
    }

    public function updateProfile()
    {
        $userSession = session()->get('user');
        $userModel = new \App\Models\UserModel();

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userSession['id']}]",
            'username' => "required|min_length[3]|is_unique[users.username,id,{$userSession['id']}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
        ];

        // Menggunakan skipValidation(true) karena kita sudah melakukan validasi manual di atas
        // dan model memiliki aturan 'required' untuk password/role yang tidak ada di form ini
        if ($userModel->skipValidation(true)->update($userSession['id'], $data)) {
            // Update session data
            $updatedUser = $userModel->find($userSession['id']);
            session()->set('user', $updatedUser);
            return redirect()->to('profile')->with('success', 'Profil berhasil diperbarui');
        }

        $error = $userModel->errors() ? implode(', ', $userModel->errors()) : 'Gagal memperbarui profil di database';
        return redirect()->back()->with('error', $error);
    }

    public function changePassword()
    {
        $userSession = session()->get('user');
        $userModel = new \App\Models\UserModel();

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $user = $userModel->find($userSession['id']);
        
        // Verifikasi password lama
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Password saat ini salah');
        }

        $newData = [
            'password' => $userModel->hashPassword($this->request->getPost('new_password'))
        ];

        // Sama seperti updateProfile, kita skip model validation karena hanya update password
        if ($userModel->skipValidation(true)->update($userSession['id'], $newData)) {
            return redirect()->to('profile')->with('success', 'Password berhasil diubah');
        }

        return redirect()->back()->with('error', 'Gagal mengubah password');
    }

    public function logs()
    {
        $data = [
            'title' => 'Audit Logs',
            'logs' => [] // TODO: Ambil dari LogModel jika ada
        ];
        return view('logs', $data);
    }

    public function settings()
    {
        // Only superadmin can access settings
        $user = session()->get('user');
        if (!$user || ($user['role'] ?? '') !== 'superadmin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Halaman tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $data = [
            'title' => 'Settings',
            'config' => [] // TODO: Ambil konfigurasi aplikasi
        ];
        return view('settings', $data);
    }
}

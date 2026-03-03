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

        return view('dashboard', ['data' => $data]);
    }

    public function upload()
    {
        $file = $this->request->getFile('excel_file');
        $result = $this->uploadService->handleUpload($file);

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
        return [
            'upload' => $this->request->getGet('upload') ?? 'all',
            'quarter' => $this->request->getGet('quarter') ?? 'all',
            'year' => $this->request->getGet('year') ?? 'all',
            'quarterly_year' => $this->request->getGet('quarterly_year') ?? 'all',
            'currency' => $this->request->getGet('currency') ?? 'IDR'
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

    public function logs()
    {
        return view('errors/html/error_404', ['message' => 'Halaman Logs sedang dalam pengembangan.']);
    }

    public function settings()
    {
        return view('errors/html/error_404', ['message' => 'Halaman Settings sedang dalam pengembangan.']);
    }
}

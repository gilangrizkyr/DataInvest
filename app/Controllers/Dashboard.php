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
        $total = $data['sector_count_by_company'][$type]['total'] ?? 0;

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

        // Set title
        $sheet->setTitle("LKPM $type");

        // Header styling
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        // Title rows
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'DPMPTSP KABUPATEN TANAH BUMBU');
        $sheet->getStyle('A1')->applyFromArray($headerStyle);

        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', "LAPORAN LKPM - $type");
        $sheet->getStyle('A2')->applyFromArray($headerStyle);

        $sheet->mergeCells('A3:D3');
        $sheet->setCellValue('A3', 'Jumlah Sektor per Perusahaan');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Info rows
        $sheet->setCellValue('A5', 'Tanggal: ' . date('d F Y'));
        $sheet->setCellValue('A6', 'Total Jumlah: ' . number_format($total, 0, ',', '.'));

        // Table header
        $tableHeaderStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => $type === 'PMA' ? '2563EB' : 'F97316'
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->setCellValue('A8', 'No');
        $sheet->setCellValue('B8', 'Sektor');
        $sheet->setCellValue('C8', 'Nama Perusahaan');
        $sheet->setCellValue('D8', 'Jumlah');
        $sheet->getStyle('A8:D8')->applyFromArray($tableHeaderStyle);

        // Data rows
        $row = 9;
        $no = 1;
        foreach ($sectorData as $data) {
            $sheet->setCellValue("A$row", $no++);
            $sheet->setCellValue("B$row", $data['sektor']);
            $sheet->setCellValue("C$row", $data['nama_perusahaan']);
            $sheet->setCellValue("D$row", $data['jumlah']);

            // Add borders
            $sheet->getStyle("A$row:D$row")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);

            $row++;
        }

        // Total row
        $sheet->mergeCells("B$row:C$row");
        $sheet->setCellValue("B$row", 'TOTAL');
        $sheet->setCellValue("D$row", $total);
        $sheet->getStyle("A$row:D$row")->applyFromArray([
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => $type === 'PMA' ? 'DBEAFE' : 'FFEDD5'],
            ],
        ]);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(15);

        // Center align for No and Jumlah columns
        $sheet->getStyle("A9:A$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D9:D$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Output
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
        // Gunakan library seperti TCPDF atau mPDF
        // Contoh menggunakan TCPDF
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('DPMPTSP Tanah Bumbu');
        $pdf->SetAuthor('DPMPTSP Tanah Bumbu');
        $pdf->SetTitle("LKPM $type");
        $pdf->SetSubject('Laporan LKPM');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Title
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'DPMPTSP KABUPATEN TANAH BUMBU', 0, 1, 'C');

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, "LAPORAN LKPM - $type", 0, 1, 'C');

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Jumlah Sektor per Perusahaan', 0, 1, 'C');

        $pdf->Ln(5);

        // Info
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Tanggal: ' . date('d F Y'), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Total Jumlah: ' . number_format($total, 0, ',', '.'), 0, 1, 'L');

        $pdf->Ln(5);

        // Table header
        $html = '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;">
        <thead>
            <tr style="background-color:' . ($type === 'PMA' ? '#2563EB' : '#F97316') . '; color:#FFFFFF; font-weight:bold;">
                <th width="8%" align="center">No</th>
                <th width="42%" align="center">Sektor</th>
                <th width="35%" align="center">Nama Perusahaan</th>
                <th width="15%" align="center">Jumlah</th>
            </tr>
        </thead>
        <tbody>';

        // Data rows
        $no = 1;
        foreach ($sectorData as $data) {
            $html .= '<tr>
            <td align="center">' . $no++ . '</td>
            <td>' . htmlspecialchars($data['sektor']) . '</td>
            <td>' . htmlspecialchars($data['nama_perusahaan']) . '</td>
            <td align="right">' . number_format($data['jumlah'], 0, ',', '.') . '</td>
        </tr>';
        }

        // Total row
        $html .= '<tr style="font-weight:bold; background-color:' . ($type === 'PMA' ? '#DBEAFE' : '#FFEDD5') . ';">
        <td colspan="3" align="right">TOTAL</td>
        <td align="right">' . number_format($total, 0, ',', '.') . '</td>
    </tr>';

        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $filename = "LKPM_{$type}_" . date('YmdHis') . '.pdf';
        $pdf->Output($filename, 'D');
        exit;
    }
}

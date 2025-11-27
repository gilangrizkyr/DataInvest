<?php

namespace App\Controllers;

use App\Models\ExcelModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
    protected $excelModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->excelModel = new ExcelModel();
    }

    public function index()
    {
        $data = session()->get('excel_data') ?? [];
        return view('dashboard', ['data' => $data]);
    }

    public function upload()
    {
        $file = $this->request->getFile('excel_file');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau tidak ditemukan.');
        }

        if ($file->getExtension() !== 'xlsx') {
            return redirect()->back()->with('error', 'Hanya file .xlsx yang diperbolehkan.');
        }

        // Simpan file sementara
        $filePath = WRITEPATH . 'uploads/' . $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/', basename($filePath));

        // Validasi kolom
        $validationResult = $this->excelModel->validateColumns($filePath);
        if (!$validationResult['valid']) {
            unlink($filePath);
            return redirect()->back()->with('error', 'Kolom tidak sesuai template: ' . implode(', ', $validationResult['missing']));
        }

        // Proses data
        $data = $this->excelModel->processData($filePath);

        // Simpan data ke session atau database (untuk demo, gunakan session)
        session()->set('excel_data', $data);

        unlink($filePath); // Hapus file setelah diproses

        return redirect()->to('/dashboard')->with('success', 'Data berhasil diproses.');
    }

    public function download()
    {
        $data = session()->get('excel_data');
        if (!$data) {
            return redirect()->to('/dashboard')->with('error', 'Tidak ada data untuk diunduh.');
        }

        // Buat Excel dengan PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Sheet Raw Data
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Raw Data');
        $headers = array_keys($data['raw'][0] ?? []);
        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray($data['raw'], null, 'A2');

        // Sheet Analisis Sektor
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(1);
        $sheet->setTitle('Analisis Sektor');
        $sheet->fromArray(['Sektor', 'Jumlah Proyek', 'Persentase'], null, 'A1');
        $sheet->fromArray($data['sector_analysis'], null, 'A2');

        // Sheet Tenaga Kerja
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(2);
        $sheet->setTitle('Tenaga Kerja');
        $sheet->fromArray(['Tipe', 'TKI', 'TKA'], null, 'A1');
        $sheet->fromArray($data['workforce'], null, 'A2');

        // Sheet Investasi
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(3);
        $sheet->setTitle('Investasi');
        $sheet->fromArray(['Tipe', 'Total Investasi'], null, 'A1');
        $sheet->fromArray($data['total_investment'], null, 'A2');

        // Sheet Proyek per Negara
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(4);
        $sheet->setTitle('Proyek per Negara');
        $sheet->fromArray(['Negara', 'Jumlah Proyek'], null, 'A1');
        $sheet->fromArray($data['projects_by_country'], null, 'A2');

        // Simpan dan download
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'hasil_analisis_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}

<?php

namespace App\Services;

use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportExcelService
{
    protected $spreadsheet;
    protected $response;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->response = service('response');
    }

    public function exportLargeData(string $filename, array $headers, callable $dataCallback, int $chunkSize = 500): ResponseInterface
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->fromArray($headers, null, 'A1');

        $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->setHeader('Cache-Control', 'max-age=0');

        $offset = 0;
        $rowNumber = 2;

        while (true) {
            $data = $dataCallback($offset, $chunkSize);

            if (empty($data)) {
                break;
            }

            $output = [];

            foreach ($data as $row) {
                $output[] = $row;
            }

            $sheet->fromArray($output, null, 'A' . $rowNumber);
            $rowNumber += count($data);

            $offset += $chunkSize;
        }

        $writer = new Xlsx($this->spreadsheet);
        ob_start();

        $writer->save('php://output');
        $output = ob_get_clean();

        return $this->response->setBody($output);
    }

    public function quickExport(string $filename, array $headers, array $data): ResponseInterface
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray($data, null, 'A2');

        $writer = new Xlsx($this->spreadsheet);
        ob_start();
        $writer->save('php://output');
        $output = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"')
            ->setBody($output);
    }

    function exportDecryptedData(string $filename, array $headers, callable $dataCallback, int $chunkSize = 500, array $decyptedColumns = []): ResponseInterface
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->fromArray($headers, null, 'A1');

        $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $this->response->setHeader('Cache-Control', 'max-age=0');

        $offset = 0;
        $rowNumber = 2;

        while (true) {
            $data = $dataCallback($offset, $chunkSize);

            if (empty($data)) {
                break;
            }

            $output = [];
            foreach ($data as $row) {
                // Jika $row adalah array asosiatif (misalnya ['nama' => 'John', 'password' => 'encrypted'])
                if (!empty($decyptedColumns)) {
                    foreach ($decyptedColumns as $column) {
                        if (isset($row[$column])) {
                            $row[$column] = dekripsi($row[$column]);  // Panggil fungsi dekripsi
                        }
                    }
                }
                $output[] = $row;
            }

            $sheet->fromArray($output, null, 'A' . $rowNumber);
            $rowNumber += count($data);

            $offset += $chunkSize;
        }

        $writer = new Xlsx($this->spreadsheet);
        ob_start();
        $writer->save('php://output');
        $output = ob_get_clean();

        return $this->response->setBody($output);
    }

    function quickExportDecrypted(string $filename, array $headers, array $data, array $decyptedColumns): ResponseInterface
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->fromArray($headers, null, 'A1');
        // Dekripsi kolom tertentu sebelum export
        $processedData = [];
        foreach ($data as $row) {
            if (!empty($decryptedColumns)) {
                foreach ($decryptedColumns as $column) {
                    if (isset($row[$column])) {
                        $row[$column] = dekripsi($row[$column]);  // Panggil fungsi dekripsi
                    }
                }
            }
            $processedData[] = $row;
        }
        $sheet->fromArray($processedData, null, 'A2');
        $writer = new Xlsx($this->spreadsheet);
        ob_start();
        $writer->save('php://output');
        $output = ob_get_clean();
        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename="' . $filename . '"')
            ->setBody($output);
    }
}

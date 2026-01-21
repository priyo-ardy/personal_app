<?php

namespace App\Services\UploadImage;

use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class UploadImageService
{
    /**
     * Service untuk melakukan upload gambar (HANYA GAMBAR)
     *
     * @param string $path Path folder tujuan (relative terhadap public folder), misal: 'uploads/users'
     * @param array $data Array yang harus berisi object file dengan key 'file'
     * @return array|object Mengembalikan informasi file (array tunggal jika single, array of array jika multiple)
     * @throws Exception Jika validasi gagal atau upload error
     */
    public function upload_image(string $path, array $data)
    {
        $inputFiles = $data['file'] ?? null;

        if (!$inputFiles) {
            log_message('error', '[UploadImageService::upload_image] File is required');
            throw new \Exception("File is required", ResponseInterface::HTTP_BAD_REQUEST);
        }

        $isSingleFile = false;
        $fileToProcess = [];

        // Normalisasi Input
        if (is_array($inputFiles)) {
            // Cek apakah array kosong
            if (empty($inputFiles)) {
                log_message('error', '[UploadImageService::upload_image] File is required');
                throw new \Exception("File is required", ResponseInterface::HTTP_BAD_REQUEST);
            }
            $fileToProcess = $inputFiles;
        } elseif ($inputFiles instanceof UploadedFile) {
            $fileToProcess = [$inputFiles];
            $isSingleFile = true;
        } else {
            log_message('error', '[UploadImageService::upload_image] Invalid file format');
            throw new \Exception("Invalid file format", ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Validasi Ukuran Total (50MB)
        $this->validateTotalSize($fileToProcess, 50);

        $results = [];

        // PERBAIKAN PATH: Hapus 'public/' karena FCPATH sudah di public
        $targetDir = FCPATH . $path;

        // PERBAIKAN PERMISSION: Gunakan 0755 (Standar aman), bukan 0777
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        foreach ($fileToProcess as $index => $file) {
            if (!$file->isValid()) {
                continue;
            }

            $allowedMimeType = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'];

            if (!in_array($file->getMimeType(), $allowedMimeType)) {
                log_message('error', '[UploadImageService::upload_image] File ke-' . ($index + 1) . ' invalid format. Allowed: PNG, JPG, GIF, WEBP');
                throw new \Exception("File ke-" . ($index + 1) . " invalid format. Allowed: PNG, JPG, GIF, WEBP", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $newName = $file->getRandomName();

            try {
                $file->move($targetDir, $newName);

                $results[] = [
                    'file_name' => $newName,
                    'file_path' => $path . '/' . $newName,
                    'full_path' => $targetDir . '/' . $newName,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ];
            } catch (Exception $e) {
                log_message('error', '[UploadImageService::upload_image] Error uploading file: ' . $e->getMessage());
                throw new Exception("Error uploading file: " . $e->getMessage(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        // PERBAIKAN FATAL: Return ditaruh SETELAH loop selesai
        if (empty($results)) {
            log_message('error', '[UploadImageService::upload_image] No files were successfully uploaded');
            throw new \Exception("No files were successfully uploaded", ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $isSingleFile ? ($results[0] ?? []) : $results;
    }

    /**
     * Validasi total ukuran file
     * @param array $files
     * @param int $sizeInMB
     */
    private function validateTotalSize(array $files, int $sizeInMB): void
    {
        $totalSize = 0;
        // Konversi MB ke Bytes
        $maxBytes = $sizeInMB * 1024 * 1024;

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $totalSize += $file->getSize();
            }
        }

        // PERBAIKAN MATEMATIKA: Jangan dikali 1024*1024 lagi di sini
        if ($totalSize > $maxBytes) {
            throw new Exception("Total file size must be less than " . $sizeInMB . " MB", ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function upload_single_image(string $path, $file)
    {
        try {
            if (!$file instanceof UploadedFile) {
                log_message('error', '[UploadImageService::upload_single_image] File is required');
                throw new \Exception("File is required", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!$file->isValid()) {
                log_message('error', '[UploadImageService::upload_single_image] File is invalid');
                throw new \Exception("File is invalid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if ($file->hasMoved()) {
                log_message('error', '[UploadImageService::upload_single_image] File has moved');
                throw new \Exception("File has moved", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();
            $newName = $file->getRandomName();
            $filePath = FCPATH . '/uploads/' . $path;
            if (!is_dir($filePath)) {
                mkdir($filePath, 0755, true);
            }

            if ($file->move($filePath, $newName)) {
                return [
                    'file_name' => $newName,
                    'file_path' => '/uploads/' . $path . '/' . $newName,
                    'full_path' => $filePath . '/' . $newName,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                ];
            }

            log_message('error', '[UploadImageService::upload_single_image] Error uploading file ' . $file->getErrorString());
            throw new \Exception("Error uploading file", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            log_message('error', '[UploadImageService::upload_single_image] Error uploading file: ' . $e->getMessage());
            // throw new \Exception("Error uploading file: " . $e->getMessage(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            throw $e;
        }
    }
}

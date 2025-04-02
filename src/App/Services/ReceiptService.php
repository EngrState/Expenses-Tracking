<?php

namespace App\Services;

use App\Config\Paths;
use Framework\Database;
use Framework\Exceptions\ValidationException;

class ReceiptService
{
    public function __construct(private Database $db) {}
    public function validateFile(?array $file)
    {
        if (!$file || $file["error"] !== UPLOAD_ERR_OK) {
            throw new ValidationException(['receipt' => ['failed to upload file']]);
        }


        $maxFileSize = 3 * 1024 * 1024;

        if ($file['size'] > $maxFileSize) {

            throw new ValidationException(['receipt' => ['file  upload too large']]);
        }

        $originalFileName = $file['name'];
        if (!preg_match('/^[A-za-z0-9\s._-]+$/', $originalFileName)) {
            throw new ValidationException(['receipt' => ['invalid file name']]);
        }

        $clientMimeType = $file['type'];
        $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!in_array($clientMimeType, $allowedMimeTypes)) {
            throw new ValidationException(['receipt' => ['invalid file type']]);
        }
    }

    public function upload(array $file, int $transaction)
    {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = bin2hex(random_bytes(16)) . "." . $fileExtension;

        $uploadPath = Paths::STORAGE_UPLOADS . "/" . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new ValidationException([
                'receipt' => ['failed to upload file']
            ]);
        }

            $this->db->query(
                "INSERT INTO receipts(
                transaction_id, original_filename, storage_filename, media_type
                )
          VALUES(
                :transaction_id, :original_filename, :storage_filename, :media_type
          )",
                [
                    'transaction_id' => $transaction,
                    'original_filename' =>  $file['name'],
                    'storage_filename' => $newFileName,
                    'media_type' => $file['type']
                ]
            );
    }

    
    public function getReceipt(string $id)
    {
        $receipt = $this->db->query(
            "SELECT *
        FROM receipts 
        WHERE id = :id",
            [
                'id' => $id
            ]
        )->find();
        return $receipt;
    }
    public function read(array $receipt)
    {
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];

        if(!file_exists($filePath)){
            redirectTo('/');
        }
        header("COntent-Disposition: inline;filename={$receipt['original_filename']}");
        header("COntent-Type: {$receipt['media_type']}");

        readfile($filePath);
    }

    public function delete (array $receipt){

        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];

        unlink($filePath);


        $this->db->query(
            "DELETE FROM  receipts
            WHERE id = :id",
            [
                'id' => $receipt['id'],
            ]);


    }
   
}

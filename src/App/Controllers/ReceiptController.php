<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{TranscationService, ReceiptService};

class ReceiptController
{
    public function __construct(
        private TemplateEngine $view,
        private TranscationService $transactionService,
        private ReceiptService $receiptService
    ) {}

    public function uploadView(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (!$transaction) {
            redirectTo("/");
        }

        echo $this->view->render("receipts/create.php");
    }

    public function upload(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (!$transaction) {
            redirectTo("/");
        }
        $receiptFile = $_FILES['receipt'] ?? NULL;

        $this->receiptService->validateFile($receiptFile);
        $this->receiptService->upload($receiptFile, $transaction['id']);

        redirectTo("/");
    }
    public function download(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (empty($transaction)) {
            redirectTo("/");
        }

        $receipt = $this->receiptService->getReceipt($params['receipt']);
        if (empty($receipt)) {
            redirectTo("/");
        }

        if ($receipt['transaction_id'] !== $transaction['id']) {
            redirectTo("/");
        }

        $this->receiptService->read($receipt);
    }
    public function delete(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (empty($transaction)) {
            redirectTo("/");
        }

        $receipt = $this->receiptService->getReceipt($params['receipt']);
        if (empty($receipt)) {
            redirectTo("/");
        }

        if ($receipt['transaction_id'] !== $transaction['id']) {
            redirectTo("/");
        }
        $this->receiptService->delete($receipt);
        redirectTo("/");
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorServices, TranscationService};

class TransactionController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorServices $validatorServices,
        private TranscationService $transcationService
    ) {}

    public function createView()
    {
        echo $this->view->render("transaction/create.php");
    }
    public function create()
    {

        $this->validatorServices->validateTransaction($_POST);
        $this->transcationService->create($_POST);
        redirectTo('/');
    }

    public function editView(array $params)
    {


        $transaction = $this->transcationService->getUserTransaction(
            $params['transaction']
        );
        if (!$transaction) {
            redirectTo('/');
        }
        echo $this->view->render(
            "transaction/edit.php",
            [
                'transaction' => $transaction
            ]
        );
    }
    public function edit(array $params)
    {
        $transaction = $this->transcationService->getUserTransaction(
            $params['transaction']
        );
        if (!$transaction) {
            redirectTo('/');
        }

        $this->validatorServices->validateTransaction($_POST);
        $this->transcationService->update($_POST, $transaction['id']);
        redirectTo('/');
    }
    public function delete(array $params)
    {
        
        // $transaction = $this->transcationService->getUserTransaction(
        //     $params['transaction']
        // );
        // if (!$transaction) {
        //     redirectTo('/');
        // }

        $this->transcationService->delete((int)  $params['transaction']);
        redirectTo('/');
    }
}

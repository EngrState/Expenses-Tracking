<?php
declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{ValidatorServices, UserService, 
    TranscationService, ReceiptService};

return[
    TemplateEngine::class => fn()=> new TemplateEngine(Paths::VIEW),
    ValidatorServices::class=>fn()=> new ValidatorServices(), 
    Database::class => fn() =>new Database($_ENV['DB_DRIVER'],
    [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_NAME'],
    ],
    $_ENV['DB_USER'], $_ENV['DB_PASS']),
    
    UserService::class => function(Container $container){
        $db = $container->get(Database::class);

        return new UserService($db);
    }, TranscationService::class => function (Container $container){
        $db = $container->get(Database::class);

        return new TranscationService($db);

    }, ReceiptService::class => function(Container $container){
        $db = $container->get(Database::class);

        return new ReceiptService($db);
    }
];


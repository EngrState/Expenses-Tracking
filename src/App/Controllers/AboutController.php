<?php

declare(strict_types=1);

namespace App\Controllers;
use Framework\TemplateEngine;
use App\Config\Paths;


class AboutController
{
    

    public function __construct(private TemplateEngine $view)
    {

    }

    public function about()
    {
        // dd($this->view);
        // echo 'home Page';
        // $secret = "lover boy";
       echo  $this->view->render("/about.php", [
            'title' =>  'AboutPage',
            'dangerousData'=> '<script> alert(123)</script>'
        ]);
    }
}

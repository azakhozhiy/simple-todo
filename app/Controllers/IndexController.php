<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function index(): Response
    {
        return response(view('main'));
    }
}

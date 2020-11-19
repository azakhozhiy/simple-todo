<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function index(): Response
    {
        return new Response(view('main'));
    }
}

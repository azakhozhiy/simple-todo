<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class NotFoundController
{
    public function show()
    {
        return new Response(view('404'));
    }
}

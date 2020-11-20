<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class NotFoundController
{
    public function show(): Response
    {
        return response(view('404', [], false));
    }
}

<?php

namespace App\Packages\Core\Engine;

use Symfony\Component\HttpFoundation\Request;

class Kernel
{
    protected Request $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
    }
}

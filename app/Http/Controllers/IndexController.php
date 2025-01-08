<?php

namespace App\Http\Controllers;

use Inertia\Response;

class IndexController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Index');
    }
}

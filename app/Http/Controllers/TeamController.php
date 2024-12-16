<?php

namespace App\Http\Controllers;

use Inertia\Response;

class TeamController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Teams');
    }
}

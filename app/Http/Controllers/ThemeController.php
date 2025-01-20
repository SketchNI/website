<?php

namespace App\Http\Controllers;

use App\Http\Requests\Theme\UpdateRequest;

class ThemeController extends Controller
{
    public function __invoke(UpdateRequest $request): void
    {
        session('theme', $request->get('theme'));
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class IndexController extends Controller
{
    public function __invoke(): Response
    {
        if (!auth()->user()->hasAnyRole(['mod', 'admin', 'super-admin'])) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        return inertia('Backend/Index');
    }
}

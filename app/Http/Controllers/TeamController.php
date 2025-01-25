<?php

namespace App\Http\Controllers;

use App\Http\Resources\Backend\TeamResource;
use App\Models\Team;
use Inertia\Response;

class TeamController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Teams', [
            'teams' => TeamResource::collection(Team::all()),
        ]);
    }
}

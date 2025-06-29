<?php

namespace App\Http\Controllers;

use Spatie\NowPlaying\NowPlaying;

class LastFmController extends Controller
{
    public function __invoke()
    {
        $now_playing = new NowPlaying(config('services.lastfm.api_key'));

        return $now_playing->getTrackInfo(config('services.lastfm.username'));
    }
}

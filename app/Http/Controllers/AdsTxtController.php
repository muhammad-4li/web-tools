<?php

namespace App\Http\Controllers;

use App\Services\AdsSettings;
use Illuminate\Http\Response;

class AdsTxtController extends Controller
{
    public function __invoke(): Response
    {
        $publisherId = trim(AdsSettings::get()['publisher_id'] ?? '');
        $enabled = AdsSettings::get()['enabled'] ?? false;

        if (!$enabled) {
            return abort(404);
        }

        if ($publisherId && str_starts_with($publisherId, 'ca-pub-')) {
            $content = "google.com, {$publisherId}, DIRECT, f08c47fec0942fa0\n";
        } else {
            $content = "# ads.txt — configure your AdSense Publisher ID in the admin panel\n";
        }

        return response($content, 200, [
            'Content-Type'  => 'text/plain',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }
}

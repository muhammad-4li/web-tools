<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ads Enable / Disable
    |--------------------------------------------------------------------------
    | Master switch. When false, no ad slots are rendered and the popup is
    | bypassed — the download callback fires immediately.
    |
    */
    'enabled' => env('ADS_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Google AdSense Publisher ID
    |--------------------------------------------------------------------------
    */
    'publisher_id' => env('ADSENSE_PUBLISHER_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Ad Slot IDs
    |--------------------------------------------------------------------------
    */
    'slots' => [
        'topbar'    => env('ADSENSE_SLOT_TOPBAR',    ''),
        'secondbar' => env('ADSENSE_SLOT_SECONDBAR', ''),
        'left'      => env('ADSENSE_SLOT_LEFT',      ''),
        'right'     => env('ADSENSE_SLOT_RIGHT',     ''),
        'bottom'    => env('ADSENSE_SLOT_BOTTOM',    ''),
        'popup'     => env('ADSENSE_SLOT_POPUP',     ''),
    ],

];

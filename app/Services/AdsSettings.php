<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class AdsSettings
{
    private static function path(): string
    {
        return storage_path('app/adsense.json');
    }

    /**
     * Return the current ads settings, merging the JSON file over config defaults.
     */
    public static function get(): array
    {
        $defaults = [
            'enabled'        => (bool) config('adsense.enabled', false),
            'publisher_id'   => (string) config('adsense.publisher_id', ''),
            'slot_topbar'    => (string) config('adsense.slots.topbar', ''),
            'slot_secondbar' => (string) config('adsense.slots.secondbar', ''),
            'slot_left'      => (string) config('adsense.slots.left', ''),
            'slot_right'     => (string) config('adsense.slots.right', ''),
            'slot_bottom'    => (string) config('adsense.slots.bottom', ''),
            'slot_popup'     => (string) config('adsense.slots.popup', ''),
        ];

        $file = static::path();

        if (! File::exists($file)) {
            return $defaults;
        }

        $stored = json_decode(File::get($file), true);

        return array_merge($defaults, is_array($stored) ? $stored : []);
    }

    /**
     * Persist ads settings to storage/app/adsense.json.
     */
    public static function save(array $data): void
    {
        $allowed = [
            'enabled', 'publisher_id',
            'slot_topbar', 'slot_secondbar', 'slot_left',
            'slot_right', 'slot_bottom', 'slot_popup',
        ];

        $clean = [];
        foreach ($allowed as $key) {
            if (array_key_exists($key, $data)) {
                $clean[$key] = $key === 'enabled'
                    ? (bool) $data[$key]
                    : (string) ($data[$key] ?? '');
            }
        }

        File::put(static::path(), json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

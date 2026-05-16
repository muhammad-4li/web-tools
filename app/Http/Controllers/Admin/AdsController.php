<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdsSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdsController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Ads/Form', [
            'settings' => AdsSettings::get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'enabled'        => 'boolean',
            'publisher_id'   => 'nullable|string|max:100',
            'slot_topbar'    => 'nullable|string|max:50',
            'slot_secondbar' => 'nullable|string|max:50',
            'slot_left'      => 'nullable|string|max:50',
            'slot_right'     => 'nullable|string|max:50',
            'slot_bottom'    => 'nullable|string|max:50',
            'slot_popup'     => 'nullable|string|max:50',
        ]);

        // Normalise nulls to empty strings for non-boolean fields
        foreach ($data as $key => $value) {
            if ($key !== 'enabled' && $value === null) {
                $data[$key] = '';
            }
        }

        AdsSettings::save($data);

        return redirect()->route('admin.ads.edit')
            ->with('success', 'Ads settings saved.');
    }
}

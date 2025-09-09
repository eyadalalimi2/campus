<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $universities = University::orderBy('name')->get();
        return view('admin.themes.index', compact('universities'));
    }

    public function edit(University $university)
    {
        return view('admin.themes.edit', compact('university'));
    }

    public function update(Request $request, University $university)
    {
        $data = $request->validate([
            'primary_color'   => ['nullable','string','max:20'],
            'secondary_color' => ['nullable','string','max:20'],
            'theme_mode'      => ['required','in:auto,light,dark'],
            'logo'            => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('universities', 'public');
            $data['logo_path'] = $path;
        }

        $university->update($data);

        // إن أردت تغيير الجامعة الحالية لتجربة الثيم فورًا:
        session(['current_university_id' => $university->id]);

        return redirect()
            ->route('admin.themes.edit', $university)
            ->with('success', 'تم تحديث إعدادات الثيم بنجاح.');
    }
}

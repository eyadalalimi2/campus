<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    public function index()
    {
        $universities = University::orderBy('name')->paginate(10);
        return view('admin.themes.index', compact('universities'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:200',
            'slug' => 'required|unique:universities,slug',
            'logo' => 'nullable|image|max:2048',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
        ]);

        $data = $r->only(['name', 'slug', 'primary_color', 'secondary_color']);
        if ($r->hasFile('logo')) {
            $data['logo_path'] = $r->file('logo')->store('logos', 'public');
        }
        University::create($data);

        return redirect()->route('admin.themes.index')->with('success', 'تمت إضافة الثيم بنجاح.');
    }

    public function edit(University $university)
    {
        return view('admin.themes.edit', compact('university'));
    }

    public function update(Request $r, University $university)
    {
        $r->validate([
            'name' => 'required|string|max:200',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $r->only(['name', 'primary_color', 'secondary_color']);
        if ($r->hasFile('logo')) {
            if ($university->logo_path) Storage::disk('public')->delete($university->logo_path);
            $data['logo_path'] = $r->file('logo')->store('logos', 'public');
        }

        $university->update($data);
        return redirect()->route('admin.themes.index')->with('success', 'تم تحديث الثيم.');
    }
}

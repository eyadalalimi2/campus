<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PracticePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PracticePdfController extends Controller
{
    public function index()
    {
        $pdfs = PracticePdf::orderBy('order', 'asc')->paginate(25);
        return view('admin.practice_pdfs.index', compact('pdfs'));
    }

    public function create()
    {
        return view('admin.practice_pdfs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf|max:10240',
            'order' => 'nullable|integer',
        ]);

        $path = $request->file('file')->store('practice_pdfs', 'public');
        $data['file'] = $path;
        $data['order'] = $data['order'] ?? 0;

        PracticePdf::create($data);

        return redirect()->route('admin.practice_pdfs.index')->with('success', 'تم إضافة ملف PDF بنجاح');
    }

    public function edit(PracticePdf $practice_pdf)
    {
        return view('admin.practice_pdfs.edit', ['pdf' => $practice_pdf]);
    }

    public function update(Request $request, PracticePdf $practice_pdf)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:10240',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('file')) {
            if ($practice_pdf->file && Storage::disk('public')->exists($practice_pdf->file)) {
                Storage::disk('public')->delete($practice_pdf->file);
            }
            $data['file'] = $request->file('file')->store('practice_pdfs', 'public');
        }

        $data['order'] = $data['order'] ?? 0;
        $practice_pdf->update($data);

        return redirect()->route('admin.practice_pdfs.index')->with('success', 'تم تحديث الملف');
    }

    public function destroy(PracticePdf $practice_pdf)
    {
        if ($practice_pdf->file && Storage::disk('public')->exists($practice_pdf->file)) {
            Storage::disk('public')->delete($practice_pdf->file);
        }

        $practice_pdf->delete();

        return redirect()->route('admin.practice_pdfs.index')->with('success', 'تم حذف الملف');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResearchPdfController extends Controller
{
    public function index()
    {
        $items = ResearchPdf::orderBy('order', 'asc')->paginate(25);
        return view('admin.research_pdfs.index', compact('items'));
    }

    public function create()
    {
        return view('admin.research_pdfs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'file' => 'required|file|mimes:pdf|max:20480',
            'order' => 'nullable|integer',
        ]);

        $data['file'] = $request->file('file')->store('research_pdfs', 'public');
        $data['order'] = $data['order'] ?? 0;

        ResearchPdf::create($data);

        return redirect()->route('admin.research_pdfs.index')->with('success', 'تم إضافة الملف بنجاح');
    }

    public function edit(ResearchPdf $research_pdf)
    {
        return view('admin.research_pdfs.edit', ['pdf' => $research_pdf]);
    }

    public function update(Request $request, ResearchPdf $research_pdf)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:20480',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('file')) {
            if ($research_pdf->file && Storage::disk('public')->exists($research_pdf->file)) {
                Storage::disk('public')->delete($research_pdf->file);
            }
            $data['file'] = $request->file('file')->store('research_pdfs', 'public');
        }

        $data['order'] = $data['order'] ?? 0;
        $research_pdf->update($data);

        return redirect()->route('admin.research_pdfs.index')->with('success', 'تم تحديث الملف');
    }

    public function destroy(ResearchPdf $research_pdf)
    {
        if ($research_pdf->file && Storage::disk('public')->exists($research_pdf->file)) {
            Storage::disk('public')->delete($research_pdf->file);
        }
        $research_pdf->delete();

        return redirect()->route('admin.research_pdfs.index')->with('success', 'تم حذف الملف');
    }
}

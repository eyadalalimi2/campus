<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentAssistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContentAssistantController extends Controller
{
    public function index(Request $request)
    {
        $q = ContentAssistant::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $term = trim($request->q);
                $qq->where(function($w) use ($term){
                    $w->where('name','like',"%{$term}%")
                      ->orWhere('university_text','like',"%{$term}%")
                      ->orWhere('college_text','like',"%{$term}%")
                      ->orWhere('major_text','like',"%{$term}%");
                });
            })
            ->when($request->filled('is_active'), fn($qq) => $qq->where('is_active', (int) $request->is_active))
            ->orderBy('sort_order')->orderBy('id');

        $assistants = $q->paginate(20)->withQueryString();

        return view('admin.content_assistants.index', compact('assistants'));
    }

    public function create()
    {
        return view('admin.content_assistants.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('content_assistants', 'public');
        }

        ContentAssistant::create($data);

        return redirect()->route('admin.content_assistants.index')->with('success','تم إضافة المساعد بنجاح');
    }

    public function edit(ContentAssistant $content_assistant)
    {
        return view('admin.content_assistants.edit', ['assistant' => $content_assistant]);
    }

    public function update(Request $request, ContentAssistant $content_assistant)
    {
        $data = $this->validateData($request, updating: true);

        if ($request->hasFile('photo')) {
            // حذف القديم إن وجد
            if ($content_assistant->photo_path) {
                Storage::disk('public')->delete($content_assistant->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('content_assistants', 'public');
        }

        $content_assistant->update($data);

        return redirect()->route('admin.content_assistants.index')->with('success','تم تحديث بيانات المساعد');
    }

    public function destroy(ContentAssistant $content_assistant)
    {
        if ($content_assistant->photo_path) {
            Storage::disk('public')->delete($content_assistant->photo_path);
        }
        $content_assistant->delete();

        return back()->with('success','تم الحذف');
    }

    private function validateData(Request $request, bool $updating = false): array
    {
        return $request->validate([
            'name'            => ['required','string','max:190'],
            'photo'           => [$updating ? 'nullable' : 'nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'university_text' => ['nullable','string','max:190'],
            'college_text'    => ['nullable','string','max:190'],
            'major_text'      => ['nullable','string','max:190'],
            'sort_order'      => ['nullable','integer'],
            'is_active'       => ['nullable', Rule::in([0,1])],
        ], [], [
            'name' => 'الاسم',
            'photo'=> 'الصورة',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    public function index(Request $r)
    {
        $q = Content::with(['university','college','major','doctor'])->latest();

        if ($r->filled('scope'))       $q->where('scope',$r->scope);
        if ($r->filled('type'))        $q->where('type',$r->type);
        if ($r->filled('is_active'))   $q->where('is_active', (int)$r->is_active);
        if ($r->filled('university_id')) $q->where('university_id',$r->university_id);
        if ($r->filled('college_id'))  $q->where('college_id',$r->college_id);
        if ($r->filled('major_id'))    $q->where('major_id',$r->major_id);
        if ($r->filled('doctor_id'))   $q->where('doctor_id',$r->doctor_id);
        if ($s = $r->get('q'))         $q->where(fn($w)=>$w->where('title','like',"%$s%")->orWhere('description','like',"%$s%"));

        $contents = $q->paginate(15)->withQueryString();

        $universities = University::orderBy('name')->get();
        $colleges     = $r->filled('university_id') ? College::where('university_id',$r->university_id)->orderBy('name')->get() : College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();

        return view('admin.contents.index', compact('contents','universities','colleges','majors','doctors'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();

        return view('admin.contents.create', compact('universities','colleges','majors','doctors'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => ['required', Rule::in(['file','video','link'])],
            'scope'       => ['required', Rule::in(['global','university'])],

            // عند scope=university
            'university_id' => 'nullable|required_if:scope,university|exists:universities,id',
            'college_id'    => 'nullable|exists:colleges,id',
            'major_id'      => 'nullable|exists:majors,id',

            // التعامل مع المصدر
            'source_url'    => 'nullable|url',

            // الملف
            'file'          => 'nullable|file|max:20480', // 20MB

            // ربط دكتور (اختياري)
            'doctor_id'     => 'nullable|exists:doctors,id',

            'is_active'     => 'nullable|boolean',
        ]);

        // التحقق الشرطي لنوع المحتوى
        if ($r->type === 'file' && !$r->hasFile('file')) {
            return back()->withErrors(['file'=>'الملف مطلوب لنوع محتوى (ملف)'])->withInput();
        }
        if (in_array($r->type, ['video','link']) && !$r->filled('source_url')) {
            return back()->withErrors(['source_url'=>'الرابط مطلوب لنوع المحتوى المحدد'])->withInput();
        }

        $data = $r->only(['title','description','type','scope','university_id','college_id','major_id','doctor_id']);
        $data['is_active'] = (bool)$r->boolean('is_active');

        if ($r->type === 'file' && $r->hasFile('file')) {
            $data['file_path'] = $r->file('file')->store('contents','public');
            $data['source_url'] = null;
        } else {
            $data['file_path'] = null;
            $data['source_url'] = $r->source_url;
        }

        Content::create($data);

        return redirect()->route('admin.contents.index')->with('success','تم إضافة المحتوى.');
    }

    public function edit(Content $content)
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();

        return view('admin.contents.edit', compact('content','universities','colleges','majors','doctors'));
    }

    public function update(Request $r, Content $content)
    {
        $r->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => ['required', Rule::in(['file','video','link'])],
            'scope'       => ['required', Rule::in(['global','university'])],
            'university_id' => 'nullable|required_if:scope,university|exists:universities,id',
            'college_id'    => 'nullable|exists:colleges,id',
            'major_id'      => 'nullable|exists:majors,id',
            'source_url'    => 'nullable|url',
            'file'          => 'nullable|file|max:20480',
            'doctor_id'     => 'nullable|exists:doctors,id',
            'is_active'     => 'nullable|boolean',
        ]);

        if ($r->type === 'file' && !$content->file_path && !$r->hasFile('file')) {
            return back()->withErrors(['file'=>'الملف مطلوب لأن المحتوى من نوع ملف ولا يوجد ملف محفوظ مسبقًا'])->withInput();
        }
        if (in_array($r->type, ['video','link']) && !$r->filled('source_url') && !$content->source_url) {
            return back()->withErrors(['source_url'=>'الرابط مطلوب لنوع المحتوى المحدد'])->withInput();
        }

        $data = $r->only(['title','description','type','scope','university_id','college_id','major_id','doctor_id']);
        $data['is_active'] = (bool)$r->boolean('is_active');

        if ($r->type === 'file') {
            if ($r->hasFile('file')) {
                if ($content->file_path) Storage::disk('public')->delete($content->file_path);
                $data['file_path'] = $r->file('file')->store('contents','public');
            }
            $data['source_url'] = null;
        } else {
            // video/link
            if ($content->file_path) {
                Storage::disk('public')->delete($content->file_path);
            }
            $data['file_path'] = null;
            $data['source_url'] = $r->source_url;
        }

        $content->update($data);

        return redirect()->route('admin.contents.index')->with('success','تم تحديث المحتوى.');
    }

    public function destroy(Content $content)
    {
        if ($content->file_path) Storage::disk('public')->delete($content->file_path);
        $content->delete();
        return back()->with('success','تم حذف المحتوى.');
    }
}

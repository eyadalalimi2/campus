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
use App\Models\Material;
use App\Models\Device;

class ContentController extends Controller
{
    public function index(Request $r)
    {
        $q = Content::with(['university', 'college', 'major', 'doctor'])->latest();

        if ($r->filled('scope'))       $q->where('scope', $r->scope);
        if ($r->filled('type'))        $q->where('type', $r->type);
        if ($r->filled('is_active'))   $q->where('is_active', (int)$r->is_active);
        if ($r->filled('university_id')) $q->where('university_id', $r->university_id);
        if ($r->filled('college_id'))  $q->where('college_id', $r->college_id);
        if ($r->filled('major_id'))    $q->where('major_id', $r->major_id);
        if ($r->filled('doctor_id'))   $q->where('doctor_id', $r->doctor_id);
        if ($s = $r->get('q'))         $q->where(fn($w) => $w->where('title', 'like', "%$s%")->orWhere('description', 'like', "%$s%"));

        $contents = $q->paginate(15)->withQueryString();

        $universities = University::orderBy('name')->get();
        $colleges     = $r->filled('university_id') ? College::where('university_id', $r->university_id)->orderBy('name')->get() : College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();

        return view('admin.contents.index', compact('contents', 'universities', 'colleges', 'majors', 'doctors'));
    }

    public function create()
{
    $universities = University::orderBy('name')->get();
    $colleges     = College::orderBy('name')->get();
    $majors       = Major::orderBy('name')->get();
    $materials    = Material::orderBy('name')->get();
    $devices      = Device::orderBy('id')->get();

    $doctors      = Doctor::orderBy('name')->get();

    return view('admin.contents.create', compact(
        'universities','colleges','majors','materials','devices','doctors'
    ));
}


   public function store(Request $r)
{
    $DOC_ZIP_MIMES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'application/zip','application/x-zip-compressed',
        'application/x-7z-compressed','application/x-rar-compressed',
        'application/x-tar','application/x-gzip',
    ];

    $r->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'type'        => ['required', Rule::in(['file','video','link'])],
        'scope'       => ['required', Rule::in(['global','university'])],

        'university_id' => 'nullable|required_if:scope,university|exists:universities,id',
        'college_id'    => 'nullable|exists:colleges,id',
        'major_id'      => 'nullable|exists:majors,id',
        'material_id'   => 'nullable|exists:materials,id',

        'source_url'    => 'required_if:type,video,link|nullable|url|max:2048',
        'file'          => 'required_if:type,file|file|mimetypes:'.implode(',', $DOC_ZIP_MIMES).'|max:102400',

        'doctor_id'     => 'nullable|exists:doctors,id',

        'device_ids'    => 'array',
        'device_ids.*'  => 'integer|exists:devices,id',

        'is_active'     => 'nullable|boolean',
    ]);

    $data = $r->only([
        'title','description','type','scope',
        'university_id','college_id','major_id','material_id','doctor_id',
        'source_url'
    ]);
    $data['is_active'] = $r->has('is_active') ? 1 : 0;

    if ($r->type === 'file') {
        $data['file_path'] = $r->file('file')->store('contents','public');
        $data['source_url'] = null;
    } else {
        $data['file_path'] = null;
    }

    $content = Content::create($data);

    $content->devices()->sync($r->input('device_ids', []));

    return redirect()->route('admin.contents.index')->with('success','تم إضافة المحتوى.');
}



    public function edit(Content $content)
{
    $universities = University::orderBy('name')->get();
    $colleges     = College::orderBy('name')->get();
    $majors       = Major::orderBy('name')->get();
    $materials    = Material::orderBy('name')->get();
    $devices      = Device::orderBy('id')->get();
    $doctors      = Doctor::orderBy('name')->get();
    $selectedDevices = $content->devices()->pluck('devices.id')->all();
    $selectedTasks   = $content->tasks()->pluck('tasks.id')->all();

    return view('admin.contents.edit', compact(
        'content','universities','colleges','majors','materials','devices','doctors',
        'selectedDevices','selectedTasks'
    ));
}


    public function update(Request $r, Content $content)
{
    $DOC_ZIP_MIMES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'application/zip','application/x-zip-compressed',
        'application/x-7z-compressed','application/x-rar-compressed',
        'application/x-tar','application/x-gzip',
    ];

    $r->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'type'        => ['required', Rule::in(['file','video','link'])],
        'scope'       => ['required', Rule::in(['global','university'])],

        'university_id' => 'nullable|required_if:scope,university|exists:universities,id',
        'college_id'    => 'nullable|exists:colleges,id',
        'major_id'      => 'nullable|exists:majors,id',
        'material_id'   => 'nullable|exists:materials,id',

        'source_url'    => 'required_if:type,video,link|nullable|url|max:2048',
        'file'          => 'nullable|file|mimetypes:'.implode(',', $DOC_ZIP_MIMES).'|max:102400',

        'doctor_id'     => 'nullable|exists:doctors,id',

        'device_ids'    => 'array',
        'device_ids.*'  => 'integer|exists:devices,id',
    

        'is_active'     => 'nullable|boolean',
    ]);

    $data = $r->only([
        'title','description','type','scope',
        'university_id','college_id','major_id','material_id','doctor_id',
        'source_url'
    ]);
    $data['is_active'] = $r->has('is_active') ? 1 : 0;

    if ($r->type === 'file') {
        if ($r->hasFile('file')) {
            if ($content->file_path) Storage::disk('public')->delete($content->file_path);
            $data['file_path'] = $r->file('file')->store('contents','public');
        } else {
            $data['file_path'] = $content->file_path;
        }
        $data['source_url'] = null;
    } else {
        if ($content->file_path) Storage::disk('public')->delete($content->file_path);
        $data['file_path'] = null;
        // source_url مُتحقق منه
    }

    $content->update($data);

    $content->devices()->sync($r->input('device_ids', []));

    return redirect()->route('admin.contents.index')->with('success','تم تحديث المحتوى.');
}



    public function destroy(Content $content)
    {
        if ($content->file_path) Storage::disk('public')->delete($content->file_path);
        $content->delete();
        return back()->with('success', 'تم حذف المحتوى.');
    }
}

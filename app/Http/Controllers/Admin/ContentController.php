<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreContentRequest;
use App\Http\Requests\Admin\UpdateContentRequest;
use App\Models\Content;
use App\Models\University;
use App\Models\UniversityBranch;
use App\Models\College;
use App\Models\Major;
use App\Models\Material;
use App\Models\Doctor;
use App\Models\Device;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Content::class, 'content');
    }

    public function index(Request $request)
    {
        // فلاتر واجهة الإدارة
        $filters = [
            'q'             => trim((string) $request->get('q', '')),
            'status'        => $request->string('status')->toString(),
            'type'          => $request->string('type')->toString(),
            'university_id' => $request->integer('university_id') ?: null,
            'branch_id'     => $request->integer('branch_id') ?: null, // ← جديد
            'college_id'    => $request->integer('college_id') ?: null,
            'major_id'      => $request->integer('major_id') ?: null,
            'material_id'   => $request->integer('material_id') ?: null,
            'doctor_id'     => $request->integer('doctor_id') ?: null,
            'is_active'     => $request->filled('is_active') ? (int) $request->boolean('is_active') : null,
            'from'          => $request->string('from')->toString(),
            'to'            => $request->string('to')->toString(),
        ];

        // الاستعلام باستخدام سكوبات Content
        $contents = Content::query()
            ->with(['university','branch','college','major','material','doctor','devices','publishedBy'])
            ->filter($filters)
            ->orderForFeed()
            ->paginate(15)
            ->withQueryString();

        // مصادر القوائم حسب التسلسل الهرمي
        $universities = University::orderBy('name')->get();

        $branches = collect();
        if ($filters['university_id']) {
            $branches = UniversityBranch::where('university_id', $filters['university_id'])
                ->orderBy('name')->get();
        }

        $colleges = collect();
        if ($filters['branch_id']) {
            $colleges = College::where('branch_id', $filters['branch_id'])
                ->orderBy('name')->get();
        }

        $majors = collect();
        if ($filters['college_id']) {
            $majors = Major::where('college_id', $filters['college_id'])
                ->orderBy('name')->get();
        }

        // مواد ودكاترة مفلترة اختياريًا لمساعدة المستخدم
        $materialsQ = Material::query()->orderBy('name');
        if ($filters['university_id']) $materialsQ->forUniversity($filters['university_id']);
        if ($filters['branch_id'])     $materialsQ->forBranch($filters['branch_id']);
        if ($filters['college_id'])    $materialsQ->forCollege($filters['college_id']);
        if ($filters['major_id'])      $materialsQ->forMajor($filters['major_id']);
        $materials = $materialsQ->get();

        $doctorsQ = Doctor::query()->orderBy('name');
        if ($filters['university_id']) $doctorsQ->forUniversity($filters['university_id']);
        if ($filters['branch_id'])     $doctorsQ->forBranch($filters['branch_id']);
        if ($filters['college_id'])    $doctorsQ->forCollege($filters['college_id']);
        if ($filters['major_id'])      $doctorsQ->forMajor($filters['major_id']);
        $doctors = $doctorsQ->get();

        return view('admin.contents.index', compact(
            'contents', 'universities', 'branches', 'colleges', 'majors', 'materials', 'doctors', 'filters'
        ));
    }

    public function create(Request $request)
    {
        $universities = University::orderBy('name')->get();

        $branches = collect();
        if ($request->filled('university_id')) {
            $branches = UniversityBranch::where('university_id', (int)$request->input('university_id'))
                ->orderBy('name')->get();
        }

        $colleges = collect();
        if ($request->filled('branch_id')) {
            $colleges = College::where('branch_id', (int)$request->input('branch_id'))
                ->orderBy('name')->get();
        }

        $majors = collect();
        if ($request->filled('college_id')) {
            $majors = Major::where('college_id', (int)$request->input('college_id'))
                ->orderBy('name')->get();
        }

        // مواد/دكاترة/أجهزة للربط
        $materials = Material::orderBy('name')->get();
        $doctors   = Doctor::orderBy('name')->get();
        $devices   = Device::orderBy('name')->get();

        return view('admin.contents.create', compact(
            'universities','branches','colleges','majors','materials','doctors','devices'
        ));
    }

    public function store(StoreContentRequest $request)
    {
        $data = $request->validated();

        // توحيد وترتيب الحقول حسب النوع والتسلسل الهرمي
        $this->normalizeByType($data, $request);

        DB::transaction(function () use (&$data) {
            $content = new Content();
            $content->fill($data);

            if (($data['status'] ?? null) === Content::STATUS_PUBLISHED) {
                $content->published_by_admin_id = auth('admin')->id();
                $content->published_at = now();
            }

            $content->save();

            if (!empty($data['device_ids']) && is_array($data['device_ids'])) {
                $content->devices()->sync(array_filter($data['device_ids']));
            }
        });

        return redirect()->route('admin.contents.index')
            ->with('success','تم إنشاء المحتوى بنجاح.');
    }

    public function edit(Content $content)
    {
        $content->load(['university','branch','college','major','material','doctor','devices']);

        $universities = University::orderBy('name')->get();

        $branches = collect();
        if ($content->university_id) {
            $branches = UniversityBranch::where('university_id', $content->university_id)
                ->orderBy('name')->get();
        }

        $colleges = collect();
        if ($content->branch_id) {
            $colleges = College::where('branch_id', $content->branch_id)
                ->orderBy('name')->get();
        }

        $majors = collect();
        if ($content->college_id) {
            $majors = Major::where('college_id', $content->college_id)
                ->orderBy('name')->get();
        }

        $materials = Material::orderBy('name')->get();
        $doctors   = Doctor::orderBy('name')->get();
        $devices   = Device::orderBy('name')->get();

        return view('admin.contents.edit', compact(
            'content','universities','branches','colleges','majors','materials','doctors','devices'
        ));
    }

    public function update(UpdateContentRequest $request, Content $content)
    {
        $data = $request->validated();

        $this->normalizeByType($data, $request, $content);

        DB::transaction(function () use (&$data, $content) {
            if (($data['status'] ?? null) === Content::STATUS_PUBLISHED && !$content->published_at) {
                $data['published_by_admin_id'] = auth('admin')->id();
                $data['published_at'] = now();
            }

            if (!empty($data['changelog'])) {
                $data['version'] = (int) ($content->version ?? 1) + 1;
            }

            $content->fill($data)->save();

            $content->devices()->sync($data['device_ids'] ?? []);
        });

        return redirect()->route('admin.contents.index')
            ->with('success','تم تحديث المحتوى بنجاح.');
    }

    public function destroy(Content $content)
    {
        if ($content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }
        $content->delete();

        return redirect()->route('admin.contents.index')
            ->with('success','تم حذف المحتوى بنجاح.');
    }

    /* ============================================
     | Helpers
     |===========================================*/
    /**
     * توحيد الحقول حسب النوع والتسلسل الهرمي:
     * - file: رفع ملف وتفريغ source_url.
     * - video/link: تفريغ file_path وتثبيت source_url.
     * - التسلسل: إن لم يوجد فرع ← صفّر الكلية/التخصص؛ وإن لم توجد كلية ← صفّر التخصص.
     */
    private function normalizeByType(array &$data, Request $request, ?Content $existing = null): void
    {
        $type = $data['type'] ?? Content::TYPE_FILE;

        // الجامعة إلزامية للمحتوى الخاص
        if (empty($data['university_id'])) {
            abort(422, 'university_id مطلوب للمحتوى الخاص.');
        }

        // ترتيب هرمي: إن غاب فرع لا معنى لكلية/تخصص
        if (empty($data['branch_id'])) {
            $data['college_id'] = null;
            $data['major_id']   = null;
        }
        // إن غابت الكلية لا معنى للتخصص
        if (empty($data['college_id'])) {
            $data['major_id'] = null;
        }

        if ($type === Content::TYPE_FILE) {
            if ($request->hasFile('file')) {
                if ($existing && $existing->file_path) {
                    Storage::disk('public')->delete($existing->file_path);
                }
                $data['file_path'] = $request->file('file')->store('contents', 'public');
            } elseif (!$existing) {
                $data['file_path'] = $data['file_path'] ?? null;
            }
            $data['source_url'] = null;

        } else { // video | link
            if ($existing && $existing->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $data['file_path']  = null;
            $data['source_url'] = $data['source_url'] ?? null;
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }
    }
}

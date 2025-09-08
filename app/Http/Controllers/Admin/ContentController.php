<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreContentRequest;
use App\Http\Requests\Admin\UpdateContentRequest;
use App\Models\Content;
use App\Models\University;
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
        // ربط بالسياسة (إن وُجدت ContentPolicy)
        // $this->authorizeResource(Content::class, 'content');
    }

    public function index(Request $request)
    {
        $filters = [
            'q'             => $request->string('q')->toString(),
            'status'        => $request->string('status')->toString(),
            'type'          => $request->string('type')->toString(),
            'university_id' => $request->integer('university_id') ?: null,
            'college_id'    => $request->integer('college_id') ?: null,
            'major_id'      => $request->integer('major_id') ?: null,
            'material_id'   => $request->integer('material_id') ?: null,
            'doctor_id'     => $request->integer('doctor_id') ?: null,
            'is_active'     => $request->filled('is_active') ? (int) $request->boolean('is_active') : null,
            'from'          => $request->string('from')->toString(),
            'to'            => $request->string('to')->toString(),
        ];

        $contents = Content::query()
            ->with(['university','college','major','material','doctor','devices','publishedBy'])
            // بحث حر
            ->when($filters['q'], function ($q, $term) {
                $q->where(function ($w) use ($term) {
                    $w->where('title', 'like', '%'.$term.'%')
                      ->orWhere('description', 'like', '%'.$term.'%');
                });
            })
            // حالة النشر والنشاط
            ->when($filters['status'], fn($q, $s) => $q->where('status', $s))
            ->when(!is_null($filters['is_active']), fn($q) => $q->where('is_active', (bool)$filters['is_active']))
            // نوع المحتوى
            ->when($filters['type'], fn($q, $t) => $q->where('type', $t))
            // المفاتيح المرجعية
            ->when($filters['university_id'], fn($q, $id) => $q->where('university_id', $id))
            ->when($filters['college_id'], fn($q, $id) => $q->where('college_id', $id))
            ->when($filters['major_id'], fn($q, $id) => $q->where('major_id', $id))
            ->when($filters['material_id'], fn($q, $id) => $q->where('material_id', $id))
            ->when($filters['doctor_id'], fn($q, $id) => $q->where('doctor_id', $id))
            // التاريخ
            ->when($filters['from'], fn($q, $d) => $q->where('published_at', '>=', $d))
            ->when($filters['to'],   fn($q, $d) => $q->where('published_at', '<=', $d))
            // ترتيب مناسب لواجهة الإدارة
            ->orderByRaw("FIELD(status, 'published','in_review','draft','archived')")
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // مصادر الفلاتر
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $materials    = Material::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();

        return view('admin.contents.index', compact(
            'contents', 'universities', 'colleges', 'majors', 'materials', 'doctors', 'filters'
        ));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $materials    = Material::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();
        $devices      = Device::orderBy('name')->get();

        return view('admin.contents.create', compact(
            'universities','colleges','majors','materials','doctors','devices'
        ));
    }

    public function store(StoreContentRequest $request)
    {
        $data = $request->validated();

        // ضمان الاتساق المنطقي حسب النوع
        $this->normalizeByType($data, $request);

        DB::transaction(function () use (&$data) {
            $content = new Content();
            $content->fill($data);

            // نشر فوري إن كانت الحالة published
            if (($data['status'] ?? null) === 'published') {
                $content->published_by_admin_id = auth('admin')->id();
                $content->published_at = now();
            }

            $content->save();

            // أجهزة الربط
            if (!empty($data['device_ids']) && is_array($data['device_ids'])) {
                $content->devices()->sync(array_filter($data['device_ids']));
            }
        });

        return redirect()->route('admin.contents.index')->with('success','تم إنشاء المحتوى بنجاح.');
    }

    public function edit(Content $content)
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $materials    = Material::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();
        $devices      = Device::orderBy('name')->get();

        return view('admin.contents.edit', compact(
            'content','universities','colleges','majors','materials','doctors','devices'
        ));
    }

    public function update(UpdateContentRequest $request, Content $content)
    {
        $data = $request->validated();

        // تحميل/تنظيف الملفات حسب النوع
        $this->normalizeByType($data, $request, $content);

        DB::transaction(function () use (&$data, $content) {

            // إن كان سيتم “نشر” محتوى لم يُنشر من قبل
            if (($data['status'] ?? null) === 'published' && !$content->published_at) {
                $data['published_by_admin_id'] = auth('admin')->id();
                $data['published_at'] = now();
            }

            // إدارة الإصدارات (اختياري): زيّد النسخة فقط عند وجود changelog
            if (!empty($data['changelog'])) {
                $data['version'] = (int) ($content->version ?? 1) + 1;
            }

            $content->fill($data)->save();

            // Pivot الأجهزة
            $content->devices()->sync($data['device_ids'] ?? []);
        });

        return redirect()->route('admin.contents.index')->with('success','تم تحديث المحتوى بنجاح.');
    }

    public function destroy(Content $content)
    {
        // حذف الملف المخزّن إن وجد
        if ($content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }
        $content->delete();

        return redirect()->route('admin.contents.index')->with('success','تم حذف المحتوى بنجاح.');
    }

    /* ============================================
     | Helpers
     |============================================*/
    /**
     * توحيد الحقول حسب نوع المحتوى:
     * - file: يرفع ملفًا ويُخلي source_url
     * - video/link: يُخلي file_path (لا ملف)، ويبقي/يضبط source_url
     * كما تُطبّق قيود الاتساق (جامعة إلزامية).
     */
    private function normalizeByType(array &$data, Request $request, ?Content $existing = null): void
    {
        $type = $data['type'] ?? 'file';

        // الجامعة إلزامية للمحتوى الخاص
        if (empty($data['university_id'])) {
            abort(422, 'university_id مطلوب للمحتوى الخاص.');
        }

        if ($type === 'file') {
            // رفع ملف جديد إن وُجد
            if ($request->hasFile('file')) {
                // حذف القديم عند التحديث
                if ($existing && $existing->file_path) {
                    Storage::disk('public')->delete($existing->file_path);
                }
                $path = $request->file('file')->store('contents', 'public');
                $data['file_path'] = $path;
            } elseif (!$existing) {
                // في الإنشاء: الملف مطلوب نوعًا، إن لم يأتِ ضمن الطلب سيُمسكه الـ FormRequest
                $data['file_path'] = $data['file_path'] ?? null;
            }
            // في وضع الملف لا نحتفظ بـ source_url
            $data['source_url'] = null;

        } else {
            // video | link: لا ملف
            if ($existing && $existing->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $data['file_path'] = null;

            // اترك/ثبت المصدر
            $data['source_url'] = $data['source_url'] ?? null;
        }

        // ضبط افتراضي لحالة النشاط إن لم تُرسل
        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }
    }
}

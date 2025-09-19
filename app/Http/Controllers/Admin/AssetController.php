<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAssetRequest;
use App\Http\Requests\Admin\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Material;
use App\Models\Device;
use App\Models\Doctor;
use App\Models\Discipline;
use App\Models\Program;
use App\Models\PublicMajor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Asset::class, 'asset');
    }

    public function index(Request $request)
    {
        $filters = [
            'q'                 => $request->string('q')->toString(),
            'category'          => $request->string('category')->toString(),   // youtube|file|reference|question_bank|curriculum|book
            'status'            => $request->string('status')->toString(),     // draft|in_review|published|archived
            'is_active'         => $request->filled('is_active') ? (int) $request->boolean('is_active') : null,
            'discipline_id'     => $request->integer('discipline_id') ?: null,
            'program_id'        => $request->integer('program_id') ?: null,
            'material_id'       => $request->integer('material_id') ?: null,
            'device_id'         => $request->integer('device_id') ?: null,
            'doctor_id'         => $request->integer('doctor_id') ?: null,
            'public_major_id'   => $request->integer('public_major_id') ?: null, // فلترة عبر جمهور الأصل العام
            'from'              => $request->string('from')->toString(),
            'to'                => $request->string('to')->toString(),
        ];

        $assets = Asset::query()
            ->with(['material','device','doctor','discipline','program','publishedBy','publicMajors.publicCollege'])
            ->when($filters['q'], function ($q, $term) {
                $q->where(function ($w) use ($term) {
                    $w->where('title', 'like', '%'.$term.'%')
                      ->orWhere('description', 'like', '%'.$term.'%');
                });
            })
            ->when($filters['category'], fn($q, $c) => $q->where('category', $c))
            ->when($filters['status'],   fn($q, $s) => $q->where('status', $s))
            ->when(!is_null($filters['is_active']), fn($q) => $q->where('is_active', (bool)$filters['is_active']))
            ->when($filters['discipline_id'], fn($q, $id) => $q->where('discipline_id', $id))
            ->when($filters['program_id'],    fn($q, $id) => $q->where('program_id', $id))
            ->when($filters['material_id'],   fn($q, $id) => $q->where('material_id', $id))
            ->when($filters['device_id'],     fn($q, $id) => $q->where('device_id', $id))
            ->when($filters['doctor_id'],     fn($q, $id) => $q->where('doctor_id', $id))
            ->when($filters['from'],          fn($q, $d)  => $q->where('published_at', '>=', $d))
            ->when($filters['to'],            fn($q, $d)  => $q->where('published_at', '<=', $d))
            // فلترة عبر جمهور الأصل العام (public_majors عبر pivot)
            ->when($filters['public_major_id'], fn($q, $pmid) =>
                $q->whereHas('publicMajors', fn($pq) => $pq->where('public_majors.id', $pmid))
            )
            ->orderByRaw("FIELD(status,'published','in_review','draft','archived')")
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $disciplines = Discipline::orderBy('name')->get();
        $programs    = Program::orderBy('name')->get();
        $materials   = Material::orderBy('name')->get();
        $devices     = Device::orderBy('name')->get();
        $doctors     = Doctor::orderBy('name')->get();
        $publicMajors= PublicMajor::active()->with('publicCollege')->orderBy('name')->get();

        return view('admin.assets.index', compact(
            'assets','disciplines','programs','materials','devices','doctors','publicMajors','filters'
        ));
    }

    public function create()
    {
        $materials    = Material::orderBy('name')->get();
        $devices      = Device::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();
        $disciplines  = Discipline::orderBy('name')->get();
        $programs     = Program::orderBy('name')->get();
        $publicMajors = PublicMajor::active()->with('publicCollege')->orderBy('name')->get();

        return view('admin.assets.create', compact(
            'materials','devices','doctors','disciplines','programs','publicMajors'
        ));
    }

    public function store(StoreAssetRequest $request)
    {
        $data = $request->validated();

        $this->normalizeByCategory($data, $request);

        DB::transaction(function () use (&$data) {
            $asset = new Asset();
            $asset->fill($data);

            // تعيين بيانات النشر عند أول نشر
            if (($data['status'] ?? null) === 'published') {
                // لاحظ: عدّل حارس المصادقة حسب نظامك (auth()->id() أو auth('admin')->id())
                $asset->published_by_admin_id = auth()->id();
                $asset->published_at = now();
            }

            $asset->save();

            // جمهور الأصل العام (public_majors عبر pivot asset_public_major) مع primary/priority
            $sync = [];
            $selected = $data['public_major_ids'] ?? [];
            $priorities = $data['public_major_priorities'] ?? [];
            $primaryId  = (int)($data['primary_public_major_id'] ?? 0);

            foreach ((array)$selected as $pmId) {
                $pmId = (int)$pmId;
                $sync[$pmId] = [
                    'is_primary' => $primaryId === $pmId ? 1 : 0,
                    'priority'   => (int)($priorities[$pmId] ?? 0),
                ];
            }
            $asset->publicMajors()->sync($sync);
        });

        return redirect()->route('admin.assets.index')->with('success','تم إنشاء الأصل بنجاح.');
    }

    public function edit(Asset $asset)
    {
        $materials    = Material::orderBy('name')->get();
        $devices      = Device::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();
        $disciplines  = Discipline::orderBy('name')->get();
        $programs     = Program::orderBy('name')->get();
        $publicMajors = PublicMajor::active()->with('publicCollege')->orderBy('name')->get();

        // تحميل علاقات الجمهور العام لعرضها في الواجهة
        $asset->load('publicMajors.publicCollege');

        return view('admin.assets.edit', compact(
            'asset','materials','devices','doctors','disciplines','programs','publicMajors'
        ));
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $data = $request->validated();

        $this->normalizeByCategory($data, $request, $asset);

        DB::transaction(function () use (&$data, $asset) {
            // نشر أول مرة
            if (($data['status'] ?? null) === 'published' && ! $asset->published_at) {
                $data['published_by_admin_id'] = auth()->id();
                $data['published_at'] = now();
            }

            $asset->fill($data)->save();

            // مزامنة الجمهور العام
            $sync = [];
            $selected  = $data['public_major_ids'] ?? [];
            $priorities= $data['public_major_priorities'] ?? [];
            $primaryId = (int)($data['primary_public_major_id'] ?? 0);

            foreach ((array)$selected as $pmId) {
                $pmId = (int)$pmId;
                $sync[$pmId] = [
                    'is_primary' => $primaryId === $pmId ? 1 : 0,
                    'priority'   => (int)($priorities[$pmId] ?? 0),
                ];
            }
            $asset->publicMajors()->sync($sync);
        });

        return redirect()->route('admin.assets.index')->with('success','تم تحديث الأصل بنجاح.');
    }

    public function destroy(Asset $asset)
    {
        // حذف الملف المخزّن إن وجد
        if ($asset->file_path) {
            Storage::disk('public')->delete($asset->file_path);
        }

        // فصل الروابط مع التخصصات العامة ثم حذف الأصل
        $asset->publicMajors()->detach();
        $asset->delete();

        return redirect()->route('admin.assets.index')->with('success','تم حذف الأصل بنجاح.');
    }

    /* ============================================
     | Helpers
     |============================================*/
    /**
     * توحيد الحقول حسب فئة الأصل (category):
     * - youtube: يُفرّغ file_path/external_url، ويُبقي video_url
     * - file: يرفع ملفًا ويُفرّغ video_url/external_url
     * - reference/book/curriculum/question_bank: يُفرّغ file_path/video_url، ويبقي external_url (إن وُجد)
     */
    private function normalizeByCategory(array &$data, Request $request, ?Asset $existing = null): void
    {
        $category = $data['category'] ?? 'file';

        switch ($category) {
            case 'youtube':
                // لا ملفات ولا روابط خارجية هنا
                if ($existing && $existing->file_path) {
                    Storage::disk('public')->delete($existing->file_path);
                }
                $data['file_path']    = null;
                $data['external_url'] = null;
                // video_url يُمرّر من الـ FormRequest
                break;

            case 'file':
                // رفع الملف الجديد إن وُجد
                if ($request->hasFile('file')) {
                    if ($existing && $existing->file_path) {
                        Storage::disk('public')->delete($existing->file_path);
                    }
                    $path = $request->file('file')->store('assets', 'public');
                    $data['file_path'] = $path;
                } elseif (! $existing) {
                    // في الإنشاء فقط، إن لم يُرسل الملف سيُمسكه الـ FormRequest
                    $data['file_path'] = $data['file_path'] ?? null;
                }
                // تنظيف الحقول الأخرى
                $data['video_url']    = null;
                $data['external_url'] = null;
                break;

            default:
                // reference | question_bank | curriculum | book
                if ($existing && $existing->file_path) {
                    Storage::disk('public')->delete($existing->file_path);
                }
                $data['file_path']    = null;
                $data['video_url']    = null;
                // external_url يُمرّر من الـ FormRequest (اختياري/مطلوب حسب سياستك)
                break;
        }

        // ضبط افتراضي للنشاط إن لم يُرسل
        if (! isset($data['is_active'])) {
            $data['is_active'] = true;
        }
    }
}

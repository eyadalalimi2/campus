<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicalContentRequest;
use App\Models\Content;
use App\Models\University;
use App\Models\UniversityBranch;
use App\Models\College;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalContentController extends Controller
{
    /**
     * قائمة المحتوى الطبي الخاص
     */
    public function index(Request $request)
    {
        $filters = [
            'q'             => $request->query('q'),
            'status'        => $request->query('status'),
            'type'          => $request->query('type'),
            'university_id' => $request->query('university_id'),
            'branch_id'     => $request->query('branch_id'),
            'college_id'    => $request->query('college_id'),
            'major_id'      => $request->query('major_id'),
            'is_active'     => $request->query('is_active'),
            'from'          => $request->query('from'),
            'to'            => $request->query('to'),
        ];

        $contents = Content::query()
            ->whereNotNull('university_id')            // محتوى خاص فقط
            ->when(true, fn($q) => $q->filter($filters))
            ->orderForFeed()
            ->with(['university','branch','college','major'])
            ->paginate(20)
            ->appends($filters);

        // القوائم (لا يوجد مسارات إضافية: نجلب كل شيء مرة واحدة ونفلتر على الواجهة JS)
        $universities = University::orderBy('name')->get(['id','name']);
        $branches     = UniversityBranch::orderBy('name')->get(['id','name','university_id']);
        $colleges     = College::orderBy('name')->get(['id','name','branch_id','university_id']);
        $majors       = Major::orderBy('name')->get(['id','name','college_id']);

        return view('admin.medical_contents.index', compact(
            'contents','filters','universities','branches','colleges','majors'
        ));
    }

    /**
     * فورم الإنشاء
     */
    public function create()
    {
        $universities = University::orderBy('name')->get(['id','name']);
        $branches     = UniversityBranch::orderBy('name')->get(['id','name','university_id']);
        $colleges     = College::orderBy('name')->get(['id','name','branch_id','university_id']);
        $majors       = Major::orderBy('name')->get(['id','name','college_id']);

        return view('admin.medical_contents.create', compact(
            'universities','branches','colleges','majors'
        ));
    }

    /**
     * حفظ الإنشاء
     */
    public function store(MedicalContentRequest $request)
    {
        $data = $request->validated();

        // فرض نوع المحتوى الطبي الخاص: ملف أو رابط فقط
        if (!in_array($data['type'], [Content::TYPE_FILE, Content::TYPE_LINK], true)) {
            return back()->withErrors(['type' => 'نوع المحتوى الخاص يجب أن يكون ملف أو رابط فقط'])->withInput();
        }

        // تحميل الملف إن وُجد
        if ($data['type'] === Content::TYPE_FILE && $request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('contents', 'public');
            $data['source_url'] = null;
        } elseif ($data['type'] === Content::TYPE_LINK) {
            $data['file_path'] = null;
        }

        // حالة النشر
        $data['is_active']   = (bool) ($data['is_active'] ?? false);
        $data['status']      = $data['status'] ?? Content::STATUS_DRAFT;

        if ($data['status'] === Content::STATUS_PUBLISHED && $data['is_active']) {
            $data['published_at'] = now();
            $data['published_by_admin_id'] = auth('admin')->id();
        }

        $content = Content::create($data);

        return redirect()->route('admin.medical_contents.index')
            ->with('success', 'تم إنشاء المحتوى الطبي الخاص بنجاح');
    }

    /**
     * فورم التعديل
     */
    public function edit(Content $medical_content)
    {
        // حماية: لا نسمح بتعديل محتوى عام من هذه الواجهة
        if (is_null($medical_content->university_id)) {
            abort(404);
        }

        $universities = University::orderBy('name')->get(['id','name']);
        $branches     = UniversityBranch::orderBy('name')->get(['id','name','university_id']);
        $colleges     = College::orderBy('name')->get(['id','name','branch_id','university_id']);
        $majors       = Major::orderBy('name')->get(['id','name','college_id']);

        return view('admin.medical_contents.edit', compact(
            'medical_content','universities','branches','colleges','majors'
        ));
    }

    /**
     * حفظ التعديل
     */
    public function update(MedicalContentRequest $request, Content $medical_content)
    {
        if (is_null($medical_content->university_id)) {
            abort(404);
        }

        $data = $request->validated();

        if (!in_array($data['type'], [Content::TYPE_FILE, Content::TYPE_LINK], true)) {
            return back()->withErrors(['type' => 'نوع المحتوى الخاص يجب أن يكون ملف أو رابط فقط'])->withInput();
        }

        // استبدال الملف إن تم رفع واحد جديد
        if ($data['type'] === Content::TYPE_FILE && $request->hasFile('file')) {
            if ($medical_content->file_path) {
                Storage::disk('public')->delete($medical_content->file_path);
            }
            $data['file_path'] = $request->file('file')->store('contents', 'public');
            $data['source_url'] = null;
        } elseif ($data['type'] === Content::TYPE_LINK) {
            // رابط: نفرغ مسار الملف إن كان قديماً
            if ($medical_content->file_path) {
                Storage::disk('public')->delete($medical_content->file_path);
            }
            $data['file_path'] = null;
        }

        // حالة النشر
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if (($data['status'] ?? $medical_content->status) === Content::STATUS_PUBLISHED && $data['is_active']) {
            $data['published_at'] = $medical_content->published_at ?: now();
            $data['published_by_admin_id'] = $medical_content->published_by_admin_id ?: auth('admin')->id();
        } else {
            // إن عاد إلى draft أو غير مفعّل، لا نغيّر published_at إلا لو أردت مسحه
        }

        $medical_content->update($data);

        return redirect()->route('admin.medical_contents.index')
            ->with('success','تم تحديث المحتوى الطبي الخاص بنجاح');
    }

    /**
     * حذف
     */
    public function destroy(Content $medical_content)
    {
        if (is_null($medical_content->university_id)) {
            abort(404);
        }

        if ($medical_content->file_path) {
            Storage::disk('public')->delete($medical_content->file_path);
        }

        $medical_content->delete();

        return back()->with('success','تم حذف المحتوى الطبي الخاص');
    }
}
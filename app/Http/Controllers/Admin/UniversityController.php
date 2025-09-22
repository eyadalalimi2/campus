<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UniversityRequest;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UniversityController extends Controller
{
    public function index(Request $r)
    {
        $q = University::query()
            // إن رغبت بإظهار الأعداد: ->withCount('branches')
            ->orderBy('name');

        if ($search = trim((string) $r->get('q'))) {
            $q->where(function ($w) use ($search) {
                $w->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($r->filled('is_active')) {
            $q->where('is_active', (bool) $r->boolean('is_active'));
        }

        $universities = $q->paginate(12)->withQueryString();

        return view('admin.universities.index', compact('universities'));
    }

    public function create()
    {
        return view('admin.universities.create');
    }

    public function store(UniversityRequest $r)
    {
        $data = $r->validated();

        $this->normalizeBooleans($data, $r);
        $this->normalizeTheme($data);

        if ($r->hasFile('logo')) {
            $data['logo_path'] = $r->file('logo')->store('universities', 'public');
        }

        University::create($data);

        return redirect()
            ->route('admin.universities.index')
            ->with('success', 'تم إنشاء الجامعة بنجاح.');
    }

    public function edit(University $university)
    {
        return view('admin.universities.edit', compact('university'));
    }

    public function update(UniversityRequest $r, University $university)
    {
        $data = $r->validated();

        $this->normalizeBooleans($data, $r);
        $this->normalizeTheme($data);

        if ($r->hasFile('logo')) {
            if ($university->logo_path && Storage::disk('public')->exists($university->logo_path)) {
                Storage::disk('public')->delete($university->logo_path);
            }
            $data['logo_path'] = $r->file('logo')->store('universities', 'public');
        }

        $university->update($data);

        return redirect()
            ->route('admin.universities.index')
            ->with('success', 'تم تحديث الجامعة بنجاح.');
    }

    public function destroy(University $university)
    {
        if ($university->logo_path) {
            Storage::disk('public')->delete($university->logo_path);
        }

        $university->delete();

        return back()->with('success', 'تم حذف الجامعة.');
    }

    /* ===========================
     | Helpers
     |===========================*/
    private function normalizeBooleans(array &$data, Request $r): void
    {
        // استخدم boolean() لضمان التحويل الصحيح من checkbox
        $data['is_active']        = (bool) $r->boolean('is_active');
        $data['use_default_theme'] = (bool) $r->boolean('use_default_theme');
    }

    /**
     * إن كان use_default_theme = 1، نفرّغ الحقول المخصّصة (حتى لا تبقى قيم قديمة).
     * وإلا نضمن قيمًا سليمة للثيم المخصّص.
     */
    private function normalizeTheme(array &$data): void
    {
        if (!array_key_exists('use_default_theme', $data)) {
            // احتياط: اعتبر الافتراضي false إن لم يأتِ الحقل
            $data['use_default_theme'] = false;
        }

        if ($data['use_default_theme']) {
            $data['primary_color']   = null;
            $data['secondary_color'] = null;
            $data['theme_mode']      = null; // أو اتركها null لتعود لاعدادات التطبيق العامة
        } else {
            // يمكن ضبط قواعد افتراضية عند تخصيص الثيم
            $data['theme_mode']      = $data['theme_mode']      ?? 'light';  // light|dark
            $data['primary_color']   = $data['primary_color']   ?? '#2c3e50';
            $data['secondary_color'] = $data['secondary_color'] ?? '#18bc9c';
        }
    }
}

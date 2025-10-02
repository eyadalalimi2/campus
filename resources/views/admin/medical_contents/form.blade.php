@php
    $row = $row ?? null;
    $mode = $mode ?? 'create';
@endphp

<div class="row g-3">

    {{-- العنوان والوصف --}}
    <div class="col-md-6">
        <label class="form-label">العنوان *</label>
        <input type="text" name="title" class="form-control" required
               value="{{ old('title', $row->title ?? '') }}">
        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">الوصف</label>
        <input type="text" name="description" class="form-control"
               value="{{ old('description', $row->description ?? '') }}">
        @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    {{-- نوع المحتوى --}}
    <div class="col-md-3">
        <label class="form-label">النوع *</label>
        <select name="type" id="mc_type" class="form-select" required>
            @php $t = old('type', $row->type ?? 'file'); @endphp
            <option value="file" @selected($t==='file')>ملف (PDF)</option>
            <option value="link" @selected($t==='link')>رابط</option>
        </select>
        @error('type')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    {{-- ملف/رابط --}}
    <div class="col-md-6" id="mc_file_wrap">
        <label class="form-label">الملف @if($mode==='create')* @endif</label>
        <input type="file" name="file" class="form-control" accept="application/pdf">
        @if(!empty($row?->file_path))
            <div class="form-text">ملف حالي: <a href="{{ $row->file_url }}" target="_blank">عرض</a></div>
        @endif
        @error('file')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6" id="mc_link_wrap" style="display:none">
        <label class="form-label">الرابط *</label>
        <input type="url" name="source_url" class="form-control" placeholder="https://..."
               value="{{ old('source_url', $row->source_url ?? '') }}">
        @error('source_url')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-12"><hr></div>

    {{-- المسار المؤسسي --}}
    <div class="col-md-3">
        <label class="form-label">الجامعة *</label>
        <select name="university_id" id="mc_university" class="form-select" required>
            <option value="">— اختر —</option>
            @foreach($universities as $u)
                <option value="{{ $u->id }}" @selected((old('university_id', $row->university_id ?? '')==$u->id))>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>
        @error('university_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">الفرع</label>
        <select name="branch_id" id="mc_branch" class="form-select">
            <option value="">— الكل —</option>
            @foreach($branches as $b)
                <option value="{{ $b->id }}" data-university="{{ $b->university_id }}"
                    @selected(old('branch_id', $row->branch_id ?? '')==$b->id)>
                    {{ $b->name }}
                </option>
            @endforeach
        </select>
        @error('branch_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">الكلية</label>
        <select name="college_id" id="mc_college" class="form-select">
            <option value="">— الكل —</option>
            @foreach($colleges as $c)
                <option value="{{ $c->id }}" data-branch="{{ $c->branch_id }}" data-university="{{ $c->university_id }}"
                    @selected(old('college_id', $row->college_id ?? '')==$c->id)>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
        @error('college_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">التخصص</label>
        <select name="major_id" id="mc_major" class="form-select">
            <option value="">— الكل —</option>
            @foreach($majors as $m)
                <option value="{{ $m->id }}" data-college="{{ $m->college_id }}"
                    @selected(old('major_id', $row->major_id ?? '')==$m->id)>
                    {{ $m->name }}
                </option>
            @endforeach
        </select>
        @error('major_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-12"><hr></div>

    {{-- حالة النشر + تفعيل --}}
    <div class="col-md-3">
        <label class="form-label">حالة النشر *</label>
        @php $st = old('status', $row->status ?? 'draft'); @endphp
        <select name="status" class="form-select" required>
            <option value="draft"      @selected($st==='draft')>مسودة</option>
            <option value="in_review"  @selected($st==='in_review')>قيد المراجعة</option>
            <option value="published"  @selected($st==='published')>منشور</option>
            <option value="archived"   @selected($st==='archived')>مؤرشف</option>
        </select>
        @error('status')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" id="mc_is_active" value="1"
                @checked(old('is_active', $row->is_active ?? 1))>
            <label class="form-check-label" for="mc_is_active">مفعّل</label>
        </div>
    </div>

</div>

{{-- JS بسيط لتبديل نوع الحقل + فلترة السلاسل المؤسسية بدون مسارات إضافية --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const $type = document.getElementById('mc_type');
    const $file = document.getElementById('mc_file_wrap');
    const $link = document.getElementById('mc_link_wrap');
    const $u = document.getElementById('mc_university');
    const $b = document.getElementById('mc_branch');
    const $c = document.getElementById('mc_college');
    const $m = document.getElementById('mc_major');

    function toggleType() {
        if ($type.value === 'file') { $file.style.display=''; $link.style.display='none'; }
        else { $file.style.display='none'; $link.style.display=''; }
    }

    function filterBranches() {
        const uid = $u.value;
        [...$b.options].forEach((opt, i) => {
            if (i===0) return;
            opt.hidden = uid && opt.dataset.university !== uid;
        });
        if ([...$b.options].some(o=>!o.hidden && o.selected) === false) $b.selectedIndex = 0;
    }
    function filterColleges() {
        const bid = $b.value;
        const uid = $u.value;
        [...$c.options].forEach((opt, i) => {
            if (i===0) return;
            opt.hidden = (uid && opt.dataset.university !== uid) || (bid && opt.dataset.branch !== bid);
        });
        if ([...$c.options].some(o=>!o.hidden && o.selected) === false) $c.selectedIndex = 0;
    }
    function filterMajors() {
        const cid = $c.value;
        [...$m.options].forEach((opt, i) => {
            if (i===0) return;
            opt.hidden = (cid && opt.dataset.college !== cid);
        });
        if ([...$m.options].some(o=>!o.hidden && o.selected) === false) $m.selectedIndex = 0;
    }

    $type?.addEventListener('change', toggleType);
    $u?.addEventListener('change', () => { filterBranches(); filterColleges(); filterMajors(); });
    $b?.addEventListener('change', () => { filterColleges(); filterMajors(); });
    $c?.addEventListener('change', () => { filterMajors(); });

    toggleType(); filterBranches(); filterColleges(); filterMajors();
});
</script>
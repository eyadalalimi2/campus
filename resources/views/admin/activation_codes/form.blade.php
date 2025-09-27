@php $isEdit = isset($code); @endphp
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">الدفعة (اختياري)</label>
        <select name="batch_id" id="batch_id" class="form-select">
            <option value="">— بدون دفعة —</option>
            @foreach (\App\Models\ActivationCodeBatch::orderBy('id', 'desc')->get(['id', 'name', 'code_length', 'code_prefix']) as $b)
                <option value="{{ $b->id }}" data-length="{{ $b->code_length }}" data-prefix="{{ $b->code_prefix }}"
                    @selected(old('batch_id', $code->batch_id ?? '') == $b->id)>{{ $b->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">الخطة</label>
        <select name="plan_id" class="form-select" required>
            @foreach (\App\Models\Plan::orderBy('name')->get() as $p)
                <option value="{{ $p->id }}" @selected(old('plan_id', $code->plan_id ?? '') == $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">الكود</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $code->code ?? '') }}"
            placeholder="اتركه فارغًا لتوليد تلقائي">
    </div>

    <div class="col-12">
        <hr><strong>النطاق (اختياري)</strong>
    </div>
    <div class="col-md-4">
        <label class="form-label">الجامعة</label>
        <select name="university_id" id="university_select" class="form-select">
            <option value="">— اختر —</option>
            @foreach (\App\Models\University::orderBy('name')->get() as $u)
                <option value="{{ $u->id }}" @selected(old('university_id', $code->university_id ?? '') == $u->id)>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">الكلية</label>
        <select name="college_id" id="college_select" class="form-select">
            <option value="">— اختر —</option>
            @foreach (\App\Models\College::orderBy('name')->get() as $c)
                <option value="{{ $c->id }}" data-university="{{ $c->university_id }}"
                    @selected(old('college_id', $code->college_id ?? '') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">التخصص</label>
        <select name="major_id" id="major_select" class="form-select">
            <option value="">— اختر —</option>
            @foreach (\App\Models\Major::with('college')->orderBy('name')->get() as $m)
                <option value="{{ $m->id }}" data-college="{{ $m->college_id }}" @selected(old('major_id', $code->major_id ?? '') == $m->id)>
                    {{ $m->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <hr><strong>السياسة والصلاحية</strong>
    </div>
    <div class="col-md-3">
        <label class="form-label">المدة (يوم)</label>
        <input type="number" name="duration_days" class="form-control" min="1" max="1825"
            value="{{ old('duration_days', $code->duration_days ?? 365) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">سياسة البداية</label>
        @php $sp = old('start_policy',$code->start_policy ?? 'on_redeem'); @endphp
        <select name="start_policy" id="start_policy" class="form-select" required>
            <option value="on_redeem" @selected($sp === 'on_redeem')>تبدأ عند التفعيل</option>
            <option value="fixed_start" @selected($sp === 'fixed_start')>تاريخ ثابت</option>
        </select>
    </div>
    <div class="col-md-3 start-on">
        <label class="form-label">تبدأ في</label>
        <input type="text" name="starts_on" id="starts_on" class="form-control js-date"
            value="{{ old('starts_on', optional($code->starts_on ?? null)->format('Y-m-d')) }}">

        <div class="col-md-3">
            <label class="form-label">صالح من</label>
            <input type="datetime-local" name="valid_from" class="form-control"
                value="{{ old('valid_from', optional($code->valid_from ?? null)->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">صالح حتى</label>
            <input type="datetime-local" name="valid_until" class="form-control"
                value="{{ old('valid_until', optional($code->valid_until ?? null)->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">حد الاستخدام</label>
            <input type="number" name="max_redemptions" class="form-control" min="1" max="1000"
                value="{{ old('max_redemptions', $code->max_redemptions ?? 1) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            @php $st = old('status',$code->status ?? 'active'); @endphp
            <select name="status" class="form-select" required>
                @foreach (['active', 'redeemed', 'expired', 'disabled'] as $x)
                    <option value="{{ $x }}" @selected($st === $x)>{{ $x }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">ملاحظات</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $code->notes ?? '') }}</textarea>
        </div>
    </div>

    @push('scripts')
        <script>
            function cascade() {
                const uni = document.getElementById('university_select')?.value || '';
                const col = document.getElementById('college_select');
                const maj = document.getElementById('major_select');

                if (col) {
                    [...col.options].forEach(o => {
                        if (!o.value) return;
                        const show = !uni || (o.dataset.university === uni);
                        o.hidden = !show;
                        if (!show && o.selected) o.selected = false;
                    });
                }
                const colVal = col?.value || '';
                if (maj) {
                    [...maj.options].forEach(o => {
                        if (!o.value) return;
                        const show = !colVal || (o.dataset.college === colVal);
                        o.hidden = !show;
                        if (!show && o.selected) o.selected = false;
                    });
                }
            }

            function toggleStart() {
                const sp = document.getElementById('start_policy').value;
                document.querySelectorAll('.start-on').forEach(el => el.style.display = (sp === 'fixed_start' ? '' : 'none'));
            }
            document.getElementById('university_select').addEventListener('change', cascade);
            document.getElementById('college_select').addEventListener('change', cascade);
            document.getElementById('start_policy').addEventListener('change', toggleStart);
            cascade();
            toggleStart();
        </script>
    @endpush

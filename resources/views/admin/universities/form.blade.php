<div class="row g-3">
    {{-- الاسم --}}
    <div class="col-md-6">
        <label class="form-label">الاسم</label>
        <input type="text" name="name" class="form-control" required
            value="{{ old('name', $university->name ?? '') }}" placeholder="مثال: جامعة صنعاء">
    </div>

    {{-- العنوان --}}
    <div class="col-md-6">
        <label class="form-label">العنوان</label>
        <input type="text" name="address" class="form-control" required
            value="{{ old('address', $university->address ?? '') }}" placeholder="المدينة، الشارع، المبنى">
    </div>

    {{-- رقم الهاتف --}}
    <div class="col-md-6">
        <label class="form-label">رقم الهاتف</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $university->phone ?? '') }}"
            placeholder="07XXXXXXXX">
    </div>

    {{-- الشعار --}}
    <div class="col-md-6">
        <label class="form-label">الشعار (PNG/JPG)</label>
        <input type="file" name="logo" class="form-control" accept=".png,.jpg,.jpeg,.webp">
        @php
            $logoSrc = null;
            if (!empty($university?->logo_url)) {
                $logoSrc = $university->logo_url; // رابط مطلق إن كان موجوداً
            } elseif (!empty($university?->logo)) {
                $logoSrc = \Illuminate\Support\Facades\Storage::url($university->logo); // مسار داخل storage
            }
        @endphp
        @if ($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo" class="mt-2 rounded border"
                style="height:48px;object-fit:contain">
        @endif
    </div>
    <div class="col-md-3 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                {{ old('is_active', $university->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">مفعل</label>
        </div>
    </div>

</div>

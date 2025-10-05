<form action="{{ $action }}" method="post" enctype="multipart/form-data" class="card card-body">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <div>فضلاً صحّح الأخطاء التالية:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">الاسم *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $assistant->name ?? '') }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">الصورة (اختياري)</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
            @if(!empty($assistant?->photo_url))
                <div class="mt-2">
                    <img src="{{ $assistant->photo_url }}" alt="" style="height:64px;width:64px;object-fit:cover;border-radius:8px">
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <label class="form-label">نص الجامعة</label>
            <input type="text" name="university_text" class="form-control" value="{{ old('university_text', $assistant->university_text ?? '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">نص الكلية</label>
            <input type="text" name="college_text" class="form-control" value="{{ old('college_text', $assistant->college_text ?? '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">نص التخصص</label>
            <input type="text" name="major_text" class="form-control" value="{{ old('major_text', $assistant->major_text ?? '') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $assistant->sort_order ?? 0) }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select name="is_active" class="form-select">
                <option value="1" @selected(old('is_active', $assistant->is_active ?? 1)==1)>مفعل</option>
                <option value="0" @selected(old('is_active', $assistant->is_active ?? 1)==0)>مخفي</option>
            </select>
        </div>

        <div class="col-12 d-flex gap-2 mt-3">
            <button class="btn btn-primary">حفظ</button>
            <a href="{{ route('admin.content_assistants.index') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </div>
</form>

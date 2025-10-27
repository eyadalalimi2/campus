@php
    // Avoid collision with the global $app container available in views.
    // Use $appModel to refer to the AndroidApp model if provided.
    $appModel = (isset($app) && $app instanceof \App\Models\AndroidApp) ? $app : null;
    $isEdit = (bool) $appModel;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">الاسم</label>
    <input name="name" class="form-control" value="{{ old('name', $isEdit ? $appModel->name : '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">الـ slug</label>
    <input name="slug" class="form-control" value="{{ old('slug', $isEdit ? $appModel->slug : '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">أيقونة (512×512)</label>
        <input type="file" name="icon" class="form-control">
        @if(!empty($appModel?->icon_path))
            <img src="{{ Storage::url($appModel->icon_path) }}" alt="" style="height:64px; margin-top:.5rem">
        @endif
    </div>
    <div class="col-md-8">
        <label class="form-label">صورة الغلاف (Feature Image)</label>
        <input type="file" name="feature_image" class="form-control">
        @if(!empty($appModel?->feature_image_path))
            <img src="{{ Storage::url($appModel->feature_image_path) }}" alt="" style="height:64px; margin-top:.5rem">
        @endif
    </div>

    <div class="col-12">
        <label class="form-label">الوصف القصير</label>
    <input name="short_description" class="form-control" maxlength="200" value="{{ old('short_description', $isEdit ? $appModel->short_description : '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">الوصف الطويل</label>
    <textarea name="long_description" rows="6" class="form-control">{{ old('long_description', $isEdit ? $appModel->long_description : '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">سجل التغييرات (changelog)</label>
    <textarea name="changelog" rows="3" class="form-control">{{ old('changelog', $isEdit ? $appModel->changelog : '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">ملف APK (زر تحميل)</label>
        <input type="file" name="apk_file" class="form-control">
        @if(!empty($appModel?->apk_file_path))
            <div class="mt-2"><a href="{{ route('apps.download', $appModel->slug) }}" class="btn btn-sm btn-outline-success">تحميل الملف الحالي</a></div>
        @endif
    </div>

    <div class="col-md-4">
        <label class="form-label">version_name</label>
    <input name="version_name" class="form-control" value="{{ old('version_name', $isEdit ? $appModel->version_name : '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">version_code</label>
    <input name="version_code" class="form-control" value="{{ old('version_code', $isEdit ? $appModel->version_code : '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">apk_size (مثال: 25.4 MB)</label>
    <input name="apk_size" class="form-control" value="{{ old('apk_size', $isEdit ? $appModel->apk_size : '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">min_sdk</label>
    <input name="min_sdk" class="form-control" value="{{ old('min_sdk', $isEdit ? $appModel->min_sdk : '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">target_sdk</label>
    <input name="target_sdk" class="form-control" value="{{ old('target_sdk', $isEdit ? $appModel->target_sdk : '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">رابط الفيديو (YouTube/Vimeo أو ملف MP4)</label>
    <input name="video_url" class="form-control" value="{{ old('video_url', $isEdit ? $appModel->video_url : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">صورة غلاف الفيديو</label>
        <input type="file" name="video_cover_image" class="form-control">
        @if(!empty($appModel?->video_cover_image))
            <img src="{{ Storage::url($appModel->video_cover_image) }}" alt="" style="height:64px; margin-top:.5rem">
        @endif
    </div>

    <div class="col-12">
        <label class="form-label">لقطات الشاشة (اختر عدة ملفات)</label>
        <input type="file" name="screenshots[]" class="form-control" multiple>
        @if(!empty($appModel?->screenshots))
            <div class="mt-2 d-flex gap-2 flex-wrap">
                @foreach($appModel->screenshots as $s)
                    <img src="{{ Storage::url($s) }}" alt="" style="height:80px; border-radius:.5rem">
                @endforeach
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">سياسة الخصوصية (رابط)</label>
    <input name="privacy_policy_url" class="form-control" value="{{ old('privacy_policy_url', $isEdit ? $appModel->privacy_policy_url : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">بريد الدعم</label>
    <input name="support_email" class="form-control" value="{{ old('support_email', $isEdit ? $appModel->support_email : '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">الموقع الرسمي</label>
    <input name="website_url" class="form-control" value="{{ old('website_url', $isEdit ? $appModel->website_url : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">الفئة (category)</label>
    <input name="category" class="form-control" value="{{ old('category', $isEdit ? $appModel->category : '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">اسم المطوّر</label>
    <input name="developer_name" class="form-control" value="{{ old('developer_name', $isEdit ? $appModel->developer_name : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">شعار المطوّر</label>
        <input type="file" name="developer_logo" class="form-control">
        @if(!empty($appModel?->developer_logo))
            <img src="{{ Storage::url($appModel->developer_logo) }}" alt="" style="height:48px; margin-top:.5rem">
        @endif
    </div>

    <div class="col-12">
        <label class="form-label">وسوم (افصل بفواصل)</label>
    <input name="tags_text" class="form-control" value="{{ old('tags_text', ($appModel && is_array($appModel->tags)) ? implode(',', $appModel->tags) : '') }}">
    </div>
</div>

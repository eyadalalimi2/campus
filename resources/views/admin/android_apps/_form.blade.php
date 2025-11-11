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
                    <div class="position-relative screenshot-item" data-path="{{ $s }}" style="display:inline-block">
                        <img src="{{ Storage::url($s) }}" alt="" style="height:80px; border-radius:.5rem; display:block">
                        <button type="button" class="btn btn-sm btn-danger screenshot-delete" title="حذف" aria-label="حذف اللقطة" style="position:absolute; top:-8px; right:-8px; border-radius:999px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; padding:0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <script>
                (function() {
                    // Attach a single delegated handler for delete buttons within this section
                    document.addEventListener('click', function(e) {
                        var target = e.target;
                        // If SVG inner element clicked, bubble up to the button
                        if (target && !target.classList.contains('screenshot-delete')) {
                            var btn = target.closest && target.closest('.screenshot-delete');
                            if (btn) target = btn; else return;
                        }
                        if (target && target.classList.contains('screenshot-delete')) {
                            e.preventDefault();
                            var item = target.closest('.screenshot-item');
                            if (!item) return;
                            var path = item.getAttribute('data-path');
                            if (!path) return;

                            var confirmDelete = window.confirm('هل تريد حذف هذه اللقطة؟');
                            if (!confirmDelete) return;

                            // Find the closest form to append a hidden input
                            var form = item.closest('form') || document.querySelector('form');
                            if (!form) return;

                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'delete_screenshots[]';
                            input.value = path;
                            form.appendChild(input);

                            // Remove the preview from UI
                            item.parentNode && item.parentNode.removeChild(item);
                        }
                    }, { passive: false });
                })();
            </script>
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

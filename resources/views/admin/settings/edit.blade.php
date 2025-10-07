
@extends('admin.layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border rounded-4 mx-auto" style="max-width: 100%;">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">إعدادات الموقع ولوحة التحكم</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">شعار لوحة التحكم</label>
                                <input type="file" name="dashboard_logo" class="form-control">
                                <small class="text-muted d-block mb-1">يفضل أن يكون الشعار بمقاس 200×50 بكسل بصيغة PNG أو JPG.</small>
                                @if($setting && $setting->dashboard_logo)
                                    <img src="{{ asset('storage/' . $setting->dashboard_logo) }}" width="200" height="50" class="mt-2 rounded shadow-sm border">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الأيقونة المصغرة (Favicon)</label>
                                <input type="file" name="dashboard_favicon" class="form-control">
                                <small class="text-muted d-block mb-1">يفضل أن تكون الأيقونة بمقاس 32×32 بكسل بصيغة PNG أو ICO.</small>
                                @if($setting && $setting->dashboard_favicon)
                                    <img src="{{ asset('storage/' . $setting->dashboard_favicon) }}" width="32" height="32" class="mt-2 rounded border">
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">شعار صفحة تسجيل دخول الأدمن</label>
                            <input type="file" name="admin_login_logo" class="form-control">
                            @if($setting && $setting->admin_login_logo)
                                <img src="{{ asset('storage/' . $setting->admin_login_logo) }}" height="50" class="mt-2 rounded shadow-sm">
                            @endif
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">عنوان الموقع</label>
                                <input type="text" name="site_title" class="form-control" value="{{ $setting->site_title ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الوصف القصير</label>
                                <input type="text" name="site_short_description" class="form-control" value="{{ $setting->site_short_description ?? '' }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف الطويل</label>
                            <textarea name="site_long_description" class="form-control" rows="3">{{ $setting->site_long_description ?? '' }}</textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">كلمات SEO</label>
                                <input type="text" name="seo_keywords" class="form-control" value="{{ $setting->seo_keywords ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">وصف SEO</label>
                                <textarea name="seo_description" class="form-control" rows="2">{{ $setting->seo_description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني للتواصل</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ $setting->contact_email ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم التواصل</label>
                                <input type="text" name="contact_phone" class="form-control" value="{{ $setting->contact_phone ?? '' }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">رابط فيسبوك</label>
                                <input type="text" name="facebook_url" class="form-control" value="{{ $setting->facebook_url ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">رابط تويتر</label>
                                <input type="text" name="twitter_url" class="form-control" value="{{ $setting->twitter_url ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">رابط انستجرام</label>
                                <input type="text" name="instagram_url" class="form-control" value="{{ $setting->instagram_url ?? '' }}">
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">حفظ الإعدادات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')
@section('content')
<div class="container">
    <h2>إعدادات الموقع ولوحة التحكم</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="mb-3">
            <label>شعار لوحة التحكم</label>
            <input type="file" name="dashboard_logo" class="form-control">
            @if($setting && $setting->dashboard_logo)
                <img src="{{ asset('storage/' . $setting->dashboard_logo) }}" height="50">
            @endif
        </div>
        <div class="mb-3">
            <label>الأيقونة المصغرة (Favicon)</label>
            <input type="file" name="dashboard_favicon" class="form-control">
            @if($setting && $setting->dashboard_favicon)
                <img src="{{ asset('storage/' . $setting->dashboard_favicon) }}" height="32">
            @endif
        </div>
        <div class="mb-3">
            <label>شعار صفحة تسجيل دخول الأدمن</label>
            <input type="file" name="admin_login_logo" class="form-control">
            @if($setting && $setting->admin_login_logo)
                <img src="{{ asset('storage/' . $setting->admin_login_logo) }}" height="50">
            @endif
        </div>
        <div class="mb-3">
            <label>عنوان الموقع</label>
            <input type="text" name="site_title" class="form-control" value="{{ $setting->site_title ?? '' }}">
        </div>
        <div class="mb-3">
            <label>الوصف القصير</label>
            <input type="text" name="site_short_description" class="form-control" value="{{ $setting->site_short_description ?? '' }}">
        </div>
        <div class="mb-3">
            <label>الوصف الطويل</label>
            <textarea name="site_long_description" class="form-control">{{ $setting->site_long_description ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label>كلمات SEO</label>
            <input type="text" name="seo_keywords" class="form-control" value="{{ $setting->seo_keywords ?? '' }}">
        </div>
        <div class="mb-3">
            <label>وصف SEO</label>
            <textarea name="seo_description" class="form-control">{{ $setting->seo_description ?? '' }}</textarea>
        </div>
        <div class="mb-3">
            <label>البريد الإلكتروني للتواصل</label>
            <input type="email" name="contact_email" class="form-control" value="{{ $setting->contact_email ?? '' }}">
        </div>
        <div class="mb-3">
            <label>رقم التواصل</label>
            <input type="text" name="contact_phone" class="form-control" value="{{ $setting->contact_phone ?? '' }}">
        </div>
        <div class="mb-3">
            <label>رابط فيسبوك</label>
            <input type="text" name="facebook_url" class="form-control" value="{{ $setting->facebook_url ?? '' }}">
        </div>
        <div class="mb-3">
            <label>رابط تويتر</label>
            <input type="text" name="twitter_url" class="form-control" value="{{ $setting->twitter_url ?? '' }}">
        </div>
        <div class="mb-3">
            <label>رابط انستجرام</label>
            <input type="text" name="instagram_url" class="form-control" value="{{ $setting->instagram_url ?? '' }}">
        </div>
        <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
    </form>
</div>
@endsection

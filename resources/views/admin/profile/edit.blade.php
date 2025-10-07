@extends('admin.layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 p-0">
            <div class="card shadow-sm border rounded-4 w-100" style="min-height: 80vh;">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">تعديل الملف الشخصي</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" class="form-control" value="{{ auth('admin')->user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ auth('admin')->user()->email }}" required>
                        </div>
                        <hr>
                        <h5 class="mb-3">تغيير كلمة المرور</h5>
                        <div class="mb-3">
                            <label class="form-label">كلمة المرور الحالية</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">حفظ التعديلات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

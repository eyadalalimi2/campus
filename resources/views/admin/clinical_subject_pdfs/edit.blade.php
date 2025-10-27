@extends('admin.layouts.app')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 m-0">تعديل ملف PDF للمواد السريرية</h1>
        <a href="{{ route('admin.clinical_subject_pdfs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
            رجوع للقائمة
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>حدثت أخطاء أثناء الإرسال:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong>تحديث بيانات الملف</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.clinical_subject_pdfs.update', $clinicalSubjectPdf) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم الملف <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $clinicalSubjectPdf->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="clinical_subject_id" class="form-label">المادة السريرية <span class="text-danger">*</span></label>
                        <select id="clinical_subject_id" name="clinical_subject_id" class="form-select @error('clinical_subject_id') is-invalid @enderror" required>
                            @foreach($clinicalSubjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('clinical_subject_id', $clinicalSubjectPdf->clinical_subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('clinical_subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="content" class="form-label">المحتوى/الوصف</label>
                        <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" rows="4">{{ old('content', $clinicalSubjectPdf->content) }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="file" class="form-label">ملف PDF</label>
                        @if($clinicalSubjectPdf->file)
                            <div class="mb-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ asset('storage/' . $clinicalSubjectPdf->file) }}" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    عرض الملف الحالي
                                </a>
                            </div>
                        @endif
                        <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror" accept="application/pdf">
                        <div class="form-text">اترك الحقل فارغاً إذا كنت لا ترغب في تغييره.</div>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="order" class="form-label">ترتيب العرض <span class="text-danger">*</span></label>
                        <input type="number" id="order" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $clinicalSubjectPdf->order) }}" min="0" step="1" required>
                        @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        تحديث
                    </button>
                    <a href="{{ route('admin.clinical_subject_pdfs.index') }}" class="btn btn-light border">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection

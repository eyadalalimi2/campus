@extends('admin.layouts.app')
@section('title','تعديل مادة')

@section('content')
<h4 class="mb-3">تعديل مادة: {{ $material->name }}</h4>

{{-- أخطاء التحقق --}}
@if ($errors->any())
  <div class="alert alert-danger">
    <div class="fw-bold mb-1">حدثت أخطاء أثناء الحفظ:</div>
    <ul class="mb-0">
      @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('admin.materials.update',$material) }}" method="POST" class="card p-3">
  @csrf @method('PUT')

  @include('admin.materials.form', [
      'material' => $material,
      // ✅ مرّر الفصول من الكنترولر
      'terms' => $terms ?? null,
      // ✅ IDs الفصول المرتبطة بالمادة لتحديدها في الـ multi-select
      'selectedTermIds' => old('term_ids',
          isset($material) ? ($material->terms()->pluck('academic_terms.id')->toArray()) : []
      )
  ])

  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.materials.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection

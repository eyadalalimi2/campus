
@extends('admin.layouts.app')
@section('title','تعديل جامعة')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-7 col-lg-6">
		<div class="card">
			<div class="card-header"><i class="bi bi-building"></i> تعديل جامعة</div>
			<div class="card-body">
				<form method="post" action="{{ route('medical.universities.update',$university) }}">
					@csrf @method('PUT')
					<div class="mb-3">
						<label class="form-label">الاسم</label>
						<input name="name" class="form-control" value="{{ $university->name }}" required>
					</div>
					<div class="mb-3">
						<label class="form-label">الكود</label>
						<input name="code" class="form-control" value="{{ $university->code }}" required maxlength="50">
					</div>
					<div class="mb-3">
						<label class="form-label">الدولة</label>
						<input name="country" class="form-control" value="{{ $university->country }}" required maxlength="2">
					</div>
					<div class="form-check mb-3">
						<input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ $university->is_active?'checked':'' }}>
						<label class="form-check-label" for="is_active">فعال</label>
					</div>
					<button class="btn btn-success"><i class="bi bi-save"></i> تحديث</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

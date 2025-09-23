
@extends('admin.layouts.app')
@section('title','تعديل جهاز')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-7 col-lg-6">
		<div class="card">
			<div class="card-header"><i class="bi bi-cpu"></i> تعديل جهاز</div>
			<div class="card-body">
				<form method="post" action="{{ route('medical.systems.update',$system) }}">
					@csrf @method('PUT')
					
					<div class="mb-3">
						<label class="form-label">الاسم (عربي)</label>
						<input name="name_ar" class="form-control" value="{{ $system->name_ar }}" required>
					</div>
					<div class="mb-3">
						<label class="form-label">الاسم (إنجليزي)</label>
						<input name="name_en" class="form-control" value="{{ $system->name_en }}">
					</div>
					<div class="mb-3">
						<label class="form-label">أيقونة</label>
						<input name="icon_url" class="form-control" value="{{ $system->icon_url }}">
					</div>
					<div class="mb-3">
						<label class="form-label">ترتيب</label>
						<input type="number" name="display_order" class="form-control" value="{{ $system->display_order }}">
					</div>
					<div class="form-check mb-3">
						<input type="hidden" name="is_active" value="0">
						<input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ $system->is_active?'checked':'' }}>
						<label class="form-check-label" for="is_active">فعال</label>
					</div>
					<button class="btn btn-success"><i class="bi bi-save"></i> تحديث</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

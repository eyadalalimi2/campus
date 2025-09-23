
@extends('admin.layouts.app')
@section('title','تعديل دكتور')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-7 col-lg-6">
		<div class="card">
			<div class="card-header"><i class="bi bi-person-badge"></i> تعديل دكتور</div>
			<div class="card-body">
				<form method="post" action="{{ route('medical.doctors.update',$doctor) }}">
					@csrf @method('PUT')
					<div class="mb-3">
						<label class="form-label">الاسم</label>
						<input name="name" class="form-control" value="{{ $doctor->name }}" required>
					</div>
					<div class="mb-3">
						<label class="form-label">رابط القناة</label>
						<input name="channel_url" type="url" class="form-control" value="{{ $doctor->channel_url }}" required>
					</div>
					<div class="mb-3">
						<label class="form-label">الدولة</label>
						<input name="country" maxlength="2" class="form-control" value="{{ $doctor->country }}">
					</div>
								<div class="mb-3 form-check">
									<input type="hidden" name="verified" value="0">
									<input type="checkbox" name="verified" class="form-check-input" id="verifiedCheck" value="1" {{ $doctor->verified?'checked':'' }}>
									<label class="form-check-label" for="verifiedCheck">معتمد</label>
								</div>
					<div class="mb-3">
						<label class="form-label">Score</label>
						<input name="score" type="number" step="0.01" min="0" max="99.99" class="form-control" value="{{ $doctor->score }}">
					</div>
					<button class="btn btn-success"><i class="bi bi-save"></i> تحديث</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

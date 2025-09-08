{{-- رسائل فلاش + أخطاء التحقق --}}
@if(session('success') || session('warning') || session('error') || $errors->any())
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
  @if(session('success'))
    <div class="toast align-items-center text-bg-success border-0 show mb-2">
      <div class="d-flex">
        <div class="toast-body">{{ session('success') }}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  @endif

  @if(session('warning'))
    <div class="toast align-items-center text-bg-warning border-0 show mb-2">
      <div class="d-flex">
        <div class="toast-body">{{ session('warning') }}</div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="toast align-items-center text-bg-danger border-0 show mb-2">
      <div class="d-flex">
        <div class="toast-body">{{ session('error') }}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  @endif

  @if($errors->any())
    <div class="toast align-items-center text-bg-danger border-0 show mb-2">
      <div class="d-flex">
        <div class="toast-body">
          @foreach($errors->all() as $e)
            <div>• {{ $e }}</div>
          @endforeach
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  @endif
</div>
@endif

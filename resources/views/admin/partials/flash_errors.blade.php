@if(isset($errors) && $errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="d-flex">
      <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
      <div>
        <strong>حدثت أخطاء:</strong>
        <ul class="mb-0 mt-1">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

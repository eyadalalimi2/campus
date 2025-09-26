<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الاسم *</label>
    <input type="text" name="name" class="form-control" value="{{ old('name',$category->name ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">الكود *</label>
    <input type="text" name="code" class="form-control" value="{{ old('code',$category->code ?? '') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">الترتيب</label>
    <input type="number" name="order_index" class="form-control" value="{{ old('order_index',$category->order_index ?? 0) }}">
  </div>
  <div class="col-md-3 d-flex align-items-center">
    <div class="form-check mt-4">
      <input class="form-check-input" type="checkbox" name="active" value="1" id="activeCheck"
             @checked(old('active', $category->active ?? true))>
      <label class="form-check-label" for="activeCheck">فعال</label>
    </div>
  </div>
</div>

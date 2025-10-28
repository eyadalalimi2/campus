<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الاسم *</label>
    <input type="text" name="name" class="form-control" value="{{ old('name',$subject->name ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">الصورة</label>
    <input type="file" name="image" class="form-control">
    @if(!empty($subject?->image_path))
      <img src="{{ asset('storage/'.$subject->image_path) }}" class="img-thumbnail mt-2" style="height:64px">
    @endif
  </div>

  <div class="col-md-4">
    <label class="form-label">النطاق *</label>
    <select name="scope" class="form-select">
      @foreach(['basic'=>'Basic','clinical'=>'Clinical','both'=>'Both'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('scope',$subject->scope ?? 'basic')==$k)>{{ $k }}</option>
      @endforeach
    </select>
  </div>
  {{--
  <div class="col-md-4">
    <label class="form-label">المستوى الأكاديمي</label>
    <input type="text" name="academic_level" class="form-control" value="{{ old('academic_level',$subject->academic_level ?? '') }}">
  </div>
  --}}
  <div class="col-md-2">
    <label class="form-label">الترتيب</label>
    <input type="number" name="order_index" class="form-control" value="{{ old('order_index',$subject->order_index ?? 0) }}">
  </div>
  <div class="col-md-2">
    <label class="form-label">الحالة *</label>
    <select name="status" class="form-select">
      @foreach(['draft'=>'موقوف','published'=>'مفعل'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$subject->status ?? 'published')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
  <!-- تم إزالة قسم ربط الأجهزة — التعديل الآن يتم من صفحة الأجهزة فقط -->
</div>

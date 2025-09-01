<?php
  $isEdit = isset($doctor) && $doctor;
?>

<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label">الاسم</label>
    <input type="text" name="name" class="form-control" required value="<?php echo e(old('name', $doctor->name ?? '')); ?>">
  </div>

  <div class="col-md-3">
    <label class="form-label">النوع</label>
    <select name="type" id="type_select" class="form-select" onchange="toggleType()" required>
      <option value="university" <?php if(old('type', $doctor->type ?? 'university')==='university'): echo 'selected'; endif; ?>>جامعي</option>
      <option value="independent" <?php if(old('type', $doctor->type ?? '')==='independent'): echo 'selected'; endif; ?>>مستقل/مشهور</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">الهاتف</label>
    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $doctor->phone ?? '')); ?>">
  </div>

  <div class="col-md-4">
    <label class="form-label">المؤهل الدراسي</label>
    <input type="text" name="degree" class="form-control" value="<?php echo e(old('degree', $doctor->degree ?? '')); ?>" placeholder="دكتوراه، ماجستير...">
  </div>

  <div class="col-md-2">
    <label class="form-label">سنة المؤهل</label>
    <input type="number" name="degree_year" class="form-control" value="<?php echo e(old('degree_year', $doctor->degree_year ?? '')); ?>" min="1900" max="<?php echo e(date('Y')); ?>">
  </div>

  <div class="col-md-6">
    <label class="form-label">الصورة (اختياري)</label>
    <input type="file" name="photo" class="form-control">
    <?php if(!empty($doctor?->photo_url)): ?>
      <img src="<?php echo e($doctor->photo_url); ?>" class="mt-2" style="height:60px;border-radius:8px">
    <?php endif; ?>
  </div>

  <!-- قسم الجامعي -->
  <div class="col-12"><hr><strong>بيانات الجامعات (للنوع: جامعي)</strong></div>

  <div class="col-md-4 type-university">
    <label class="form-label">الجامعة</label>
    <select name="university_id" id="university_id" class="form-select">
      <option value="">— اختر —</option>
      <?php $__currentLoopData = \App\Models\University::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($u->id); ?>" <?php if(old('university_id', $doctor->university_id ?? '')==$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-md-4 type-university">
    <label class="form-label">الكلية</label>
    <select name="college_id" id="college_id" class="form-select">
      <option value="">— اختر —</option>
      <?php $__currentLoopData = \App\Models\College::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($c->id); ?>" <?php if(old('college_id', $doctor->college_id ?? '')==$c->id): echo 'selected'; endif; ?> data-university="<?php echo e($c->university_id); ?>">
          <?php echo e($c->name); ?> (<?php echo e($c->university->name); ?>)
        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="col-md-4 type-university">
    <label class="form-label">التخصص</label>
    <select name="major_id" id="major_id" class="form-select">
      <option value="">— اختر —</option>
      <?php $__currentLoopData = \App\Models\Major::with('college')->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m->id); ?>" <?php if(old('major_id', $doctor->major_id ?? '')==$m->id): echo 'selected'; endif; ?> data-college="<?php echo e($m->college_id); ?>">
          <?php echo e($m->name); ?> (<?php echo e($m->college->name); ?>)
        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <!-- قسم المستقل -->
  <div class="col-12"><hr><strong>تخصّصات الدكتور المستقل (يمكن اختيار عدة تخصّصات)</strong></div>
  <div class="col-md-12 type-independent">
    <label class="form-label">التخصصات</label>
    <select name="major_ids[]" id="independent_majors" class="form-select" multiple size="6">
      <?php $__currentLoopData = \App\Models\Major::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m->id); ?>" <?php if(in_array($m->id, old('major_ids', $selectedMajors ?? []))): echo 'selected'; endif; ?>>
          <?php echo e($m->name); ?> (<?php echo e($m->college->name); ?>)
        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <div class="form-text">اضغط Ctrl/⌘ لاختيار أكثر من تخصّص.</div>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        <?php echo e(old('is_active', $doctor->is_active ?? true) ? 'checked':''); ?>>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleType(){
  const type = document.getElementById('type_select').value;
  document.querySelectorAll('.type-university').forEach(el => el.style.display = (type==='university' ? '' : 'none'));
  document.querySelectorAll('.type-independent').forEach(el => el.style.display = (type==='independent' ? '' : 'none'));
}

function filterCollegesByUniversity(){
  const uniId = document.getElementById('university_id').value;
  document.querySelectorAll('#college_id option[data-university]').forEach(o=>{
    o.hidden = (uniId && o.dataset.university !== uniId);
  });
}

function filterMajorsByCollege(){
  const colId = document.getElementById('college_id').value;
  document.querySelectorAll('#major_id option[data-college]').forEach(o=>{
    o.hidden = (colId && o.dataset.college !== colId);
  });
}

document.getElementById('type_select').addEventListener('change', toggleType);
document.getElementById('university_id').addEventListener('change', filterCollegesByUniversity);
document.getElementById('college_id').addEventListener('change', filterMajorsByCollege);

// تفعيل الحالة عند التحميل
toggleType();
filterCollegesByUniversity();
filterMajorsByCollege();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/doctors/form.blade.php ENDPATH**/ ?>
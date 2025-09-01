<div class="row g-3">
    
    <div class="col-md-6">
        <label class="form-label">الاسم</label>
        <input type="text" name="name" class="form-control" required
            value="<?php echo e(old('name', $university->name ?? '')); ?>" placeholder="مثال: جامعة صنعاء">
    </div>

    
    <div class="col-md-6">
        <label class="form-label">العنوان</label>
        <input type="text" name="address" class="form-control" required
            value="<?php echo e(old('address', $university->address ?? '')); ?>" placeholder="المدينة، الشارع، المبنى">
    </div>

    
    <div class="col-md-6">
        <label class="form-label">رقم الهاتف</label>
        <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $university->phone ?? '')); ?>"
            placeholder="07XXXXXXXX">
    </div>

    
    <div class="col-md-6">
        <label class="form-label">الشعار (PNG/JPG)</label>
        <input type="file" name="logo" class="form-control" accept=".png,.jpg,.jpeg,.webp">
        <?php
            $logoSrc = null;
            if (!empty($university?->logo_url)) {
                $logoSrc = $university->logo_url; // رابط مطلق إن كان موجوداً
            } elseif (!empty($university?->logo)) {
                $logoSrc = \Illuminate\Support\Facades\Storage::url($university->logo); // مسار داخل storage
            }
        ?>
        <?php if($logoSrc): ?>
            <img src="<?php echo e($logoSrc); ?>" alt="Logo" class="mt-2 rounded border"
                style="height:48px;object-fit:contain">
        <?php endif; ?>
    </div>
    <div class="col-md-3 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                <?php echo e(old('is_active', $university->is_active ?? true) ? 'checked' : ''); ?>>
            <label class="form-check-label" for="is_active">مفعل</label>
        </div>
    </div>

</div>
<?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/universities/form.blade.php ENDPATH**/ ?>
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialTerm extends Pivot
{
    /** جدول الـ Pivot */
    protected $table = 'material_term';

    /** الجدول يحتوي على عمود id Auto Increment */
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * الجدول يحتوي على created_at فقط (بدون updated_at).
     */
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'material_id',
        'term_id',
    ];

    /*=============================
     | علاقات
     |=============================*/
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }

    /*=============================
     | Scopes
     |=============================*/
    public function scopeForMaterial($q, int $materialId)
    {
        return $q->where('material_id', $materialId);
    }

    public function scopeForTerm($q, int $termId)
    {
        return $q->where('term_id', $termId);
    }

    /*=============================
     | Helpers
     |=============================*/
    /**
     * يُنشئ الربط إذا لم يكن موجودًا (محمي بقيود UNIQUE uq_material_term).
     */
    public static function attachIfMissing(int $materialId, int $termId): self
    {
        return static::firstOrCreate([
            'material_id' => $materialId,
            'term_id'     => $termId,
        ]);
    }
}

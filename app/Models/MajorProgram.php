<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MajorProgram extends Pivot
{
    protected $table = 'major_program';

    // جدول pivot بدون created_at/updated_at
    public $timestamps = false;

    // لا يوجد id تلقائي – قيود مركّبة (major_id, program_id)
    public $incrementing = true;
    protected $primaryKey = 'id';
    protected $keyType = 'int';


    protected $fillable = [
        'major_id',
        'program_id',
    ];

    protected $casts = [
        'major_id'   => 'integer',
        'program_id' => 'integer',
    ];

    /*=============================
     | علاقات
     |=============================*/
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /*=============================
     | Scopes مساعدة
     |=============================*/
    public function scopeForMajor($q, int $majorId)
    {
        return $q->where('major_id', $majorId);
    }

    public function scopeForProgram($q, int $programId)
    {
        return $q->where('program_id', $programId);
    }

    /*=============================
     | Helpers
     |=============================*/
    /**
     * يربط التخصص ببرنامج إن لم يكن الربط موجودًا (اعتمد UNIQUE (major_id, program_id) في DB).
     */
    public static function attachIfMissing(int $majorId, int $programId): self
    {
        return static::firstOrCreate([
            'major_id'   => $majorId,
            'program_id' => $programId,
        ]);
    }
}

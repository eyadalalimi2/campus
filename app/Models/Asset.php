<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_id',
        'device_id',
        'doctor_id',
        'discipline_id',
        'program_id',
        'category',
        'title',
        'description',
        'status',
        'published_at',
        'published_by_admin_id',
        'video_url',
        'file_path',
        'external_url',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
        'deleted_at'   => 'datetime',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'published_by_admin_id');
    }

    /**
     * رابط الملف ضمن التخزين إذا كان نوع الأصل ملفاً.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    /**
     * استخراج معرف الفيديو من رابط يوتيوب إن وجد.
     */
    public function getVideoIdAttribute(): ?string
    {
        if ($this->category === 'youtube' && $this->video_url) {
            preg_match('/[\?\&]v=([^\?\&]+)/', $this->video_url, $matches);
            return $matches[1] ?? null;
        }
        return null;
    }
}

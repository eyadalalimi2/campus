<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'source_url',
        'file_path',
        'university_id',
        'college_id',
        'major_id',
        'material_id',
        'doctor_id',
        'is_active',
        'status',
        'published_at',
        'published_by_admin_id',
        'version',
        'changelog',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
        'version'      => 'integer',
        'deleted_at'   => 'datetime',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * الأجهزة المرتبطة بالمحتوى عبر pivot content_device.
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'content_device');
    }

    /**
     * الإداري الذي نشر المحتوى.
     */
    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'published_by_admin_id');
    }

    /**
     * رابط الملف ضمن التخزين.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    /**
     * نطاق للمحتويات المنشورة والنشطة.
     */
    public function scopePublished($query)
    {
        return $query->where('status','published')->where('is_active', true);
    }

    /**
     * نطاق لتصفية المحتوى حسب الجامعة.
     */
    public function scopeForUniversity($query, $universityId)
    {
        return $query->where('university_id', $universityId);
    }
}

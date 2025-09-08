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

    /** حالات النشر */
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_IN_REVIEW = 'in_review';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED  = 'archived';

    /** أنواع المحتوى */
    public const TYPE_FILE  = 'file';
    public const TYPE_VIDEO = 'video';
    public const TYPE_LINK  = 'link';

    protected $fillable = [
        'title',
        'description',
        'type',
        'source_url',
        'file_path',
        'university_id',          // إلزامي (خاصة بالمحتوى الخاص)
        'college_id',             // اختياري ضمن نفس الجامعة
        'major_id',               // اختياري ضمن نفس الكلية/الجامعة
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

    protected $appends = ['file_url', 'is_published'];

    /* ============================
     | علاقات
     |============================*/
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

    /** الأجهزة المرتبطة عبر pivot content_device (بدون timestamps) */
    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'content_device', 'content_id', 'device_id');
    }

    /** الإداري الذي نشر المحتوى */
    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'published_by_admin_id');
    }

    /* ============================
     | Accessors
     |============================*/
    /** رابط الملف المخزّن */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    /** هل هو منشور ونشط */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED && (bool) $this->is_active;
    }

    /* ============================
     | Scopes (فلترة جاهزة)
     |============================*/
    /** منشور + نشط */
    public function scopePublished($q)
    {
        return $q->where('status', self::STATUS_PUBLISHED)->where('is_active', true);
    }

    /** للجامعة المحددة (محتوى خاص) */
    public function scopeForUniversity($q, $universityId)
    {
        return $q->where('university_id', $universityId);
    }

    /** للكلية داخل الجامعة (عند التمرير) */
    public function scopeForCollege($q, $collegeId)
    {
        return $collegeId ? $q->where('college_id', $collegeId) : $q;
    }

    /** للتخصص داخل الكلية (عند التمرير) */
    public function scopeForMajor($q, $majorId)
    {
        return $majorId ? $q->where('major_id', $majorId) : $q;
    }

    /** حسب النوع */
    public function scopeType($q, ?string $type)
    {
        return $type ? $q->where('type', $type) : $q;
    }

    /** حسب الحالة */
    public function scopeStatus($q, ?string $status)
    {
        return $status ? $q->where('status', $status) : $q;
    }

    /** بحث نصي بسيط */
    public function scopeSearch($q, ?string $qstr)
    {
        if (!$qstr) return $q;
        return $q->where(function ($w) use ($qstr) {
            $w->where('title', 'like', '%'.$qstr.'%')
              ->orWhere('description', 'like', '%'.$qstr.'%');
        });
    }

    /** نطاق زمني عبر published_at */
    public function scopePublishedBetween($q, ?string $from, ?string $to)
    {
        if ($from) $q->where('published_at', '>=', $from);
        if ($to)   $q->where('published_at', '<=', $to);
        return $q;
    }

    /**
     * مطابقة جمهور الطالب: نفس الجامعة + (كلية NULL أو كلية الطالب) + (تخصص NULL أو تخصص الطالب).
     * مناسبة لتغذية التطبيق للطالب.
     */
    public function scopeMatchAudience($q, int $universityId, ?int $collegeId = null, ?int $majorId = null)
    {
        $q->where('university_id', $universityId);

        // college: إما NULL أو يساوي كلية الطالب
        if ($collegeId) {
            $q->where(function ($w) use ($collegeId) {
                $w->whereNull('college_id')->orWhere('college_id', $collegeId);
            });
        } else {
            $q->whereNull('college_id');
        }

        // major: إما NULL أو يساوي تخصص الطالب
        if ($majorId) {
            $q->where(function ($w) use ($majorId) {
                $w->whereNull('major_id')->orWhere('major_id', $majorId);
            });
        } else {
            $q->whereNull('major_id');
        }

        return $q;
    }

    /**
     * فلترة موحّدة لاستخدامها في الـ Controllers:
     * يدعم: q, status, type, university_id, college_id, major_id, material_id, doctor_id, is_active, from, to
     */
    public function scopeFilter($q, array $f = [])
    {
        return $q
            ->when(isset($f['q']) && $f['q'] !== '', fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['status']),        fn($qq) => $qq->status($f['status']))
            ->when(!empty($f['type']),          fn($qq) => $qq->type($f['type']))
            ->when(!empty($f['university_id']), fn($qq) => $qq->forUniversity($f['university_id']))
            ->when(!empty($f['college_id']),    fn($qq) => $qq->forCollege($f['college_id']))
            ->when(!empty($f['major_id']),      fn($qq) => $qq->forMajor($f['major_id']))
            ->when(!empty($f['material_id']),   fn($qq) => $qq->where('material_id', $f['material_id']))
            ->when(!empty($f['doctor_id']),     fn($qq) => $qq->where('doctor_id', $f['doctor_id']))
            ->when(isset($f['is_active']),       fn($qq) => $qq->where('is_active', (bool)$f['is_active']))
            ->when(!empty($f['from']) || !empty($f['to']), fn($qq) => $qq->publishedBetween($f['from'] ?? null, $f['to'] ?? null));
    }

    /** ترتيب افتراضي لتدفق الإدارة: الأحدث نشراً ثم آخر تحديث */
    public function scopeOrderForFeed($q)
    {
        return $q->orderByDesc('status')
                 ->orderByDesc('published_at')
                 ->orderByDesc('created_at');
    }

    /* ============================
     | عمليات مساعدة
     |============================*/
    /** نشر المحتوى وتعيين الحقول المرتبطة */
    public function publish(int $adminId): void
    {
        $this->status = self::STATUS_PUBLISHED;
        $this->is_active = true;
        $this->published_at = now();
        $this->published_by_admin_id = $adminId;
        $this->save();
    }
}

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

    /** قوائم مساعدة */
    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_IN_REVIEW,
        self::STATUS_PUBLISHED,
        self::STATUS_ARCHIVED,
    ];

    public const TYPES = [
        self::TYPE_FILE,
        self::TYPE_VIDEO,
        self::TYPE_LINK,
    ];

    protected $fillable = [
        'title',
        'description',
        'type',
        'source_url',
        'file_path',
        'university_id',          // إلزامي (خاصة بالمحتوى الخاص)
        'branch_id',              // دعم الفرع
        'college_id',             // اختياري ضمن نفس الجامعة/الفرع
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(UniversityBranch::class);
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

    /**
     * ربط المحتوى الخاص بالطب البشري عبر Pivot: MedicalSubjectContent
     * ملاحظة: سنعتمد وجود الموديل \App\Models\Medical\MedicalSubject مع table=MedicalSubjects
     */
    public function medicalSubjects(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Medical\MedicalSubject::class,
            'MedicalSubjectContent',
            'content_id',
            'subject_id'
        )
        ->withPivot(['sort_order', 'is_primary', 'notes', 'created_at', 'updated_at'])
        ->withTimestamps(); // لأن الجدول يحتوي created_at/updated_at
    }

    /* ============================
     | Accessors
     |============================*/
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . ltrim($this->file_path, '/')) : null;
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED && (bool) $this->is_active;
    }

    /* ============================
     | Scopes (فلترة جاهزة)
     |============================*/
    public function scopePublished($q)
    {
        return $q->where('status', self::STATUS_PUBLISHED)->where('is_active', true);
    }

    /**
     * محتوى صالح للربط بمواد الطب (مطابق للـ Trigger في DB):
     * منشور + مفعل + نوعه ملف أو رابط
     */
    public function scopeEligibleForMedicalLink($q)
    {
        return $q->where('is_active', 1)
                 ->where('status', self::STATUS_PUBLISHED)
                 ->whereIn('type', [self::TYPE_FILE, self::TYPE_LINK]);
    }

    public function scopeForUniversity($q, ?int $universityId)
    {
        return $universityId ? $q->where('university_id', $universityId) : $q;
    }

    public function scopeForBranch($q, ?int $branchId)
    {
        return $branchId ? $q->where('branch_id', $branchId) : $q;
    }

    public function scopeForCollege($q, ?int $collegeId)
    {
        return $collegeId ? $q->where('college_id', $collegeId) : $q;
    }

    public function scopeForMajor($q, ?int $majorId)
    {
        return $majorId ? $q->where('major_id', $majorId) : $q;
    }

    public function scopeType($q, ?string $type)
    {
        return $type ? $q->where('type', $type) : $q;
    }

    public function scopeStatus($q, ?string $status)
    {
        return $status ? $q->where('status', $status) : $q;
    }

    public function scopeSearch($q, ?string $qstr)
    {
        if (!$qstr) return $q;

        return $q->where(function ($w) use ($qstr) {
            $w->where('title', 'like', '%' . $qstr . '%')
              ->orWhere('description', 'like', '%' . $qstr . '%');
        });
    }

    public function scopePublishedBetween($q, ?string $from, ?string $to)
    {
        if ($from) $q->where('published_at', '>=', $from);
        if ($to)   $q->where('published_at', '<=', $to);
        return $q;
    }

    /**
     * مطابقة جمهور الطالب:
     * - نفس الجامعة (إلزامي)
     * - (الفرع NULL أو فرع الطالب)
     * - (الكلية NULL أو كلية الطالب)
     * - (التخصص NULL أو تخصص الطالب)
     */
    public function scopeMatchAudience($query, $user)
    {
        $query->where('university_id', $user->university_id);

        if ($user->branch_id) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('branch_id')
                  ->orWhere('branch_id', $user->branch_id);
            });
        }

        if ($user->college_id) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('college_id')
                  ->orWhere('college_id', $user->college_id);
            });
        }

        if ($user->major_id) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('major_id')
                  ->orWhere('major_id', $user->major_id);
            });
        }
    }

    /**
     * فلترة موحّدة للاستخدام في الـ Controllers:
     * يدعم: q, status, type, university_id, branch_id, college_id, major_id, material_id, doctor_id, is_active, from, to
     */
    public function scopeFilter($q, array $f = [])
    {
        return $q
            ->when(isset($f['q']) && $f['q'] !== '',      fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['status']),                  fn($qq) => $qq->status($f['status']))
            ->when(!empty($f['type']),                    fn($qq) => $qq->type($f['type']))
            ->when(!empty($f['university_id']),           fn($qq) => $qq->forUniversity((int)$f['university_id']))
            ->when(!empty($f['branch_id']),               fn($qq) => $qq->forBranch((int)$f['branch_id']))
            ->when(!empty($f['college_id']),              fn($qq) => $qq->forCollege((int)$f['college_id']))
            ->when(!empty($f['major_id']),                fn($qq) => $qq->forMajor((int)$f['major_id']))
            ->when(!empty($f['material_id']),             fn($qq) => $qq->where('material_id', (int)$f['material_id']))
            ->when(!empty($f['doctor_id']),               fn($qq) => $qq->where('doctor_id', (int)$f['doctor_id']))
            ->when(isset($f['is_active']) && $f['is_active'] !== '', fn($qq) => $qq->where('is_active', (bool)$f['is_active']))
            ->when(!empty($f['from']) || !empty($f['to']),fn($qq) => $qq->publishedBetween($f['from'] ?? null, $f['to'] ?? null));
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
    public function publish(int $adminId): void
    {
        $this->status = self::STATUS_PUBLISHED;
        $this->is_active = true;
        $this->published_at = now();
        $this->published_by_admin_id = $adminId;
        $this->save();
    }
}
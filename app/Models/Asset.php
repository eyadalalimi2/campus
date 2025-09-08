<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    /** حالات النشر */
    public const STATUS_DRAFT     = 'draft';
    public const STATUS_IN_REVIEW = 'in_review';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED  = 'archived';

    /** تصنيفات الأصل */
    public const CAT_YOUTUBE      = 'youtube';
    public const CAT_FILE         = 'file';
    public const CAT_REFERENCE    = 'reference';
    public const CAT_QBANK        = 'question_bank';
    public const CAT_CURRICULUM   = 'curriculum';
    public const CAT_BOOK         = 'book';

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

    protected $appends = ['file_url', 'is_published', 'video_id', 'audience_mode'];

    /* ============================
     | علاقات
     |============================*/
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

    /** جمهور الأصل كصفوف pivot مباشرة */
    public function audiences()
    {
        return $this->belongsToMany(Major::class, 'asset_audiences', 'asset_id', 'major_id');
    }


    /** تخصصات مستهدفة عبر pivot asset_audiences */
    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'asset_audiences', 'asset_id', 'major_id');
    }

    /* ============================
     | Accessors
     |============================*/
    /** رابط الملف المخزّن */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /** هل هو منشور ونشط */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED && (bool) $this->is_active;
    }

    /** وضع الجمهور: global إذا لا توجد قيود، أو restricted إذا هناك تخصصات محددة */
    public function getAudienceModeAttribute(): string
    {
        return $this->relationLoaded('audiences')
            ? ($this->audiences->isEmpty() ? 'global' : 'restricted')
            : ($this->audiences()->exists() ? 'restricted' : 'global');
    }

    /** استخراج ID يوتيوب لأنماط متعددة */
    public function getVideoIdAttribute(): ?string
    {
        if ($this->category !== self::CAT_YOUTUBE || empty($this->video_url)) {
            return null;
        }
        $url = $this->video_url;

        // أنماط مدعومة: v=، youtu.be، /embed/
        $patterns = [
            '/[?&]v=([^?&#]+)/i',
            '#youtu\.be/([^?&#/]+)#i',
            '#/embed/([^?&#/]+)#i',
        ];
        foreach ($patterns as $p) {
            if (preg_match($p, $url, $m)) {
                return $m[1] ?? null;
            }
        }
        return null;
    }

    /* ============================
     | Scopes (فلترة جاهزة)
     |============================*/
    public function scopePublished($q)
    {
        return $q->where('status', self::STATUS_PUBLISHED)->where('is_active', true);
    }

    public function scopeStatus($q, ?string $status)
    {
        return $status ? $q->where('status', $status) : $q;
    }

    public function scopeCategory($q, ?string $category)
    {
        return $category ? $q->where('category', $category) : $q;
    }

    public function scopeDiscipline($q, ?int $disciplineId)
    {
        return $disciplineId ? $q->where('discipline_id', $disciplineId) : $q;
    }

    public function scopeProgram($q, ?int $programId)
    {
        return $programId ? $q->where('program_id', $programId) : $q;
    }

    public function scopeMaterial($q, ?int $materialId)
    {
        return $materialId ? $q->where('material_id', $materialId) : $q;
    }

    public function scopeDevice($q, ?int $deviceId)
    {
        return $deviceId ? $q->where('device_id', $deviceId) : $q;
    }

    public function scopeDoctor($q, ?int $doctorId)
    {
        return $doctorId ? $q->where('doctor_id', $doctorId) : $q;
    }

    public function scopeActive($q, $active)
    {
        return isset($active) ? $q->where('is_active', (bool)$active) : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where(function ($w) use ($term) {
            $w->where('title', 'like', '%' . $term . '%')
                ->orWhere('description', 'like', '%' . $term . '%');
        });
    }

    public function scopePublishedBetween($q, ?string $from, ?string $to)
    {
        if ($from) $q->where('published_at', '>=', $from);
        if ($to)   $q->where('published_at', '<=', $to);
        return $q;
    }

    /**
     * إظهار الأصول المتاحة لطالب حسب تخصصه:
     * - لا توجد قيود جمهور => يظهر للجميع (عام)
     * - توجد قيود => يظهر إذا كان تخصص الطالب ضمن القيود
     */
    public function scopeVisibleForMajor($q, ?int $majorId)
    {
        return $q->where(function ($w) use ($majorId) {
            $w->whereDoesntHave('audiences');
            if ($majorId) {
                $w->orWhereHas('audiences', fn($a) => $a->where('major_id', $majorId));
            }
        });
    }

    /**
     * فلترة موحّدة لاستخدامها في الـ Controllers:
     * يدعم: q, status, category, discipline_id, program_id, material_id, device_id, doctor_id, is_active, from, to, major_id
     */
    public function scopeFilter($q, array $f = [])
    {
        return $q
            ->when(isset($f['q']) && $f['q'] !== '', fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['status']),        fn($qq) => $qq->status($f['status']))
            ->when(!empty($f['category']),      fn($qq) => $qq->category($f['category']))
            ->when(!empty($f['discipline_id']), fn($qq) => $qq->discipline((int)$f['discipline_id']))
            ->when(!empty($f['program_id']),    fn($qq) => $qq->program((int)$f['program_id']))
            ->when(!empty($f['material_id']),   fn($qq) => $qq->material((int)$f['material_id']))
            ->when(!empty($f['device_id']),     fn($qq) => $qq->device((int)$f['device_id']))
            ->when(!empty($f['doctor_id']),     fn($qq) => $qq->doctor((int)$f['doctor_id']))
            ->when(isset($f['is_active']),       fn($qq) => $qq->active($f['is_active']))
            ->when(
                !empty($f['from']) || !empty($f['to']),
                fn($qq) => $qq->publishedBetween($f['from'] ?? null, $f['to'] ?? null)
            )
            ->when(
                isset($f['major_id']) && $f['major_id'] !== '',
                fn($qq) => $qq->visibleForMajor((int)$f['major_id'])
            );
    }

    /** ترتيب افتراضي للتغذية */
    public function scopeOrderForFeed($q)
    {
        return $q->orderByDesc('status')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');
    }

    /* ============================
     | عمليات مساعدة
     |============================*/
    /** نشر الأصل وتعيين الحقول المرتبطة */
    public function publish(int $adminId): void
    {
        $this->status = self::STATUS_PUBLISHED;
        $this->is_active = true;
        $this->published_at = now();
        $this->published_by_admin_id = $adminId;
        $this->save();
    }
}

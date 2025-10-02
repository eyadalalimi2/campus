<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** حالات المستخدم */
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_GRADUATED = 'graduated';

    /** الجنس */
    public const GENDER_MALE   = 'male';
    public const GENDER_FEMALE = 'female';

    protected $fillable = [
        'student_number',
        'name',
        'email',
        'phone',
        'country_id',
        'profile_photo_path',

        // الهرم المؤسسي
        'university_id',
        'branch_id',
        'college_id',
        'major_id',

        // الخرائط العامة (موجودة في الجدول)
        'public_college_id',
        'public_major_id',

        // المستوى والفصل الحالي
        'level',
        'current_term',

        'gender',
        'status',

        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'level'             => 'integer',
        'current_term'      => 'integer',
    ];

    /**
     * ملاحظات مهمة:
     * - لدينا عمود فعلي باسم public_major_id في جدول users.
     * - حتى لا نصطدم معه، سنُبقيه كما هو (قابل للحفظ)،
     *   وسنقدم خاصية مشتقة اختيارية باسم derived_public_major_id
     *   تُعيد Major->public_major_id في حال أردت احتسابها ديناميكياً.
     */
    protected $appends = [
        'has_active_subscription',
        'derived_public_major_id',
    ];

    /*=============================
     | علاقات
     |=============================*/
    public function branch(): BelongsTo
    {
        return $this->belongsTo(UniversityBranch::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

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

    public function publicCollege(): BelongsTo
    {
        return $this->belongsTo(\App\Models\PublicCollege::class, 'public_college_id');
    }

    public function publicMajor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\PublicMajor::class, 'public_major_id');
    }

    /** اشتراكات الطالب */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /*=============================
     | Mutators
     |=============================*/
    public function setPasswordAttribute($value): void
    {
        if (!$value) return;
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value ? mb_strtolower(trim($value)) : null;
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value ? trim($value) : null;
    }

    public function setStudentNumberAttribute($value): void
    {
        $this->attributes['student_number'] = $value ? trim($value) : null;
    }

    /*=============================
     | Accessors
     |=============================*/
    public function getHasActiveSubscriptionAttribute(): bool
    {
        return $this->subscriptions()->active()->exists();
    }

    /**
     * derived_public_major_id:
     * قيمة مشتقة من علاقة Major->public_major_id (لا تستبدل العمود الفعلي).
     */
    public function getDerivedPublicMajorIdAttribute(): ?int
    {
        return $this->major?->public_major_id ? (int) $this->major->public_major_id : null;
    }

    /**
     * مساعد لاستخراج المسار الهرمي كاملاً (للأندرويد مثلاً).
     */
    public function audiencePath(): array
    {
        return [
            'university_id' => $this->university_id,
            'branch_id'     => $this->branch_id,
            'college_id'    => $this->college_id,
            'major_id'      => $this->major_id,
            'level'         => $this->level,
            'current_term'  => $this->current_term,
        ];
    }

    /*=============================
     | Scopes (فلترة جاهزة)
     |=============================*/
    public function scopeStatus($q, ?string $status)
    {
        return $status ? $q->where('status', $status) : $q;
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

    /**
     * فلترة بحسب التخصص العام عبر المابّينغ (users -> majors.public_major_id)
     */
    public function scopeForPublicMajor($q, ?int $publicMajorId)
    {
        if (!$publicMajorId) return $q;

        return $q->whereHas('major', function ($mq) use ($publicMajorId) {
            $mq->where('public_major_id', (int) $publicMajorId);
        });
    }

    public function scopeForCountry($q, ?int $countryId)
    {
        return $countryId ? $q->where('country_id', $countryId) : $q;
    }

    public function scopeLevel($q, $level)
    {
        return (isset($level) && $level !== '') ? $q->where('level', (int) $level) : $q;
    }

    public function scopeCurrentTerm($q, $term)
    {
        return (isset($term) && $term !== '') ? $q->where('current_term', (int) $term) : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;

        $term = trim($term);
        return $q->where(function ($w) use ($term) {
            $w->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('student_number', 'like', "%{$term}%");
        });
    }

    /**
     * فلترة موحّدة للاستخدام في الكنترولرز:
     * يدعم: q, status, university_id, branch_id, college_id, major_id, country_id, level,
     *       current_term, has_active_subscription, public_major_id (اختياري).
     */
    public function scopeFilter($q, array $f = [])
    {
        $q = $q
            ->when(isset($f['q']) && $f['q'] !== '', fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['status']),          fn($qq) => $qq->status($f['status']))
            ->when(!empty($f['university_id']),   fn($qq) => $qq->forUniversity((int) $f['university_id']))
            ->when(!empty($f['branch_id']),       fn($qq) => $qq->forBranch((int) $f['branch_id']))
            ->when(!empty($f['college_id']),      fn($qq) => $qq->forCollege((int) $f['college_id']))
            ->when(!empty($f['major_id']),        fn($qq) => $qq->forMajor((int) $f['major_id']))
            ->when(!empty($f['country_id']),      fn($qq) => $qq->forCountry((int) $f['country_id']))
            ->when(isset($f['level']) && $f['level'] !== '', fn($qq) => $qq->level($f['level']))
            ->when(isset($f['current_term']) && $f['current_term'] !== '', fn($qq) => $qq->currentTerm($f['current_term']))
            ->when(!empty($f['public_major_id']), fn($qq) => $qq->forPublicMajor((int) $f['public_major_id']));

        // تصفية حسب وجود اشتراك نشط (اختياري)
        if (isset($f['has_active_subscription'])) {
            $wantActive = filter_var($f['has_active_subscription'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($wantActive === true) {
                $q->whereHas('subscriptions', fn($s) => $s->active());
            } elseif ($wantActive === false) {
                $q->whereDoesntHave('subscriptions', fn($s) => $s->active());
            }
        }

        return $q;
    }

    /** ترتيب افتراضي: الحالة ثم الاسم */
    public function scopeOrderDefault($q)
    {
        return $q->orderByRaw("FIELD(status, 'active','suspended','graduated')")
                 ->orderBy('name');
    }

    /*=============================
     | Helpers
     |=============================*/
    public function currentSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->active()
            ->orderBy('ends_at', 'desc')
            ->first();
    }
}
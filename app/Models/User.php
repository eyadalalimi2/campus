<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

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
        'country_id',          // إلزامي في DB
        'profile_photo_path',
        'university_id',
        'college_id',
        'major_id',
        'level',
        'gender',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'level'             => 'integer',
    ];

    protected $appends = [
        'has_active_subscription',
    ];

    /* ============================
     | علاقات
     |============================*/
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

    /** اشتراكات الطالب */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /* ============================
     | Mutators
     |============================*/
    public function setPasswordAttribute($value): void
    {
        if (!$value) return;
        // لا تعيد التهشير إذا كان مُهشّرًا مسبقًا
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

    /* ============================
     | Accessors
     |============================*/
    public function getHasActiveSubscriptionAttribute(): bool
    {
        // يعتمد على سكوب Subscription::active()
        return $this->subscriptions()->active()->exists();
    }

    /* ============================
     | Scopes (فلترة جاهزة)
     |============================*/
    public function scopeStatus($q, ?string $status)
    {
        return $status ? $q->where('status', $status) : $q;
    }

    public function scopeForUniversity($q, ?int $universityId)
    {
        return $universityId ? $q->where('university_id', $universityId) : $q;
    }

    public function scopeForCollege($q, ?int $collegeId)
    {
        return $collegeId ? $q->where('college_id', $collegeId) : $q;
    }

    public function scopeForMajor($q, ?int $majorId)
    {
        return $majorId ? $q->where('major_id', $majorId) : $q;
    }

    public function scopeForCountry($q, ?int $countryId)
    {
        return $countryId ? $q->where('country_id', $countryId) : $q;
    }

    public function scopeLevel($q, $level)
    {
        return (isset($level) && $level !== '') ? $q->where('level', (int) $level) : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        $term = trim($term);
        return $q->where(function ($w) use ($term) {
            $w->where('name', 'like', '%'.$term.'%')
              ->orWhere('email', 'like', '%'.$term.'%')
              ->orWhere('phone', 'like', '%'.$term.'%')
              ->orWhere('student_number', 'like', '%'.$term.'%');
        });
    }

    /**
     * فلترة موحّدة للاستخدام في الكنترولرز:
     * يدعم: q, status, university_id, college_id, major_id, country_id, level, has_active_subscription
     */
    public function scopeFilter($q, array $f = [])
    {
        $q = $q
            ->when(isset($f['q']) && $f['q'] !== '', fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['status']),          fn($qq) => $qq->status($f['status']))
            ->when(!empty($f['university_id']),   fn($qq) => $qq->forUniversity((int)$f['university_id']))
            ->when(!empty($f['college_id']),      fn($qq) => $qq->forCollege((int)$f['college_id']))
            ->when(!empty($f['major_id']),        fn($qq) => $qq->forMajor((int)$f['major_id']))
            ->when(!empty($f['country_id']),      fn($qq) => $qq->forCountry((int)$f['country_id']))
            ->when(isset($f['level']) && $f['level'] !== '', fn($qq) => $qq->level($f['level']));

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

    /* ============================
     | Helpers
     |============================*/
    /** الاشتراك النشط الحالي (إن وجد) */
    public function currentSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->active()
            ->orderBy('ends_at', 'desc')
            ->first();
    }
}

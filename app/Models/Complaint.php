<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;

    protected $table = 'complaints';

    protected $fillable = [
        'user_id','type','subject','body','severity','status',
        'target_type','target_id','assigned_admin_id','closed_at','attachment_path',
    ];

    protected $casts = [
        'closed_at'   => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    /** العلاقات */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(Admin::class,'assigned_admin_id');
    }

    /** سكوبات مساعدة للفلاتر */
    public function scopeStatus($q, ?string $status)
    {
        return $status ? $q->where('status',$status) : $q;
    }

    public function scopeSeverity($q, ?string $sev)
    {
        return $sev ? $q->where('severity',$sev) : $q;
    }

    public function scopeType($q, ?string $type)
    {
        return $type ? $q->where('type',$type) : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where(function($w) use ($term) {
            $w->where('subject','like',"%{$term}%")
              ->orWhere('body','like',"%{$term}%")
              ->orWhereHas('user', fn($u)=>$u->where('name','like',"%{$term}%")
                                             ->orWhere('email','like',"%{$term}%"));
        });
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentRequest extends Model
{
    use SoftDeletes;

    protected $table = 'student_requests';

    protected $fillable = [
        'user_id',
        'category',
        'title',
        'body',
        'priority',
        'status',
        'assigned_admin_id',
        'closed_at',
        'attachment_path',
        'material_id',
        'content_id',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}

<?php
namespace App\Models\Medical; use Illuminate\Database\Eloquent\Model;
class ResourceFile extends Model {
    protected $table='med_resource_files';
    protected $fillable=['resource_id','storage_path','cdn_url','bytes','hash_sha256','download_allowed'];
    public function resource(){ return $this->belongsTo(Resource::class,'resource_id'); }
}

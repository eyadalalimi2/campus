<?php
namespace App\Models\Medical; use Illuminate\Database\Eloquent\Model;
class ResourceReferenceMeta extends Model {
    protected $table='med_resource_reference_meta';
    protected $fillable=['resource_id','citation_text','doi','isbn','pmid','publisher','edition'];
    public function resource(){ return $this->belongsTo(Resource::class,'resource_id'); }
}

<?php
namespace App\Http\Controllers\Medical\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Medical\ResourceRating;
use App\Models\Medical\Favorite;

class InteractionController extends BaseApiController
{
    public function rate(Request $r, $id)
    {
        $v = Validator::make(array_merge($r->all(), ['resource_id'=>$id]), [
            'user_id'    => 'required|integer|min:1',
            'resource_id'=> 'required|integer|min:1',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:500',
        ]);
        if ($v->fails()) return $this->fail('Validation error', 422, $v->errors()->toArray());

        $row = ResourceRating::updateOrCreate(
            ['user_id'=>$r->user_id, 'resource_id'=>$id],
            ['rating'=>$r->rating, 'comment'=>$r->comment, 'updated_at'=>now(), 'created_at'=>now()]
        );
        return $this->ok(['rated'=>true,'rating'=>$row->rating]);
    }

    public function toggleFavorite(Request $r)
    {
        $v = Validator::make($r->all(), [
            'user_id'    => 'required|integer|min:1',
            'resource_id'=> 'required|integer|min:1',
        ]);
        if ($v->fails()) return $this->fail('Validation error', 422, $v->errors()->toArray());

        $existing = Favorite::where('user_id',$r->user_id)->where('resource_id',$r->resource_id)->first();
        if ($existing) {
            $existing->delete();
            return $this->ok(['favorite'=>false]);
        }
        Favorite::create(['user_id'=>$r->user_id,'resource_id'=>$r->resource_id,'created_at'=>now(),'updated_at'=>now()]);
        return $this->ok(['favorite'=>true]);
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Structure;

use App\Http\Controllers\Controller;
use App\Models\PublicCollege;

class PublicTaxonomyController extends Controller
{
    public function index()
    {
        $data = PublicCollege::active()
            ->with(['publicMajors' => fn($q) => $q->active()->orderBy('name')])
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $data->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'majors' => $c->publicMajors->map(fn($m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                ]),
            ]),
        ]);
    }
}

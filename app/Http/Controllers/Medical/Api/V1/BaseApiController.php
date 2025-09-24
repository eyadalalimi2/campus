<?php
namespace App\Http\Controllers\Medical\Api\V1;

use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    protected function ok($data = null, $meta = [], $code = 200)
    {
        $payload = ['ok' => true];
        if (!is_null($data)) $payload['data'] = $data;
        if (!empty($meta))  $payload['meta'] = $meta;
        return response()->json($payload, $code);
    }

    protected function fail($message = 'Bad Request', $code = 400, $errors = [])
    {
        $payload = ['ok' => false, 'message' => $message];
        if (!empty($errors)) $payload['errors'] = $errors;
        return response()->json($payload, $code);
    }

    protected function paginate($query, $perPage = 15)
    {
        $p = (int) request('page', 1);
        $pp = (int) request('per_page', $perPage);
        $res = $query->paginate($pp, ['*'], 'page', $p);
        return [$res->items(), [
            'page' => $res->currentPage(),
            'per_page'=> $res->perPage(),
            'total' => $res->total(),
            'last_page' => $res->lastPage()
        ]];
    }
}

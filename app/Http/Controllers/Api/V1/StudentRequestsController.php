<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StudentRequests\StoreStudentRequestRequest;
use App\Http\Requests\Api\V1\StudentRequests\UpdateStudentRequestRequest;
use App\Http\Resources\Api\V1\StudentRequestResource;
use App\Models\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class StudentRequestsController extends Controller
{
    // GET /api/v1/me/requests
    public function index(Request $request)
    {
        $user = $request->user();

        $q = StudentRequest::query()->where('user_id', $user->id);

        // فلاتر اختيارية
        if ($s = $request->query('status'))   $q->where('status', $s);           // open|in_progress|resolved|rejected|closed
        if ($p = $request->query('priority')) $q->where('priority', $p);         // low|normal|high
        if ($c = $request->query('category')) $q->where('category', $c);         // general|material|account|technical|other
        if ($t = $request->query('q')) {
            $q->where(fn($w) => $w->where('title', 'like', "%{$t}%")
                ->orWhere('body', 'like', "%{$t}%"));
        }

    $default = (int) config('api.pagination.default', 20);
    $max     = (int) config('api.pagination.max', 50);
    $perPage = (int) $request->query('per_page', $default);
    $perPage = max(1, min($perPage, $max));

    $requests = $q->latest('created_at')->paginate($perPage)->withQueryString();

        return StudentRequestResource::collection($requests)
            ->additional(['status' => 'ok']);
    }

    // POST /api/v1/me/requests
    public function store(StoreStudentRequestRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['priority'] = $data['priority'] ?? 'normal';
        $data['status'] = 'open';

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('student_requests', 'public');
        }

        $req = StudentRequest::create($data);

        return (new StudentRequestResource($req))
            ->additional(['status' => 'created'])
            ->response()
            ->setStatusCode(201);
    }

    // GET /api/v1/me/requests/{id}
    public function show(Request $request, $id)
    {
        $req = StudentRequest::findOrFail($id);
        Gate::authorize('view', $req);

        return (new StudentRequestResource($req))
            ->additional(['status' => 'ok']);
    }
    // PATCH /api/v1/me/requests/{id}
    public function update(UpdateStudentRequestRequest $request, $id)
    {
        $req = StudentRequest::findOrFail($id);
        Gate::authorize('update', $req);

        $data = $request->validated();

        if (array_key_exists('title', $data)) $req->title = $data['title'];
        if (array_key_exists('body', $data))  $req->body  = $data['body'];
        if (array_key_exists('priority', $data) && $data['priority']) {
            $req->priority = $data['priority'];
        }

        // تبديل/إضافة مرفق
        if ($request->hasFile('attachment')) {
            if ($req->attachment_path && !str_starts_with($req->attachment_path, 'http')) {
                Storage::disk('public')->delete($req->attachment_path);
            }
            $req->attachment_path = $request->file('attachment')->store('student_requests', 'public');
        }

        // إغلاق اختياري
        if (!empty($data['close'])) {
            $req->status    = 'closed';
            $req->closed_at = now();
        }

        $req->save();

        return (new StudentRequestResource($req->fresh()))
            ->additional(['status' => 'ok']);
    }


    // DELETE /api/v1/me/requests/{id}
    public function destroy(Request $request, $id)
    {
        $req = StudentRequest::findOrFail($id);
        Gate::authorize('delete', $req);

        $req->delete(); // Soft delete

        return response()->json(['status' => 'deleted']);
    }
}

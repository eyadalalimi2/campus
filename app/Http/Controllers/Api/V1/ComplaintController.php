<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Complaint\StoreComplaintRequest;
use App\Http\Requests\Api\V1\Complaint\UpdateComplaintRequest;
use App\Http\Resources\Api\V1\ComplaintResource;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    // GET /api/v1/complaints
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Complaint::query()
            ->where('user_id', $user->id);

        // فلاتر
        if ($s = $request->query('status'))   $query->where('status', $s);
        if ($sev = $request->query('severity')) $query->where('severity', $sev);
        if ($t = $request->query('type'))     $query->where('type', $t);
        if ($q = $request->query('q')) {
            $query->where(fn($w)=>$w->where('subject','like',"%{$q}%")
                                     ->orWhere('body','like',"%{$q}%"));
        }

        $complaints = $query->latest('created_at')->paginate(15)->withQueryString();

        return ComplaintResource::collection($complaints)->additional(['status'=>'ok']);
    }

    // POST /api/v1/complaints
    public function store(StoreComplaintRequest $request)
    {
        $data = $request->validated();
        $data['user_id']  = $request->user()->id;
        $data['severity'] = $data['severity'] ?? 'low';
        $data['status']   = 'open';

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('complaints', 'public');
        }

        $complaint = Complaint::create($data);

        return (new ComplaintResource($complaint))
            ->additional(['status'=>'created'])
            ->response()
            ->setStatusCode(201);
    }

    // GET /api/v1/complaints/{id}
    public function show(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        Gate::authorize('view', $complaint);

        return (new ComplaintResource($complaint))->additional(['status'=>'ok']);
    }

    // PATCH /api/v1/complaints/{id}
    public function update(UpdateComplaintRequest $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        Gate::authorize('update', $complaint);

        $data = $request->validated();

        if (array_key_exists('body', $data)) {
            $complaint->body = $data['body'];
        }

        if ($request->hasFile('attachment')) {
            if ($complaint->attachment_path && !str_starts_with($complaint->attachment_path, 'http')) {
                Storage::disk('public')->delete($complaint->attachment_path);
            }
            $complaint->attachment_path = $request->file('attachment')->store('complaints', 'public');
        }

        if (!empty($data['close'])) {
            $complaint->status    = 'closed';
            $complaint->closed_at = now();
        }

        $complaint->save();

        return (new ComplaintResource($complaint->fresh()))
            ->additional(['status'=>'ok']);
    }

    // DELETE /api/v1/complaints/{id}
    public function destroy(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        Gate::authorize('delete', $complaint);

        $complaint->delete();

        return response()->json(['status'=>'deleted']);
    }
}

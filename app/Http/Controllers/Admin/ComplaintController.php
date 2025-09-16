<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateComplaintRequest;
use App\Models\Admin;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::query()
            ->with(['user:id,name,email','assignee:id,name'])
            ->status($request->string('status')->trim()->value())
            ->severity($request->string('severity')->trim()->value())
            ->type($request->string('type')->trim()->value())
            ->search($request->string('q')->trim()->value())
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $filters = [
            'status'   => $request->query('status'),
            'severity' => $request->query('severity'),
            'type'     => $request->query('type'),
            'q'        => $request->query('q'),
        ];

        return view('admin.complaints.index', compact('complaints','filters'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['user:id,name,email,phone','assignee:id,name']);
        $admins = Admin::query()->select('id','name')->orderBy('name')->get();

        return view('admin.complaints.show', compact('complaint','admins'));
    }

    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        $data = $request->validated();

        // تحديث الحقول الأساسية
        if (array_key_exists('status', $data) && $data['status']) {
            $complaint->status = $data['status'];
        }
        if (array_key_exists('severity', $data) && $data['severity']) {
            $complaint->severity = $data['severity'];
        }
        if (array_key_exists('assigned_admin_id', $data)) {
            $complaint->assigned_admin_id = $data['assigned_admin_id'];
        }

        // إغلاق فوري
        if (!empty($data['close_now']) || ($complaint->status === 'closed' && !$complaint->closed_at)) {
            $complaint->closed_at = now();
        }
        // إن حُولت إلى resolved بدون closed_at لا نغلق تلقائيًا
        if ($complaint->status !== 'closed' && $complaint->closed_at) {
            // الحفاظ على closed_at فقط للحالة closed
            $complaint->closed_at = null;
        }

        $complaint->save();

        // Log للملاحظات (اختياري)
        if (!empty($data['note'])) {
            Log::channel('single')->info('[Complaint Note]', [
                'complaint_id' => $complaint->id,
                'admin_id'     => optional(auth('admin')->user())->id,
                'note'         => $data['note'],
            ]);
        }

        return redirect()
            ->route('admin.complaints.show', $complaint)
            ->with('status', 'تم تحديث الشكوى بنجاح.');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()
            ->route('admin.complaints.index')
            ->with('status', 'تم حذف الشكوى (Soft Delete).');
    }
}

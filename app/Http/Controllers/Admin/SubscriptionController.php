<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $r)
    {
        $q = Subscription::with('user')->latest();

        if ($s = $r->get('q')) {
            $q->whereHas('user', function($w) use ($s){
                $w->where('name','like',"%$s%")
                  ->orWhere('email','like',"%$s%")
                  ->orWhere('student_number','like',"%$s%");
            });
        }
        if ($r->filled('status')) $q->where('status',$r->status);
        if ($r->filled('plan'))   $q->where('plan',$r->plan);

        $subs = $q->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('admin.subscriptions.index', compact('subs','users'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.subscriptions.create', compact('users'));
    }

    public function store(SubscriptionRequest $req)
    {
        $data = $req->validated();
        $data['auto_renew'] = (bool)$req->boolean('auto_renew');
        Subscription::create($data);
        return redirect()->route('admin.subscriptions.index')->with('success','تم إنشاء الاشتراك.');
    }

    public function edit(Subscription $subscription)
    {
        $users = User::orderBy('name')->get();
        return view('admin.subscriptions.edit', ['subscription'=>$subscription, 'users'=>$users]);
    }

    public function update(SubscriptionRequest $req, Subscription $subscription)
    {
        $data = $req->validated();
        $data['auto_renew'] = (bool)$req->boolean('auto_renew');
        $subscription->update($data);
        return redirect()->route('admin.subscriptions.index')->with('success','تم تحديث الاشتراك.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return back()->with('success','تم حذف الاشتراك.');
    }
}

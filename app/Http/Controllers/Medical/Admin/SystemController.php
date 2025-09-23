<?php
namespace App\Http\Controllers\Medical\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medical\SystemRequest;
use App\Models\Medical\System;
use Illuminate\Http\Request;

class SystemController extends Controller {
    public function index() {
        $items = System::orderBy('display_order')->paginate(20);
        return view('medical.admin.systems.index', compact('items'));
    }
    public function create(){ return view('medical.admin.systems.create'); }
    public function store(SystemRequest $req){
        System::create($req->validated());
        return redirect()->route('medical.systems.index')->with('ok','تم الإنشاء');
    }
    public function edit(System $system){ return view('medical.admin.systems.edit', compact('system')); }
    public function update(SystemRequest $req, System $system){
        $system->update($req->validated());
        return redirect()->route('medical.systems.index')->with('ok','تم التحديث');
    }
    public function destroy(System $system){
        $system->delete();
        return back()->with('ok','تم الحذف');
    }
}

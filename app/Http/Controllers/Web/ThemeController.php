<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\University;

class ThemeController extends Controller
{
    public function setUniversity(Request $r) {
        $r->validate(['university_id'=>'required|exists:universities,id']);
        session(['current_university_id' => (int)$r->university_id]);
        return back()->with('success','تم تعيين الجامعة الحالية.');
    }
}

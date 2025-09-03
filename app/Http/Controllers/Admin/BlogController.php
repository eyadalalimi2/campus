<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use App\Models\University;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $r)
    {
        $q = Blog::with(['university','doctor'])->latest();

        if ($s = $r->get('q')) {
            $q->where(function($w) use ($s){
                $w->where('title','like',"%$s%")
                  ->orWhere('slug','like',"%$s%")
                  ->orWhere('excerpt','like',"%$s%");
            });
        }
        if ($r->filled('status'))       $q->where('status',$r->status);
        if ($r->filled('university_id'))$q->where('university_id',$r->university_id);
        if ($r->filled('doctor_id'))    $q->where('doctor_id',$r->doctor_id);

        $blogs = $q->paginate(15)->withQueryString();
        $universities = University::orderBy('name')->get();
        $doctors = Doctor::orderBy('name')->get();

        return view('admin.blogs.index', compact('blogs','universities','doctors'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $doctors = Doctor::orderBy('name')->get();
        return view('admin.blogs.create', compact('universities','doctors'));
    }

    public function store(BlogRequest $req)
    {
        $data = $req->validated();
        $data['is_active'] = (bool)$req->boolean('is_active');

        if ($req->hasFile('cover_image')) {
            $data['cover_image_path'] = $req->file('cover_image')->store('blogs','public');
        }

        Blog::create($data);
        return redirect()->route('admin.blogs.index')->with('success','تم إنشاء التدوينة.');
    }

    public function edit(Blog $blog)
    {
        $universities = University::orderBy('name')->get();
        $doctors = Doctor::orderBy('name')->get();
        return view('admin.blogs.edit', compact('blog','universities','doctors'));
    }

    public function update(BlogRequest $req, Blog $blog)
    {
        $data = $req->validated();
        $data['is_active'] = (bool)$req->boolean('is_active');

        if ($req->hasFile('cover_image')) {
            if ($blog->cover_image_path) Storage::disk('public')->delete($blog->cover_image_path);
            $data['cover_image_path'] = $req->file('cover_image')->store('blogs','public');
        }

        $blog->update($data);
        return redirect()->route('admin.blogs.index')->with('success','تم تحديث التدوينة.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->cover_image_path) Storage::disk('public')->delete($blog->cover_image_path);
        $blog->delete();
        return back()->with('success','تم حذف التدوينة.');
    }
}

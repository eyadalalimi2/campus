<?php
namespace App\Http\Controllers\Medical\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medical\ResourceRequest;
use App\Models\Medical\Resource;
use App\Models\Medical\ResourceFile;
use App\Models\Medical\ResourceYoutubeMeta;
use App\Models\Medical\ResourceReferenceMeta;
use App\Models\Medical\Subject;
use App\Models\Medical\System;
use App\Models\Medical\Doctor;
use App\Models\Medical\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller {
    public function index(Request $r){
        $q = Resource::with(['subject','system','doctor'])->orderByDesc('id');
        if($r->filled('type')) $q->where('type',$r->get('type'));
        if($r->filled('track')) $q->where('track',$r->get('track'));
        if($r->filled('subject_id')) $q->where('subject_id',$r->get('subject_id'));
        if($r->filled('system_id')) $q->where('system_id',$r->get('system_id'));
        $items = $q->paginate(20)->appends($r->query());
        return view('medical.admin.resources.index', [
            'items'=>$items,
            'subjects'=>Subject::orderBy('code')->get(),
            'systems'=>System::orderBy('display_order')->get()
        ]);
    }

    public function create(){
        return view('medical.admin.resources.create', [
            'subjects'=>Subject::orderBy('code')->get(),
            'systems'=>System::orderBy('display_order')->get(),
            'doctors'=>Doctor::orderBy('name')->get(),
        ]);
    }

    public function store(ResourceRequest $req){
        return DB::transaction(function() use ($req){
            $data = $req->validated();
            $data['authors'] = $data['authors'] ?? [];
            $res = Resource::create($data);

            // إن كان YOUTUBE أنشئ الميتا
            if($res->type === 'YOUTUBE'){
                ResourceYoutubeMeta::create([
                    'resource_id'=>$res->id,
                    'provider'=>'YOUTUBE',
                    'channel_id'=>$req->input('channel_id'),
                    'video_id'=>$req->input('video_id'),
                    'playlist_id'=>$req->input('playlist_id'),
                ]);
            }
            // إن كان REFERENCE أنشئ الميتا
            if($res->type === 'REFERENCE'){
                ResourceReferenceMeta::create([
                    'resource_id'=>$res->id,
                    'citation_text'=>$req->input('citation_text',''),
                    'doi'=>$req->input('doi'),
                    'isbn'=>$req->input('isbn'),
                    'pmid'=>$req->input('pmid'),
                    'publisher'=>$req->input('publisher'),
                    'edition'=>$req->input('edition'),
                ]);
            }
            return redirect()->route('medical.resources.edit',$res)->with('ok','تم الإنشاء');
        });
    }

    public function edit(Resource $resource){
        return view('medical.admin.resources.edit', [
            'item'=>$resource->load(['files','youtube','reference','universities']),
            'subjects'=>Subject::orderBy('code')->get(),
            'systems'=>System::orderBy('display_order')->get(),
            'doctors'=>Doctor::orderBy('name')->get(),
            'universities'=>University::orderBy('name')->get(),
        ]);
    }

    public function update(ResourceRequest $req, Resource $resource){
        return DB::transaction(function() use ($req,$resource){
            $resource->update($req->validated());
            if($resource->type==='YOUTUBE'){
                $meta = $resource->youtube ?: new ResourceYoutubeMeta(['resource_id'=>$resource->id]);
                $meta->fill([
                    'channel_id'=>$req->input('channel_id'),
                    'video_id'=>$req->input('video_id'),
                    'playlist_id'=>$req->input('playlist_id'),
                ])->save();
            }
            if($resource->type==='REFERENCE'){
                $meta = $resource->reference ?: new ResourceReferenceMeta(['resource_id'=>$resource->id]);
                $meta->fill([
                    'citation_text'=>$req->input('citation_text',''),
                    'doi'=>$req->input('doi'),
                    'isbn'=>$req->input('isbn'),
                    'pmid'=>$req->input('pmid'),
                    'publisher'=>$req->input('publisher'),
                    'edition'=>$req->input('edition'),
                ])->save();
            }
            return back()->with('ok','تم التحديث');
        });
    }

    public function destroy(Resource $resource){
        $resource->delete();
        return back()->with('ok','تم الحذف');
    }

    // ملفات موارد (Books/Summaries)
    public function storeFile(Request $r, Resource $resource){
        $r->validate(['file'=>'required|file','download_allowed'=>'nullable|boolean']);
        $path = $r->file('file')->store('medical','public'); // استخدم disk مناسب
        $file = ResourceFile::create([
            'resource_id'=>$resource->id,
            'storage_path'=>$path,
            'cdn_url'=>Storage::disk('public')->url($path),
            'bytes'=>$r->file('file')->getSize(),
            'download_allowed'=>(bool)$r->get('download_allowed',false),
        ]);
        return back()->with('ok','تم رفع الملف');
    }
    public function destroyFile(Resource $resource, ResourceFile $file){
        $file->delete();
        return back()->with('ok','تم حذف الملف');
    }

    // ربط/فك الجامعات
    public function attachUniversity(Request $r, Resource $resource){
        $r->validate(['university_id'=>'required|exists:med_universities,id']);
        $resource->universities()->syncWithoutDetaching([$r->university_id]);
        return back()->with('ok','تم الربط بالجامعة');
    }
    public function detachUniversity(Resource $resource, University $university){
        $resource->universities()->detach($university->id);
        return back()->with('ok','تم الإزالة');
    }
}

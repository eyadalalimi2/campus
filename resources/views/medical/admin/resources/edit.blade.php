
@extends('admin.layouts.app')
@section('title','تعديل مورد')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-7">
    <div class="card mb-4">
      <div class="card-header"><i class="bi bi-collection"></i> تعديل مورد</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.resources.update',$item) }}">@csrf @method('PUT')
          <!-- نفس حقول create مع تعبئة قيم $item -->
          <!-- اختصرنا لعدم التكرار -->
          <button class="btn btn-success"><i class="bi bi-save"></i> تحديث</button>
        </form>
      </div>
    </div>
  </div>
</div>

@if($item->type === 'YOUTUBE')
  <h3>YouTube Meta</h3>
  <form method="post" action="{{ route('medical.resources.update',$item) }}">@csrf @method('PUT')
    <input type="hidden" name="type" value="YOUTUBE">
    <input type="hidden" name="track" value="{{ $item->track }}">
    <input type="hidden" name="subject_id" value="{{ $item->subject_id }}">
    <label>channel_id</label><input name="channel_id" value="{{ $item->youtube->channel_id ?? '' }}">
    <label>video_id</label><input name="video_id" value="{{ $item->youtube->video_id ?? '' }}">
    <label>playlist_id</label><input name="playlist_id" value="{{ $item->youtube->playlist_id ?? '' }}">
    <button>تحديث YT</button>
  </form>
@endif

@if($item->type === 'REFERENCE')
  <h3>Reference Meta</h3>
  <form method="post" action="{{ route('medical.resources.update',$item) }}">@csrf @method('PUT')
    <input type="hidden" name="type" value="REFERENCE">
    <input type="hidden" name="track" value="{{ $item->track }}">
    <input type="hidden" name="subject_id" value="{{ $item->subject_id }}">
    <label>citation_text</label><textarea name="citation_text">{{ $item->reference->citation_text ?? '' }}</textarea>
    <label>doi</label><input name="doi" value="{{ $item->reference->doi ?? '' }}">
    <label>isbn</label><input name="isbn" value="{{ $item->reference->isbn ?? '' }}">
    <label>pmid</label><input name="pmid" value="{{ $item->reference->pmid ?? '' }}">
    <label>publisher</label><input name="publisher" value="{{ $item->reference->publisher ?? '' }}">
    <label>edition</label><input name="edition" value="{{ $item->reference->edition ?? '' }}">
    <button>تحديث المرجع</button>
  </form>
@endif

<h3>ملفات</h3>
<form method="post" action="{{ route('medical.resources.files.store',$item) }}" enctype="multipart/form-data">
  @csrf
  <input type="file" name="file" required>
  <label>السماح بالتنزيل</label><input type="checkbox" name="download_allowed">
  <button>رفع</button>
</form>
<ul>
  @foreach($item->files as $f)
    <li>{{ $f->cdn_url }} ({{ $f->bytes }} bytes)
      <form method="post" action="{{ route('medical.resources.files.destroy',[$item,$f]) }}" style="display:inline">@csrf @method('DELETE')<button>حذف</button></form>
    </li>
  @endforeach
</ul>

<h3>الجامعات المخوّلة</h3>
<form method="post" action="{{ route('medical.resources.universities.attach',$item) }}">
  @csrf
  <select name="university_id">@foreach($universities as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select>
  <button>إضافة</button>
</form>
<ul>
  @foreach($item->universities as $u)
    <li>{{ $u->name }}
      <form method="post" action="{{ route('medical.resources.universities.detach',[$item,$u]) }}" style="display:inline">@csrf @method('DELETE')<button>إزالة</button></form>
    </li>
  @endforeach
</ul>
@endsection

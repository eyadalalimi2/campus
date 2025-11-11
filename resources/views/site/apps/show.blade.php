@extends('site.layouts.app')

@section('title', $app->name)

@push('styles')
@php $dark = (($themeVars['mode'] ?? 'auto') === 'dark'); @endphp
<style>
  :root{ --gp-primary:#0f9d58; --gp-bg:#f6f6f6; --gp-card:#fff; --gp-text:#1f1f1f; --gp-sub:#5f6368; --radius:16px; }
  @if($dark)
    :root{ --gp-bg:#0f1520; --gp-card:#111827; --gp-text:#e5e7eb; --gp-sub:#9aa4b2; }
  @endif
  /* توافق كامل مع ثيم التخطيط الحالي */
  .gp-card{background:var(--gp-card);border-radius:var(--radius);box-shadow:0 1px 2px rgba(0,0,0,.06)}
  .app-icon{width:96px;height:96px;border-radius:24%;object-fit:cover;box-shadow:0 2px 6px rgba(0,0,0,.12)}
  .btn-install{background:var(--gp-primary);color:#fff;border:none;border-radius:999px;padding:.75rem 1.25rem;font-weight:700}
  .btn-install:hover{filter:brightness(.95)} .btn-ghost{border-radius:999px}
  .gp-chip{display:inline-flex;gap:.5rem;align-items:center;border-radius:999px;padding:.35rem .75rem;background:#eef7f0;color:#185c3b;font-weight:500;font-size:.85rem}
  @if($dark).gp-chip{background:#153a2a;color:#b5f5c9}@endif
  .rating-wrap{display:flex;align-items:center;gap:.5rem}
  .stars{position:relative;width:110px;height:22px}
  .stars::before,.stars::after{content:"★★★★★";position:absolute;inset:0;font-size:22px;letter-spacing:2px}
  .stars::before{color:#d6d6d6} .stars::after{color:#f5a623;width:var(--fill,0%);overflow:hidden;white-space:nowrap}
  .shots-scroll{display:flex;gap:12px;overflow:auto;scroll-snap-type:x mandatory;padding-bottom:6px}
  .shot{scroll-snap-align:start;border-radius:14px;overflow:hidden;width:280px;aspect-ratio:9/16;background:#000;border:1px solid #eaeaea;cursor:pointer}
  .shot img{width:100%;height:100%;object-fit:cover}
  .shot.video{position:relative}
  .shot.video .play-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#fff;font-size:48px;text-shadow:0 2px 8px rgba(0,0,0,.6);background:linear-gradient(to bottom,rgba(0,0,0,.05),rgba(0,0,0,.35))}
  .bar{height:10px;background:#eee;border-radius:999px;overflow:hidden}
  .bar>span{display:block;height:100%;background:#f5a623;width:0}
  @media(max-width:768px){.sticky-install{position:fixed;inset-block-end:0;inset-inline:0;background:var(--gp-card);border-top:1px solid #eee;padding:.75rem;z-index:1030}}
  .clamp{display:-webkit-box;-webkit-line-clamp:6;-webkit-box-orient:vertical;overflow:hidden}
  .clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
  .app-card{width:140px} .app-card .icon{width:64px;height:64px;border-radius:18%;object-fit:cover}
  .kv dt{color:var(--gp-sub);font-weight:500} .kv dd{margin-inline-start:0;margin-bottom:.75rem}
  /* cover image */
  .cover-img{display:block;width:100%;aspect-ratio:16/9;object-fit:cover;background:#000;max-height:420px}
  @media (min-width:992px){.cover-img{border-radius:var(--radius);max-height:360px}}
  @media (max-width:575.98px){.cover-img{aspect-ratio:auto;height:190px;object-fit:cover}}
  /* reviews */
  .rv-item{display:flex;gap:.75rem}
  .rv-avatar{width:40px;height:40px;border-radius:50%;object-fit:cover}
  .rv-body{flex:1}
  .rv-name{font-weight:600}
  .rv-stars{--fill:0%;position:relative;width:90px;height:18px}
  .rv-stars::before,.rv-stars::after{content:"★★★★★";position:absolute;inset:0;font-size:18px;letter-spacing:2px}
  .rv-stars::before{color:#d6d6d6}.rv-stars::after{color:#f5a623;width:var(--fill,0%);overflow:hidden;white-space:nowrap}
  .rv-reply{background:rgba(15,157,88,.06);border:1px solid rgba(15,157,88,.15);border-radius:12px;padding:.75rem}
  @if($dark).rv-reply{background:#102a1d;border-color:#1f4d37}@endif
  /* category badge تحسين بطاقة الفئة */
  .cat-badge{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;border-radius:999px;font-size:.8rem;font-weight:600;background:linear-gradient(135deg,#eef5ff,#e2ecff);color:#0a4a7a;box-shadow:0 1px 2px rgba(0,0,0,.06);}
  .cat-badge i{font-size:1rem;line-height:1}
  @if($dark).cat-badge{background:linear-gradient(135deg,#1e293b,#0f172a);color:#cbd5e1}@endif
  /* نمط خاص للتعليم */
  .cat-badge.cat-edu{background:linear-gradient(135deg,#e3f9ff,#d0f0ff);color:#055874}
  @if($dark).cat-badge.cat-edu{background:linear-gradient(135deg,#063c49,#052d38);color:#8ee6ff}@endif
  /* تحسين زر عرض المزيد + تغطية تلاشي للنص الطويل */
  .about-wrapper{position:relative;overflow:hidden;transition:max-height .35s ease;}
  .about-wrapper.clamped::after{content:"";position:absolute;inset:0;pointer-events:none;background:linear-gradient(to top, var(--gp-card) 0%, rgba(255,255,255,0) 55%);} 
  @if($dark).about-wrapper.clamped::after{background:linear-gradient(to top, var(--gp-card) 0%, rgba(0,0,0,0) 55%);} @endif
  .btn-toggle-more{display:inline-flex;align-items:center;gap:.35rem;font-weight:600;font-size:.85rem;color:var(--gp-primary);text-decoration:none}
  .btn-toggle-more i{transition:transform .3s}
  .btn-toggle-more[aria-expanded="true"] i{transform:rotate(180deg)}
  /* عند التوسيع، عطِّل خصائص القص مهما وُجدت .clamp على النص */
  .about-wrapper:not(.clamped) .clamp{display:block;overflow:visible;-webkit-line-clamp:unset;-webkit-box-orient:initial}
  /* قائمة بعناوين وأيقونات بسيطة */
  .list-icon{list-style:none;margin:0;padding:0}
  .list-icon li{display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem}
  .list-icon .ic{width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;background:#eef2f7;color:#0a4a7a}
  @if($dark).list-icon .ic{background:#0f172a;color:#cbd5e1}@endif
  .copy-btn{border:0;background:transparent;color:var(--gp-sub);padding:0 .25rem}
  .copy-btn:hover{color:var(--gp-text)}
  /* أزرار ملوّنة احترافية */
  .btn-fancy{position:relative;overflow:hidden;border:none;border-radius:999px;font-weight:600;display:inline-flex;align-items:center;gap:.45rem;padding:.6rem 1.1rem;font-size:.8rem;line-height:1.2;background:linear-gradient(135deg,#0f9d58,#34a853);color:#fff;box-shadow:0 2px 4px rgba(0,0,0,.15);transition:filter .25s,transform .25s}
  .btn-fancy:hover{filter:brightness(.92)}
  .btn-fancy:active{transform:translateY(1px)}
  .btn-fancy.btn-alt{background:linear-gradient(135deg,#1a73e8,#4285f4)}
  .btn-fancy.btn-amber{background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#222}
  .btn-fancy i{font-size:1rem}
  @if($dark){
    .btn-fancy{box-shadow:0 2px 6px rgba(0,0,0,.4)}
  }
  @endif
  /* زر عرض/إخفاء بيانات المطوّر */
  .btn-toggle-dev{border:none;border-radius:999px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;font-weight:600;padding:.45rem .95rem;font-size:.75rem;display:inline-flex;align-items:center;gap:.4rem}
  .btn-toggle-dev:hover{filter:brightness(.92)}
  .btn-toggle-dev i{transition:transform .3s}
  .btn-toggle-dev[aria-expanded="true"] i{transform:rotate(180deg)}
  /* زر سياسة الخصوصية خاص */
  .btn-privacy{background:linear-gradient(135deg,#10b981,#059669);border:none;color:#fff;border-radius:10px;font-weight:600;padding:.5rem .85rem;font-size:.75rem;display:inline-flex;align-items:center;gap:.4rem}
  .btn-privacy:hover{filter:brightness(.95)}
  /* زر إظهار المزيد من التطبيقات */
  .btn-more-apps{background:linear-gradient(135deg,#1e3a8a,#0f172a);color:#fff;border:none;border-radius:999px;padding:.55rem 1rem;font-weight:600;font-size:.75rem;display:inline-flex;align-items:center;gap:.45rem}
  .btn-more-apps:hover{filter:brightness(.92)}
  @if($dark){
    .btn-more-apps{background:linear-gradient(135deg,#3b82f6,#1d4ed8)}
  }
  @endif
  .app-card.hidden-app{opacity:0;transform:translateY(6px);transition:opacity .35s,transform .35s}
  .app-card.hidden-app.revealed{opacity:1;transform:translateY(0)}
</style>
@endpush

@section('content')
@php
  // بيانات مساعدة
  $screens = $app->screenshots; if (is_string($screens)) $screens = json_decode($screens, true); if (!is_array($screens)) $screens = [];
  $tags = $app->tags; if (is_string($tags)) $tags = json_decode($tags, true); if (!is_array($tags)) $tags = [];
  $coverUrl = $app->feature_image_path ? Storage::url($app->feature_image_path) : ($app->icon_path ? Storage::url($app->icon_path) : asset('images/default-app-icon.png'));
  $iconUrl = $app->icon_path ? Storage::url($app->icon_path) : asset('images/default-app-icon.png');
  $devLogoUrl = !empty($app->developer_logo) ? Storage::url($app->developer_logo) : null; // شعار المطوّر
  // يوتيوب embed
  $embed = null; if (!empty($app->video_url)) { $v = $app->video_url; if (Str::contains($v, 'youtu.be/')) { $embed = preg_replace('~https?://youtu\.be/([A-Za-z0-9_\-]+)~', 'https://www.youtube.com/embed/$1', $v); } elseif (Str::contains($v, 'watch?v=')) { $embed = preg_replace('~.*watch\?v=([A-Za-z0-9_\-]+).*~', 'https://www.youtube.com/embed/$1', $v); } elseif (Str::contains($v, '/embed/')) { $embed = $v; } }
  // صورة غلاف/مصغّرة للفيديو (من لوحة التحكم)، مع بدائل ذكية
  $videoThumbUrl = null;
  if (!empty($app->video_cover_image)) {
    $videoThumbUrl = Storage::url($app->video_cover_image);
  } elseif (!empty($app->video_url)) {
    $v = $app->video_url; $vid = null;
    if (preg_match('~youtu\.be/([A-Za-z0-9_\-]+)~', $v, $m)) { $vid = $m[1]; }
    elseif (preg_match('~[?&]v=([A-Za-z0-9_\-]+)~', $v, $m)) { $vid = $m[1]; }
    elseif (preg_match('~/embed/([A-Za-z0-9_\-]+)~', $v, $m)) { $vid = $m[1]; }
    if ($vid) { $videoThumbUrl = 'https://img.youtube.com/vi/'.$vid.'/hqdefault.jpg'; }
  }
  if (!$videoThumbUrl) { $videoThumbUrl = $coverUrl; }
  // تقييم
  $rating = isset($rating) ? floatval($rating) : floatval($app->rating ?? 0);
  $reviewsCount = isset($reviewsCount) ? intval($reviewsCount) : intval($app->reviews_count ?? 0);
  $ratingFill = max(0,min(100, round(($rating/5)*100)));
  // تفصيل التقييمات
  $breakdown = $breakdown ?? ($app->ratings_breakdown ?? null); // مثال: [5=>80,4=>12,3=>5,2=>2,1=>1]
  // التنزيلات
  $installsRaw = intval($app->downloads_total ?? 0);
  $installsLabel = '—';
  if (!empty($app->installs_label)) {
    $installsLabel = $app->installs_label; // يحترم نص مخصص إن وُجد
  } elseif ($installsRaw > 0) {
    $units = [1000000000 => 'مليار', 1000000 => 'مليون', 1000 => 'ألف'];
    $formatted = null; $suffix = '';
    foreach ($units as $base => $name) {
      if ($installsRaw >= $base) {
        $val = $installsRaw / $base;
        $formatted = $val >= 100 ? number_format($val, 0, '.', '') : rtrim(rtrim(number_format($val, 1, '.', ''), '0'), '.');
        $suffix = ' ' . $name;
        break;
      }
    }
    if ($formatted === null) { $formatted = number_format($installsRaw); }
    $installsLabel = $formatted . $suffix . '+'; // مثال: 1.2 مليون+
  }
  // سجل التغييرات كسطور
  $changelogLines = [];
  if(!empty($app->changelog)){
    $lines = preg_split("/
?
/", trim($app->changelog));
    foreach($lines as $ln){ $ln = trim($ln, "-*• 	"); if($ln!=='') $changelogLines[] = $ln; }
  }
@endphp

<div class="py-2" dir="rtl"><!-- لا نُكرّر حاوية .container لأن التخطيط يوفرها -->

  {{-- ===== صورة الغلاف أو الأيقونة (دائمًا) ===== --}}
  <section class="gp-card p-0 overflow-hidden mb-3">
    <img class="cover-img" src="{{ $coverUrl }}" alt="صورة الغلاف {{ $app->name }}" loading="lazy">
  </section>

  {{-- (تمت إزالة قسم "فيديو تعريفي" بناءً على طلب المستخدم) --}}

  {{-- ===== رأس التطبيق (على نمط Google Play) ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <div class="row g-3 align-items-start">
      <div class="col-auto">
        <img class="app-icon" src="{{ $iconUrl }}" alt="أيقونة {{ $app->name }}" loading="lazy">
      </div>
      <div class="col">
        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
          <h1 class="h3 m-0">{{ $app->name }}</h1>
          @if($app->category)
            @php $cat = $app->category; $isEdu = in_array(Str::lower($cat), ['التعليم','education']); @endphp
            <span class="cat-badge @if($isEdu) cat-edu @endif" title="الفئة: {{ $cat }}">
              <i class="bi @if($isEdu) bi-mortarboard @else bi-folder2-open @endif"></i>
              <span>{{ $cat }}</span>
            </span>
          @endif
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
          <div class="rating-wrap" aria-label="التقييم {{ number_format($rating,1) }} من 5">
            <strong class="fs-5 @if($dark) text-light @else text-dark @endif">{{ number_format($rating,1) }}</strong>
            <div class="stars" style="--fill: {{ $ratingFill }}%;" title="{{ number_format($rating,1) }} / 5"></div>
            <span>( {{ number_format($reviewsCount) }} مراجعة )</span>
          </div>
          <span>{{ $installsLabel }} تنزيل</span>
          @if($app->content_rating)
            <span>{{ $app->content_rating }}</span>
          @endif
        </div>
        @if(!empty($app->short_description))
          <div class="text-muted mt-2 clamp-3">{{ $app->short_description }}</div>
        @endif
      </div>
      <div class="col-12 col-md-auto d-flex gap-2 mt-2 mt-md-0">
        <a class="btn btn-install" id="installBtn" href="{{ route('apps.download', $app->slug) }}">
          <i class="bi bi-download ms-1"></i> تثبيت
        </a>
        @if($embed)
        <button class="btn btn-outline-secondary btn-ghost" id="watchVideoBtn" aria-label="مشاهدة الفيديو">
          <i class="bi bi-play-circle"></i>
        </button>
        @endif
        <button class="btn btn-outline-secondary btn-ghost" id="shareBtn">
          <i class="bi bi-share"></i>
        </button>
      </div>
    </div>
    <div class="mt-3 d-flex flex-wrap gap-2">
      @if(!empty($app->has_ads))<span class="gp-chip">يحتوي على إعلانات</span>@endif
      @if(!empty($app->has_iap))<span class="gp-chip">يتضمن مشتريات داخل التطبيق</span>@endif
    </div>
  </section>

  {{-- ===== لقطات الشاشة ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <h2 class="h5 mb-3">لقطات الشاشة</h2>
    @php $hasShots = count($screens) > 0; @endphp
    @if($hasShots || $embed)
      <div class="shots-scroll" id="shots">
        @if($embed)
          <button class="shot video" id="videoTile" type="button" aria-label="فيديو">
            <img src="{{ $videoThumbUrl }}" alt="غلاف فيديو {{ $app->name }}" loading="lazy">
            <span class="play-overlay"><i class="bi bi-play-circle-fill"></i></span>
          </button>
        @endif
        @foreach($screens as $i=>$s)
          @php $url = Storage::url($s); @endphp
          <button class="shot" data-img="{{ $url }}" aria-label="لقطة {{ $i+1 }}"><img src="{{ $url }}" alt="لقطة شاشة {{ $i+1 }}" loading="lazy"></button>
        @endforeach
      </div>
    @else
      <div class="text-muted">لا توجد لقطات شاشة أو فيديو.</div>
    @endif
  </section>

  {{-- ===== ما الجديد ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h2 class="h5 m-0">ما الجديد</h2>
      <span class="text-muted small">
        @if(!empty($app->updated_at)) آخر تحديث: {{ optional($app->updated_at)->format('Y-m-d') }} • @endif
        الإصدار {{ $app->version_name ?? '-' }}
      </span>
    </div>
    @if(count($changelogLines))
      <ul class="mb-0">
        @foreach($changelogLines as $ln)<li>{{ $ln }}</li>@endforeach
      </ul>
    @else
      <div class="text-muted">لا توجد ملاحظات إصدار.</div>
    @endif
  </section>

  {{-- ===== عن هذا التطبيق ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <h2 class="h5">عن هذا التطبيق</h2>
    @if($app->long_description)
      @php $aboutLen = mb_strlen(strip_tags($app->long_description)); @endphp
      <div class="about-wrapper clamped" id="aboutWrapper" data-full-height="0">
        <div class="clamp" id="aboutLong">{!! nl2br(e($app->long_description)) !!}</div>
      </div>
      <button class="btn btn-toggle-more p-0 mt-2" id="toggleAbout" aria-expanded="false">
        <span class="label">عرض المزيد</span>
        <i class="bi bi-chevron-down"></i>
      </button>
    @endif
    <div class="mt-3 d-flex flex-wrap gap-2">
      @forelse($tags as $t)
        <span class="badge text-bg-light">{{ $t }}</span>
      @empty
        <span class="badge text-bg-light">سهل الاستخدام</span>
      @endforelse
    </div>
  </section>

  {{-- ===== التقييمات والمراجعات ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <div class="row g-4 align-items-center">
      <div class="col-md-3 text-center">
        <div class="display-5 fw-bold">{{ number_format($rating,1) }}</div>
        <div class="stars mx-auto" style="--fill:{{ $ratingFill }}%" title="{{ number_format($rating,1) }} / 5"></div>
        <div class="text-muted">استنادًا إلى {{ number_format($reviewsCount) }} مراجعة</div>
      </div>
      <div class="col-md-9">
        @php
          $dist = $breakdown ?: [5=>null,4=>null,3=>null,2=>null,1=>null];
          if(!$breakdown && $rating>0){ $dist = [5=>max(0,min(100,($rating-0.5)/4.5*80+10)),4=>12,3=>5,2=>2,1=>1]; }
        @endphp
        @foreach([5,4,3,2,1] as $star)
          @php $w = isset($dist[$star]) && $dist[$star]!==null ? $dist[$star] : 0; @endphp
          <div class="d-flex align-items-center gap-2 mb-2">
            <span>{{ $star }} نجوم</span>
            <div class="bar flex-grow-1"><span style="width:{{ $w }}%"></span></div>
          </div>
        @endforeach
      </div>
    </div>

    @isset($reviews)
      <hr class="my-3" />
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="h6 m-0">تعليقات المستخدمين</h3>
      </div>
      @php $totalReviews = $reviews->count(); $reviewsLimited = $reviews->take(2); $hasMoreReviews = $totalReviews > 2; @endphp
      <div class="vstack gap-3" id="reviewsList" data-expanded="false" data-total="{{ $totalReviews }}">
        @forelse($reviewsLimited as $rv)
          @php
            $u = $rv->user;
            $name = $u->name ?? 'مستخدم';
            $avatar = method_exists($u, 'getProfilePhotoUrlAttribute') ? ($u->profile_photo_url ?? null) : null;
            if (!$avatar && !empty($u->profile_photo_path)) { $avatar = Storage::url($u->profile_photo_path); }
            $fill = max(0,min(100, round(($rv->rating/5)*100)));
          @endphp
          <div class="rv-item">
            <img class="rv-avatar" src="{{ $avatar ?: asset('images/default-avatar.svg') }}" alt="{{ $name }}" loading="lazy">
            <div class="rv-body">
              <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="rv-name">{{ $name }}</span>
                <span class="rv-stars" style="--fill: {{ $fill }}%" aria-label="{{ $rv->rating }} من 5"></span>
                <span class="text-muted small">{{ optional($rv->created_at)->diffForHumans() }}</span>
              </div>
              @if($rv->comment)
                <div class="mt-1">{{ $rv->comment }}</div>
              @endif

              @if($rv->reply_text)
                <div class="rv-reply mt-2">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-patch-check-fill text-success"></i>
                    <strong>رد المطوّر</strong>
                    @if($rv->replyAdmin)
                      <span class="text-muted small">— {{ $rv->replyAdmin->name }}</span>
                    @endif
                    @if($rv->replied_at)
                      <span class="text-muted small">• {{ optional($rv->replied_at)->diffForHumans() }}</span>
                    @endif
                  </div>
                  <div>{{ $rv->reply_text }}</div>
                </div>
              @endif
            </div>
          </div>
        @empty
          <div class="text-muted">لا توجد تعليقات حتى الآن.</div>
        @endforelse
        @if($hasMoreReviews)
          @foreach($reviews->slice(2) as $rv)
            @php
              $u = $rv->user;
              $name = $u->name ?? 'مستخدم';
              $avatar = method_exists($u, 'getProfilePhotoUrlAttribute') ? ($u->profile_photo_url ?? null) : null;
              if (!$avatar && !empty($u->profile_photo_path)) { $avatar = Storage::url($u->profile_photo_path); }
              $fill = max(0,min(100, round(($rv->rating/5)*100)));
            @endphp
            <div class="rv-item extra-review d-none">
              <img class="rv-avatar" src="{{ $avatar ?: asset('images/default-avatar.svg') }}" alt="{{ $name }}" loading="lazy">
              <div class="rv-body">
                <div class="d-flex flex-wrap align-items-center gap-2">
                  <span class="rv-name">{{ $name }}</span>
                  <span class="rv-stars" style="--fill: {{ $fill }}%" aria-label="{{ $rv->rating }} من 5"></span>
                  <span class="text-muted small">{{ optional($rv->created_at)->diffForHumans() }}</span>
                </div>
                @if($rv->comment)
                  <div class="mt-1">{{ $rv->comment }}</div>
                @endif
                @if($rv->reply_text)
                  <div class="rv-reply mt-2">
                    <div class="d-flex align-items-center gap-2 mb-1">
                      <i class="bi bi-patch-check-fill text-success"></i>
                      <strong>رد المطوّر</strong>
                      @if($rv->replyAdmin)
                        <span class="text-muted small">— {{ $rv->replyAdmin->name }}</span>
                      @endif
                      @if($rv->replied_at)
                        <span class="text-muted small">• {{ optional($rv->replied_at)->diffForHumans() }}</span>
                      @endif
                    </div>
                    <div>{{ $rv->reply_text }}</div>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        @endif
      </div>
      @if($hasMoreReviews)
        <button class="btn-fancy btn-amber btn-lg mt-3 d-inline-flex align-items-center gap-2" id="toggleReviewsBtn" aria-expanded="false" data-step="5" style="box-shadow:0 2px 8px rgba(251,191,36,.15);font-size:1rem;">
          <i class="bi bi-chat-dots" style="font-size:1.2rem;"></i>
          <span class="label">عرض المزيد من التعليقات</span>
        </button>
      @endif
    @endisset
  </section>

  {{-- ===== أمان البيانات ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <h2 class="h5">أمان البيانات</h2>
    <p class="text-muted mb-2">ممارسات الخصوصية كما يوضحها المطوّر:</p>
    <ul class="list-icon">
      @if(!empty($app->privacy_no_share))
        <li><span class="ic"><i class="bi bi-shield-x"></i></span><span>لا تتم مشاركة البيانات مع أطراف خارجية</span></li>
      @endif
      @if(!empty($app->privacy_encrypted))
        <li><span class="ic"><i class="bi bi-shield-lock"></i></span><span>يتم تشفير البيانات أثناء النقل</span></li>
      @endif
      @if(!empty($app->privacy_delete_request))
        <li><span class="ic"><i class="bi bi-trash3"></i></span><span>يمكنك طلب حذف البيانات</span></li>
      @endif
      @if(empty($app->privacy_no_share) && empty($app->privacy_encrypted) && empty($app->privacy_delete_request))
        <li class="text-muted">لم يقدّم المطوّر تفاصيل أمان البيانات.</li>
      @endif
    </ul>
    @if($app->privacy_policy_url)
        <div class="mt-2 d-flex flex-wrap gap-2">
          <a class="btn-privacy" href="{{ $app->privacy_policy_url }}" target="_blank" rel="noopener" aria-label="افتح سياسة الخصوصية">
            <i class="bi bi-shield-check"></i>
            <span>سياسة الخصوصية</span>
          </a>
          <button class="btn-fancy btn-amber copy-btn" type="button" data-copy="{{ $app->privacy_policy_url }}" aria-label="نسخ رابط سياسة الخصوصية">
            <i class="bi bi-clipboard"></i>
            <span>نسخ الرابط</span>
          </button>
        </div>
    @endif
  </section>

  {{-- ===== معلومات إضافية ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <h2 class="h5">معلومات إضافية</h2>
    <dl class="row kv mb-0">
  <dt class="col-sm-3 col-md-2">الحجم</dt><dd class="col-sm-9 col-md-4">{{ $app->apk_size ? $app->apk_size.' MB' : '-' }}</dd>
      <dt class="col-sm-3 col-md-2">عمليات التنزيل</dt><dd class="col-sm-9 col-md-4">{{ $installsLabel }}</dd>
  <dt class="col-sm-3 col-md-2">الإصدار الحالي</dt><dd class="col-sm-9 col-md-4">{{ $app->version_name ?: '-' }}</dd>
      <dt class="col-sm-3 col-md-2">يتطلّب أندرويد</dt><dd class="col-sm-9 col-md-4">{{ $app->min_sdk ?? '-' }} وما فوق</dd>
    <dt class="col-sm-3 col-md-2">تم التحديث في</dt><dd class="col-sm-9 col-md-4">{{ !empty($app->published_at) ? optional($app->published_at)->format('d F Y') : '-' }}</dd>
  <dt class="col-sm-3 col-md-2">تم الإطلاق في</dt><dd class="col-sm-9 col-md-4">{{ !empty($app->created_at) ? optional($app->created_at)->format('d F Y') : '-' }}</dd>
  <dt class="col-sm-3 col-md-2">التطبيق من تطوير وبرمجة</dt><dd class="col-sm-9 col-md-4">
    @if($devLogoUrl)
      <img src="{{ $devLogoUrl }}" alt="شعار المطوّر {{ $app->developer_name }}" style="height:34px;width:auto;vertical-align:middle;margin-inline-end:6px;border-radius:8px;object-fit:cover">
    @endif
    {{ $app->developer_name ?? '—' }}
  </dd>
    </dl>
  </section>

  {{-- ===== التواصل مع المطوّر ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="h5 m-0">التواصل مع المطوّر</h2>
      <button class="btn-toggle-dev" id="devToggleBtn" data-bs-toggle="collapse" data-bs-target="#devContact" aria-expanded="false">
        <i class="bi bi-chevron-down"></i><span class="label">عرض</span>
      </button>
    </div>
    <div class="collapse" id="devContact">
      <ul class="list-icon mb-0 mt-2">
        @if($app->support_email)
          <li>
            <span class="ic"><i class="bi bi-envelope"></i></span>
            <span>البريد: <a href="mailto:{{ $app->support_email }}">{{ $app->support_email }}</a></span>
            <button class="copy-btn ms-auto" type="button" title="نسخ البريد" data-copy="{{ $app->support_email }}"><i class="bi bi-clipboard"></i></button>
          </li>
        @endif
        @if($app->website_url)
          <li>
            <span class="ic"><i class="bi bi-globe2"></i></span>
            <span>الموقع: <a href="{{ $app->website_url }}" target="_blank" rel="noopener">{{ parse_url($app->website_url, PHP_URL_HOST) ?: $app->website_url }}</a></span>
            <button class="copy-btn ms-auto" type="button" title="نسخ الرابط" data-copy="{{ $app->website_url }}"><i class="bi bi-clipboard"></i></button>
          </li>
        @endif
        @if($app->privacy_policy_url)
          <li>
            <span class="ic"><i class="bi bi-shield-check"></i></span>
            <span>سياسة الخصوصية: <a href="{{ $app->privacy_policy_url }}" target="_blank" rel="noopener">رابط السياسة</a></span>
            <button class="copy-btn ms-auto" type="button" title="نسخ الرابط" data-copy="{{ $app->privacy_policy_url }}"><i class="bi bi-clipboard"></i></button>
          </li>
        @endif
        @if(empty($app->support_email) && empty($app->website_url) && empty($app->privacy_policy_url))
          <li class="text-muted">لا تتوفر بيانات تواصل.</li>
        @endif
      </ul>
    </div>
  </section>

  {{-- ===== تطبيقات مشابهة (اختياري) ===== --}}
  @isset($similarApps)
  <section class="gp-card p-3 p-md-4 mb-5">
    <h2 class="h5">قد تُعجبك أيضًا</h2>
    @php $saCount = $similarApps->count(); $initialVisible = 8; @endphp
    <div class="d-flex gap-3 overflow-auto pb-2" id="similarAppsList">
      @foreach($similarApps as $idx => $sa)
        @php $saIcon = $sa->icon_path ? Storage::url($sa->icon_path) : asset('images/default-app-icon.png'); @endphp
        <div class="app-card text-center @if($idx >= $initialVisible) hidden-app d-none @endif">
          <img class="icon" src="{{ $saIcon }}" alt="{{ $sa->name }}">
          <div class="mt-2 fw-semibold small text-truncate" title="{{ $sa->name }}">{{ $sa->name }}</div>
          <div class="text-muted small">{{ number_format($sa->rating ?? 0,1) }} ★</div>
          <a href="{{ route('apps.show',$sa->slug) }}" class="stretched-link" aria-label="افتح {{ $sa->name }}"></a>
        </div>
      @endforeach
    </div>
    @if($saCount > $initialVisible)
      @php $remain = $saCount - $initialVisible; @endphp
      <div class="mt-3 text-center">
        <button id="moreAppsBtn" class="btn-more-apps" type="button" data-step="6" aria-expanded="false">
          <i class="bi bi-grid"></i>
          <span class="label">عرض المزيد من التطبيقات ({{ $remain }})</span>
        </button>
      </div>
    @endif
  </section>
  @endisset

</div>

{{-- شريط تثبيت جوّال ثابت --}}
<div class="sticky-install d-md-none">
  <div class="d-flex align-items-center gap-3 px-2">
    <img class="rounded" src="{{ $iconUrl }}" alt="أيقونة" style="width:48px;height:48px;border-radius:14%">
    <div class="flex-grow-1">
  <div class="fw-bold text-truncate">{{ $app->name }}</div>
  <div class="text-muted small">{{ number_format($rating,1) }} ★ • {{ $installsLabel }}</div>
    </div>
    <a class="btn btn-install py-2 px-3" href="{{ route('apps.download', $app->slug) }}">تثبيت</a>
  </div>
</div>

{{-- Modal لمعاينة اللقطة --}}
<div class="modal fade" id="shotModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content" style="background:#000">
      <img id="shotPreview" alt="لقطة شاشة" style="width:100%;height:auto;display:block" />
    </div>
  </div>
</div>

@if($embed)
{{-- Modal لمشاهدة فيديو التطبيق --}}
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content" style="background:#000">
      <button type="button" class="btn btn-sm btn-light position-absolute" style="top:8px;right:8px;z-index:2" data-bs-dismiss="modal" aria-label="إغلاق الفيديو">
        <i class="bi bi-x-lg"></i>
      </button>
      <div class="ratio ratio-16x9">
        <iframe id="videoFrame" src="" allow="autoplay; encrypted-media" allowfullscreen title="فيديو {{ $app->name }}" style="border:0"></iframe>
      </div>
    </div>
  </div>
  </div>
@endif
@endsection

@push('scripts')
<script>
  // فتح اللقطات داخل مودال أسود بسيط عند الضغط على عناصر .shot
  document.querySelectorAll('.shot').forEach(btn=>{
    btn.addEventListener('click',()=>{
      const src = btn.getAttribute('data-img');
      const modalEl = document.getElementById('shotModal');
      modalEl.querySelector('#shotPreview').src = src;
      bootstrap.Modal.getOrCreateInstance(modalEl).show();
    });
  });

  // بلاي تايل للفيديو داخل شريط اللقطات
  const videoTile = document.getElementById('videoTile');
  if(videoTile){
    videoTile.addEventListener('click',()=>{
      const watchBtn = document.getElementById('watchVideoBtn');
      if(watchBtn){ watchBtn.click(); }
    });
  }

  // زر عرض المزيد المحسن للوصف الطويل (مستبدل للمنطق القديم لتفادي تكرار المستمع والتراجع المزدوج)
  const toggleAbout = document.getElementById('toggleAbout');
  const aboutLong = document.getElementById('aboutLong');
  const aboutWrapper = document.getElementById('aboutWrapper');
  if(toggleAbout && aboutWrapper && aboutLong){
    // قياس الارتفاع الكامل مرة واحدة بعد أن يرسم المحتوى
    requestAnimationFrame(()=>{
      const wasClamped = aboutWrapper.classList.contains('clamped');
      const hadClamp = aboutLong.classList.contains('clamp');
      // فك القيود مؤقتًا لقياس الارتفاع الكامل الحقيقي
      aboutWrapper.classList.remove('clamped');
      aboutLong.classList.remove('clamp');
      const fullHeight = aboutLong.scrollHeight;
      aboutWrapper.dataset.fullHeight = String(fullHeight);
      // إعادة الحالة الأصلية
      if(wasClamped) aboutWrapper.classList.add('clamped');
      if(hadClamp) aboutLong.classList.add('clamp');
      // اضبط max-height الابتدائي على الارتفاع المقصوص الفعلي لتفادي قفزات
      const clampedHeight = aboutWrapper.getBoundingClientRect().height;
      aboutWrapper.style.maxHeight = clampedHeight + 'px';
    });
    toggleAbout.addEventListener('click',()=>{
      const expanded = toggleAbout.getAttribute('aria-expanded')==='true';
      toggleAbout.setAttribute('aria-expanded', String(!expanded));
      const labelSpan = toggleAbout.querySelector('.label');
      if(labelSpan){ labelSpan.textContent = expanded ? 'عرض المزيد' : 'عرض أقل'; }
      if(expanded){
        // Collapse: من كامل إلى مقصوص
        const startH = aboutWrapper.getBoundingClientRect().height;
        aboutWrapper.style.maxHeight = startH + 'px';
        // اجبر إعادة تدفق
        void aboutWrapper.offsetHeight;
        aboutWrapper.classList.add('clamped');
        aboutLong.classList.add('clamp');
        const endH = aboutWrapper.getBoundingClientRect().height;
        aboutWrapper.style.maxHeight = endH + 'px';
      } else {
        // Expand: من مقصوص إلى كامل
        const startH = aboutWrapper.getBoundingClientRect().height;
        aboutWrapper.style.maxHeight = startH + 'px';
        void aboutWrapper.offsetHeight;
        aboutWrapper.classList.remove('clamped');
        aboutLong.classList.remove('clamp');
        const fullHeight = Number(aboutWrapper.dataset.fullHeight) || aboutLong.scrollHeight;
        aboutWrapper.style.maxHeight = fullHeight + 'px';
      }
    });
  }

  // زر المشاركة (Web Share API مع نسخة احتياطية لنسخ الرابط)
  const shareBtn = document.getElementById('shareBtn');
  if(shareBtn){
    shareBtn.addEventListener('click', async ()=>{
      const data = { title: document.title, text: 'جرّب هذا التطبيق', url: location.href };
      try{ if(navigator.share){ await navigator.share(data); } else { await navigator.clipboard.writeText(location.href); alert('تم نسخ الرابط'); } }
      catch(e){ /* تجاهل */ }
    });
  }

  // زر مشاهدة الفيديو داخل مودال
  const watchVideoBtn = document.getElementById('watchVideoBtn');
  const videoModalEl = document.getElementById('videoModal');
  if(watchVideoBtn && videoModalEl){
    const frame = videoModalEl.querySelector('#videoFrame');
    const src = @json($embed ?? '');
    watchVideoBtn.addEventListener('click',()=>{
      if(src){
        // إضافة autoplay
        try {
          const url = new URL(src);
          url.searchParams.set('autoplay','1');
          url.searchParams.set('rel','0');
          frame.src = url.toString();
        } catch(e){ frame.src = src + '?autoplay=1&rel=0'; }
      }
      bootstrap.Modal.getOrCreateInstance(videoModalEl).show();
    });
    videoModalEl.addEventListener('hidden.bs.modal',()=>{ frame.src=''; });
  }

  // إزالة كتلة قديمة كانت تكرر المنطق (تم استبدالها أعلاه بانتقال سلس)
  // أزرار النسخ العامة (سياسة الخصوصية + بيانات التواصل)
  document.querySelectorAll('.copy-btn[data-copy]').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
      const val = btn.getAttribute('data-copy');
      try{ await navigator.clipboard.writeText(val); btn.classList.add('text-success'); setTimeout(()=>btn.classList.remove('text-success'),1200); }
      catch(e){ alert('تعذّر النسخ'); }
    });
  });
  // تبديل نص زر عرض بيانات المطوّر
  const devToggleBtn = document.getElementById('devToggleBtn');
  const devContact = document.getElementById('devContact');
  if(devToggleBtn && devContact){
    const label = devToggleBtn.querySelector('.label');
    devContact.addEventListener('shown.bs.collapse',()=>{ if(label){ label.textContent='إخفاء'; } devToggleBtn.setAttribute('aria-expanded','true'); });
    devContact.addEventListener('hidden.bs.collapse',()=>{ if(label){ label.textContent='عرض'; } devToggleBtn.setAttribute('aria-expanded','false'); });
  }

  // زر عرض المزيد من التعليقات
  const toggleReviewsBtn = document.getElementById('toggleReviewsBtn');
  if(toggleReviewsBtn){
    const step = parseInt(toggleReviewsBtn.getAttribute('data-step'))||5;
    const hiddenReviews = Array.from(document.querySelectorAll('.extra-review.d-none'));
    const totalExtra = hiddenReviews.length;
    const updateLabel = () => {
      const remaining = document.querySelectorAll('.extra-review.d-none').length;
      if(remaining>0){
        toggleReviewsBtn.textContent = 'عرض المزيد من التعليقات ('+remaining+' متبقية)';
      } else {
        toggleReviewsBtn.textContent = 'تم عرض كل التعليقات';
        toggleReviewsBtn.disabled = true;
        toggleReviewsBtn.setAttribute('aria-expanded','true');
      }
    };
    updateLabel();
    toggleReviewsBtn.addEventListener('click',()=>{
      const remainingList = Array.from(document.querySelectorAll('.extra-review.d-none')).slice(0, step);
      remainingList.forEach(el=>el.classList.remove('d-none'));
      toggleReviewsBtn.setAttribute('aria-expanded','true');
      updateLabel();
    });
  }

  // زر عرض المزيد من التطبيقات المشابهة
  const moreAppsBtn = document.getElementById('moreAppsBtn');
  const appsList = document.getElementById('similarAppsList');
  if(moreAppsBtn && appsList){
    const step = parseInt(moreAppsBtn.getAttribute('data-step')) || 6;
    const updateAppsLabel = () => {
      const remaining = appsList.querySelectorAll('.app-card.hidden-app.d-none').length;
      const label = moreAppsBtn.querySelector('.label');
      if(remaining>0){
        if(label) label.textContent = `عرض المزيد من التطبيقات (${remaining})`;
        moreAppsBtn.disabled = false;
        moreAppsBtn.setAttribute('aria-expanded','false');
      } else {
        if(label) label.textContent = 'تم عرض كل التطبيقات';
        moreAppsBtn.disabled = true;
        moreAppsBtn.setAttribute('aria-expanded','true');
      }
    };
    updateAppsLabel();
    moreAppsBtn.addEventListener('click',()=>{
      const next = Array.from(appsList.querySelectorAll('.app-card.hidden-app.d-none')).slice(0, step);
      next.forEach(el=>{
        el.classList.remove('d-none');
        requestAnimationFrame(()=>{ el.classList.add('revealed'); });
      });
      updateAppsLabel();
    });
  }
</script>

<!-- JSON-LD داخل الجسم لعدم وجود stack في <head> بالتخطيط -->
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"SoftwareApplication",
  "name":"{{ $app->name }}",
  "applicationCategory":"{{ $app->category ?? 'BusinessApplication' }}",
  "operatingSystem":"Android",
  "offers":{"@type":"Offer","price":"{{ $app->price ?? 0 }}","priceCurrency":"{{ $app->currency ?? 'USD' }}"},
  "aggregateRating":{
    "@type":"AggregateRating",
    "ratingValue":"{{ number_format($rating,1) }}",
    "ratingCount":"{{ $reviewsCount }}"
  },
  "author":{"@type":"Organization","name":"{{ $app->developer_name ?? 'Developer' }}"}
}
</script>
@endpush

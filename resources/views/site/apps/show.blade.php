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
</style>
@endpush

@section('content')
@php
  // بيانات مساعدة
  $screens = $app->screenshots; if (is_string($screens)) $screens = json_decode($screens, true); if (!is_array($screens)) $screens = [];
  $tags = $app->tags; if (is_string($tags)) $tags = json_decode($tags, true); if (!is_array($tags)) $tags = [];
  $coverUrl = $app->feature_image_path ? Storage::url($app->feature_image_path) : ($app->icon_path ? Storage::url($app->icon_path) : asset('images/default-app-icon.png'));
  $iconUrl = $app->icon_path ? Storage::url($app->icon_path) : asset('images/default-app-icon.png');
  // يوتيوب embed
  $embed = null; if (!empty($app->video_url)) { $v = $app->video_url; if (Str::contains($v, 'youtu.be/')) { $embed = preg_replace('~https?://youtu\.be/([A-Za-z0-9_\-]+)~', 'https://www.youtube.com/embed/$1', $v); } elseif (Str::contains($v, 'watch?v=')) { $embed = preg_replace('~.*watch\?v=([A-Za-z0-9_\-]+).*~', 'https://www.youtube.com/embed/$1', $v); } elseif (Str::contains($v, '/embed/')) { $embed = $v; } }
  // تقييم
  $rating = isset($rating) ? floatval($rating) : floatval($app->rating ?? 0);
  $reviewsCount = isset($reviewsCount) ? intval($reviewsCount) : intval($app->reviews_count ?? 0);
  $ratingFill = max(0,min(100, round(($rating/5)*100)));
  // تفصيل التقييمات
  $breakdown = $breakdown ?? ($app->ratings_breakdown ?? null); // مثال: [5=>80,4=>12,3=>5,2=>2,1=>1]
  // التنزيلات
  $installsLabel = $app->installs_label ?? (($app->downloads_total ?? 0) > 0 ? number_format($app->downloads_total).'+' : '—');
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

  {{-- ===== صورة الغلاف (Feature Image) ===== --}}
  @if(!empty($app->feature_image_path))
    <section class="gp-card p-0 overflow-hidden mb-3">
      <img class="cover-img" src="{{ Storage::url($app->feature_image_path) }}" alt="صورة الغلاف {{ $app->name }}" loading="lazy">
    </section>
  @endif

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
            <span class="badge rounded-pill text-bg-light">الفئة: {{ $app->category }}</span>
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
    @if(count($screens))
      <div class="shots-scroll" id="shots">
        @foreach($screens as $i=>$s)
          @php $url = Storage::url($s); @endphp
          <button class="shot" data-img="{{ $url }}" aria-label="لقطة {{ $i+1 }}"><img src="{{ $url }}" alt="لقطة شاشة {{ $i+1 }}" loading="lazy"></button>
        @endforeach
      </div>
    @else
      <div class="text-muted">لا توجد لقطات شاشة متاحة.</div>
    @endif
  </section>

  {{-- ===== ما الجديد ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h2 class="h5 m-0">ما الجديد</h2>
      <span class="text-muted small">
        @if(!empty($app->updated_at)) آخر تحديث: {{ optional($app->updated_at)->format('Y-m-d') }} • @endif
        الإصدار {{ $app->version_name ?? '-' }}@if($app->version_code) ({{ $app->version_code }}) @endif
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
      <div class="clamp" id="aboutLong">{!! nl2br(e($app->long_description)) !!}</div>
    @endif
    <button class="btn btn-link p-0 mt-2" id="toggleAbout" aria-expanded="false">عرض المزيد</button>
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
      <div class="vstack gap-3">
        @forelse($reviews as $rv)
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
      </div>
    @endisset
  </section>

  {{-- ===== أمان البيانات ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <h2 class="h5">أمان البيانات</h2>
    <p class="text-muted mb-2">ممارسات الخصوصية كما يوضحها المطوّر:</p>
    <div class="d-flex flex-wrap gap-2">
      @if(!empty($app->privacy_no_share))<span class="gp-chip">لا تتم مشاركة البيانات مع أطراف خارجية</span>@endif
      @if(!empty($app->privacy_encrypted))<span class="gp-chip">يتم تشفير البيانات أثناء النقل</span>@endif
      @if(!empty($app->privacy_delete_request))<span class="gp-chip">يمكنك طلب حذف البيانات</span>@endif
    </div>
    @if($app->privacy_policy_url)
      <a class="d-block mt-2" href="{{ $app->privacy_policy_url }}" target="_blank" rel="noopener">اطّلع على سياسة الخصوصية</a>
    @endif
  </section>

  {{-- ===== معلومات إضافية ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <h2 class="h5">معلومات إضافية</h2>
    <dl class="row kv mb-0">
  <dt class="col-sm-3 col-md-2">الحجم</dt><dd class="col-sm-9 col-md-4">{{ $app->apk_size ? $app->apk_size.' MB' : '-' }}</dd>
      <dt class="col-sm-3 col-md-2">عمليات التنزيل</dt><dd class="col-sm-9 col-md-4">{{ $installsLabel }}</dd>
  <dt class="col-sm-3 col-md-2">الإصدار الحالي</dt><dd class="col-sm-9 col-md-4">{{ $app->version_name ? $app->version_name.( $app->version_code ? ' ('.$app->version_code.')' : '' ) : '-' }}</dd>
      <dt class="col-sm-3 col-md-2">يتطلّب أندرويد</dt><dd class="col-sm-9 col-md-4">{{ $app->min_sdk ?? '-' }} وما فوق</dd>
      <dt class="col-sm-3 col-md-2">تم التحديث في</dt><dd class="col-sm-9 col-md-4">{{ !empty($app->updated_at) ? optional($app->updated_at)->format('d F Y') : '-' }}</dd>
  <dt class="col-sm-3 col-md-2">تم الإطلاق في</dt><dd class="col-sm-9 col-md-4">{{ !empty($app->published_at) ? optional($app->published_at)->format('d F Y') : '-' }}</dd>
  <dt class="col-sm-3 col-md-2">التطبيق من تطوير وبرمجة</dt><dd class="col-sm-9 col-md-4">{{ $app->developer_name ?? '—' }}</dd>
    </dl>
  </section>

  {{-- ===== التواصل مع المطوّر ===== --}}
  <section class="gp-card p-3 p-md-4 mb-3">
    <div class="d-flex justify-content-between align-items-center">
      <h2 class="h5 m-0">التواصل مع المطوّر</h2>
      <button class="btn btn-link p-0" data-bs-toggle="collapse" data-bs-target="#devContact" aria-expanded="false">عرض</button>
    </div>
    <div class="collapse" id="devContact">
      <ul class="list-unstyled mb-0 mt-2">
        @if($app->support_email)<li>البريد: <a href="mailto:{{ $app->support_email }}">{{ $app->support_email }}</a></li>@endif
        @if($app->website_url)<li>الموقع: <a href="{{ $app->website_url }}" target="_blank" rel="noopener">{{ parse_url($app->website_url, PHP_URL_HOST) ?: $app->website_url }}</a></li>@endif
        @if($app->privacy_policy_url)<li>سياسة الخصوصية: <a href="{{ $app->privacy_policy_url }}" target="_blank" rel="noopener">رابط السياسة</a></li>@endif
      </ul>
    </div>
  </section>

  {{-- ===== تطبيقات مشابهة (اختياري) ===== --}}
  @isset($similarApps)
  <section class="gp-card p-3 p-md-4 mb-5">
    <h2 class="h5">قد تُعجبك أيضًا</h2>
    <div class="d-flex gap-3 overflow-auto pb-2">
      @foreach($similarApps as $sa)
        @php $saIcon = $sa->icon_path ? Storage::url($sa->icon_path) : asset('images/default-app-icon.png'); @endphp
        <div class="app-card text-center">
          <img class="icon" src="{{ $saIcon }}" alt="{{ $sa->name }}">
          <div class="mt-2 fw-semibold small text-truncate" title="{{ $sa->name }}">{{ $sa->name }}</div>
          <div class="text-muted small">{{ number_format($sa->rating ?? 0,1) }} ★</div>
          <a href="{{ route('apps.show',$sa->slug) }}" class="stretched-link" aria-label="افتح {{ $sa->name }}"></a>
        </div>
      @endforeach
    </div>
  </section>
  @endisset

</div>

{{-- شريط تثبيت جوّال ثابت --}}
<div class="sticky-install d-md-none">
  <div class="d-flex align-items-center gap-3 px-2">
    <img class="rounded" src="{{ $iconUrl }}" alt="أيقونة" style="width:48px;height:48px;border-radius:14%">
    <div class="flex-grow-1">
      <div class="fw-bold text-truncate">{{ $app->name }}</div>
      <div class="text-muted small">{{ number_format($rating,1) }} ★ • +{{ $installsLabel }}</div>
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

  // توسيع/طي وصف "عن هذا التطبيق"
  const toggleAbout = document.getElementById('toggleAbout');
  const aboutText = document.getElementById('aboutText');
  const aboutLong = document.getElementById('aboutLong');
  if(toggleAbout){
    toggleAbout.addEventListener('click',()=>{
      const expanded = toggleAbout.getAttribute('aria-expanded')==='true';
      toggleAbout.setAttribute('aria-expanded', String(!expanded));
      [aboutText, aboutLong].forEach(el=>{ if(el) el.classList.toggle('clamp'); });
      toggleAbout.textContent = expanded ? 'عرض المزيد' : 'عرض أقل';
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

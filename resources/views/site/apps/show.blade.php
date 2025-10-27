@extends('site.layouts.app')

@section('title', $app->name)

@push('styles')
<style>
/* ===== Facebook-like Profile (RTL, Bootstrap 5.3.3) ===== */
:root{
  --cover-h-sm: 220px;
  --cover-h-lg: 300px;
  --avatar: 128px;
  --avatar-sm: 96px;
}
.profile-cover{
  position:relative; overflow:hidden; border-radius: .75rem;
  min-height:var(--cover-h-sm);
}
@media (min-width:992px){ .profile-cover{ min-height:var(--cover-h-lg);} }
.profile-cover .bg{
  position:absolute; inset:0; background-size:cover; background-position:center;
  filter:brightness(.6);
}
.profile-cover .overlay{
  position:absolute; inset:0;
  background:linear-gradient(180deg, rgba(0,0,0,.10), rgba(0,0,0,.55));
}
.profile-head{
  position:relative; z-index:2; padding:1rem 1rem 0 1rem; color:#fff;
  display:flex; align-items:flex-end; min-height:calc(var(--cover-h-sm) - 24px);
}
@media (min-width:992px){
  .profile-head{ min-height:calc(var(--cover-h-lg) - 24px); padding:1.25rem 1.25rem 0 1.25rem;}
}
.avatar-wrap{
  position:absolute; inset-inline-start:1rem; bottom:-32px; /* يبرز خارج الغلاف */
}
@media (min-width:992px){ .avatar-wrap{ bottom:-40px; } }
.avatar{
  width:var(--avatar-sm); height:var(--avatar-sm); object-fit:cover;
  border-radius:50%; border:4px solid #fff; box-shadow:0 10px 25px rgba(0,0,0,.35);
}
@media (min-width:992px){ .avatar{ width:var(--avatar); height:var(--avatar);} }
.page-title{
  margin-inline-start:calc(var(--avatar-sm) + 1.25rem);
}
@media (min-width:992px){ .page-title{ margin-inline-start:calc(var(--avatar) + 1.25rem);} }
.cta-bar{
  display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;
}
.sticky-subnav{
  position:sticky; top:0; z-index:1020; /* تحت النافبار الرئيسي إن وجد */
  background:#fff; border-radius:.75rem; box-shadow:0 4px 14px rgba(0,0,0,.06);
}
.sticky-subnav .nav-link{ color:#111; }
.sticky-subnav .nav-link.active{
  font-weight:600; border-bottom:2px solid var(--bs-primary);
}
.badge-tag{ background:#f1f3f5; color:#000; }
.meta-dt{ color:var(--bs-secondary-color); }
.sshot{ width:100%; height:480px; object-fit:cover; border-radius:.5rem; }
.sshot-thumb{ height:84px; width:150px; object-fit:cover; border-radius:.5rem; cursor:pointer; }
</style>
@endpush

@section('content')
@php
    // ضمان التعامل مع JSON
    $screens = $app->screenshots;
    if (is_string($screens)) $screens = json_decode($screens, true);
    if (!is_array($screens)) $screens = [];

    $tags = $app->tags;
    if (is_string($tags)) $tags = json_decode($tags, true);
    if (!is_array($tags)) $tags = [];

    $coverUrl = $app->feature_image_path
        ? Storage::url($app->feature_image_path)
        : ($app->icon_path ? Storage::url($app->icon_path) : asset('images/default-app-icon.png'));

    $iconUrl = $app->icon_path ? Storage::url($app->icon_path) : asset('images/default-app-icon.png');

    // يوتيوب embed
    $embed = null;
    if (!empty($app->video_url)) {
        $v = $app->video_url;
        if (Str::contains($v, 'youtu.be/')) {
            $embed = preg_replace('~https?://youtu\.be/([A-Za-z0-9_\-]+)~', 'https://www.youtube.com/embed/$1', $v);
        } elseif (Str::contains($v, 'watch?v=')) {
            $embed = preg_replace('~.*watch\?v=([A-Za-z0-9_\-]+).*~', 'https://www.youtube.com/embed/$1', $v);
        } elseif (Str::contains($v, '/embed/')) {
            $embed = $v;
        }
    }
@endphp

<div class="container" dir="rtl">
  <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">

      {{-- ===== Header like Facebook ===== --}}
      <div id="top" class="profile-cover mb-5">
        <div class="bg" style="background-image:url('{{ $coverUrl }}')"></div>
        <div class="overlay"></div>

        <div class="profile-head">
          <div class="page-title w-100">
            <h1 class="h3 fw-bold mb-1">{{ $app->name }}</h1>
            <div class="small opacity-75 mb-3">{{ $app->short_description }}</div>
            <div class="cta-bar">
              <a href="{{ route('apps.download', $app->slug) }}" class="btn btn-primary">
                <i class="bi bi-download me-1"></i> تحميل
              </a>
              @if($app->apk_file_path)
              <a class="btn btn-outline-light border" href="{{ Storage::url($app->apk_file_path) }}" target="_blank" rel="noopener">
                ملف APK
              </a>
              @endif
              <span class="text-white-50 small">
                <i class="bi bi-people-fill me-1"></i>{{ number_format($app->downloads_total ?? 0) }} تحميل
              </span>
            </div>
          </div>

          <div class="avatar-wrap">
            <img src="{{ $iconUrl }}" alt="{{ $app->name }}" class="avatar" loading="lazy">
          </div>
        </div>
      </div>

      {{-- ===== Sticky Subnav (Facebook-like tabs) ===== --}}
      <div class="sticky-subnav mb-4 px-2 px-md-3" id="subnav">
        <ul class="nav nav-pills flex-nowrap overflow-auto" id="profileTabs">
          <li class="nav-item"><a class="nav-link active" href="#about">نظرة عامة</a></li>
          <li class="nav-item"><a class="nav-link" href="#screens">لقطات الشاشة</a></li>
          <li class="nav-item"><a class="nav-link" href="#video">الفيديو</a></li>
          <li class="nav-item"><a class="nav-link" href="#details">تفاصيل التطبيق</a></li>
          <li class="nav-item"><a class="nav-link" href="#dev">المطوّر</a></li>
        </ul>
      </div>

      {{-- ===== Section: About ===== --}}
      <section id="about" class="mb-4" data-bs-spy="scroll">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-2">نبذة سريعة</h5>
            <p class="text-secondary">{{ $app->short_description }}</p>
            @if($app->long_description)
              <hr>
              <h5 class="mb-2">الوصف الكامل</h5>
              <div class="text-body">{!! nl2br(e($app->long_description)) !!}</div>
            @endif
          </div>
        </div>
      </section>

      {{-- ===== Section: Screenshots ===== --}}
      <section id="screens" class="mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h5 class="mb-0">لقطات الشاشة</h5>
              @if(count($screens))<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#galleryModal">عرض بالحجم الكامل</button>@endif
            </div>

            @if(count($screens))
              <div id="appCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                  @foreach($screens as $i => $s)
                    <div class="carousel-item @if($i===0) active @endif">
                      <img src="{{ Storage::url($s) }}" class="d-block w-100 sshot" alt="شاشة {{ $i+1 }}" loading="lazy">
                    </div>
                  @endforeach
                </div>
                @if(count($screens) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#appCarousel" data-bs-slide="prev" aria-label="السابق">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#appCarousel" data-bs-slide="next" aria-label="التالي">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
                @endif
              </div>

              <div class="d-flex flex-wrap justify-content-center gap-2">
                @foreach($screens as $i => $s)
                  <img src="{{ Storage::url($s) }}" class="sshot-thumb" data-bs-target="#appCarousel" data-bs-slide-to="{{ $i }}" alt="thumb-{{ $i }}" loading="lazy">
                @endforeach
              </div>

              {{-- Modal --}}
              <div class="modal fade" id="galleryModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h6 class="modal-title">{{ $app->name }} — معرض الصور</h6>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body">
                      <div id="appCarouselFs" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded">
                          @foreach($screens as $i => $s)
                            <div class="carousel-item @if($i===0) active @endif">
                              <img src="{{ Storage::url($s) }}" class="d-block w-100" style="max-height:75vh; object-fit:contain" alt="شاشة {{ $i+1 }}">
                            </div>
                          @endforeach
                        </div>
                        @if(count($screens) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#appCarouselFs" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#appCarouselFs" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @else
              <div class="text-secondary">لا توجد لقطات شاشة.</div>
            @endif
          </div>
        </div>
      </section>

      {{-- ===== Section: Video ===== --}}
      <section id="video" class="mb-4">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">الفيديو</h5>
            @if($embed)
              <div class="ratio ratio-16x9">
                <iframe src="{{ $embed }}" title="فيديو {{ $app->name }}" allowfullscreen loading="lazy"></iframe>
              </div>
            @else
              <div class="text-secondary">لا يوجد فيديو توضيحي.</div>
            @endif
          </div>
        </div>
      </section>

      {{-- ===== Section: Details ===== --}}
      <section id="details" class="mb-4">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">تفاصيل التطبيق</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between"><span class="meta-dt">الإصدار</span><span>{{ $app->version_name ?? '-' }} @if($app->version_code) ({{ $app->version_code }}) @endif</span></li>
                  <li class="list-group-item d-flex justify-content-between"><span class="meta-dt">الحجم</span><span>{{ $app->apk_size ?? '-' }}</span></li>
                  <li class="list-group-item d-flex justify-content-between"><span class="meta-dt">الحد الأدنى</span><span>{{ $app->min_sdk ?? '-' }}</span></li>
                  <li class="list-group-item d-flex justify-content-between"><span class="meta-dt">الهدف</span><span>{{ $app->target_sdk ?? '-' }}</span></li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between"><span class="meta-dt">التصنيف</span><span>{{ $app->category ?? '-' }}</span></li>
                  <li class="list-group-item">
                    <div class="meta-dt mb-1">الوسوم</div>
                    @if(count($tags))
                      @foreach($tags as $t)<span class="badge badge-tag me-1 mb-1">{{ $t }}</span>@endforeach
                    @else <span>-</span> @endif
                  </li>
                  <li class="list-group-item">
                    <div class="d-flex flex-wrap gap-2">
                      @if($app->website_url)<a href="{{ $app->website_url }}" class="btn btn-sm btn-outline-primary" target="_blank">الموقع الرسمي</a>@endif
                      @if($app->privacy_policy_url)<a href="{{ $app->privacy_policy_url }}" class="btn btn-sm btn-outline-secondary" target="_blank">سياسة الخصوصية</a>@endif
                      @if($app->support_email)<a href="mailto:{{ $app->support_email }}" class="btn btn-sm btn-outline-success">الدعم</a>@endif
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            @if($app->changelog)
              <hr class="my-3">
              <h6 class="mb-2">سجل التغييرات</h6>
              <pre class="mb-0 small" style="white-space:pre-wrap">{{ trim($app->changelog) }}</pre>
            @endif
          </div>
        </div>
      </section>

      {{-- ===== Section: Developer ===== --}}
      <section id="dev" class="mb-5">
        <div class="card">
          <div class="card-body d-flex align-items-center gap-3">
            @if($app->developer_logo)
              <img src="{{ Storage::url($app->developer_logo) }}" alt="{{ $app->developer_name }}" style="height:72px; width:72px; object-fit:contain; border-radius:50%; border:1px solid #eee;" loading="lazy">
            @endif
            <div class="flex-grow-1">
              <div class="fw-semibold">{{ $app->developer_name ?? '-' }}</div>
              @if(!empty($app->developer_bio))<div class="small text-secondary mt-1">{{ $app->developer_bio }}</div>@endif
            </div>
            @if($app->website_url)
              <a href="{{ $app->website_url }}" target="_blank" class="btn btn-outline-dark btn-sm"><i class="bi bi-link-45deg me-1"></i> موقع المطوّر</a>
            @endif
          </div>
        </div>
      </section>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // thumbs -> main carousel
  document.querySelectorAll('.sshot-thumb').forEach(function(el){
    el.addEventListener('click', function(){
      var idx = this.getAttribute('data-bs-slide-to');
      var carousel = document.getElementById('appCarousel');
      if (carousel) bootstrap.Carousel.getOrCreateInstance(carousel).to(parseInt(idx,10));
    });
  });

  // Scrollspy-like active state for subnav
  const sections = ['about','screens','video','details','dev'];
  const links = sections.map(id => [id, document.querySelector(`a[href="#${id}"]`)]);
  const opts = { rootMargin: '-40% 0px -50% 0px', threshold: 0.01 };
  const io = new IntersectionObserver((entries)=>{
    entries.forEach(e=>{
      if(e.isIntersecting){
        links.forEach(([id, a])=>a && a.classList.toggle('active', '#'+id === '#'+e.target.id));
      }
    });
  }, opts);
  sections.forEach(id => {
    const el = document.getElementById(id);
    if (el) io.observe(el);
  });
});
</script>
@endpush

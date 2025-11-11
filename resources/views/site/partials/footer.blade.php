<footer class="border-top py-3">
  <div class="container text-muted small d-flex justify-content-between">
    <span>© {{ date('Y') }} —  المنهج الاكاديمي</span>
    @if($currentUniversity)
      <span>{{ $currentUniversity->address }} — {{ $currentUniversity->phone }}</span>
    @endif
  </div>
</footer>

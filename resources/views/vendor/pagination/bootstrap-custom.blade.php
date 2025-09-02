@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">

            {{-- زر "الأول" --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="الأول">&laquo;</a>
                </li>
            @endif

            {{-- زر "السابق" --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="السابق">&lsaquo;</a>
                </li>
            @endif

            {{-- أرقام الصفحات --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- زر "التالي" --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="التالي">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
            @endif

            {{-- زر "الأخير" --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="الأخير">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            @endif
        </ul>
    </nav>

    {{-- عرض عدد النتائج --}}
    @php
        $from = ($paginator->currentPage() - 1) * $paginator->perPage() + 1;
        $to   = min($paginator->currentPage() * $paginator->perPage(), $paginator->total());
    @endphp
    <div class="text-center text-muted small">
        عرض {{ $from }} إلى {{ $to }} من أصل {{ $paginator->total() }} نتيجة
    </div>
@endif

@if ($paginator->hasPages())
    <nav class="pager" aria-label="صفحه‌ها">
        @if ($paginator->onFirstPage())
            <span class="pager-off">صفحهٔ قبل</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">صفحهٔ قبل</a>
        @endif
        <span class="pager-pos">صفحهٔ {{ \App\Support\Persian::digits($paginator->currentPage()) }} از {{ \App\Support\Persian::digits($paginator->lastPage()) }}</span>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">صفحهٔ بعد</a>
        @else
            <span class="pager-off">صفحهٔ بعد</span>
        @endif
    </nav>
@endif

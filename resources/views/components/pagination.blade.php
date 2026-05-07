@if ($paginator->hasPages())
<div class="pagination-wrapper">

    <div class="pagination-info" id="pagination-info">
        Menampilkan
        <strong>{{ $paginator->firstItem() }}</strong>
        –
        <strong>{{ $paginator->lastItem() }}</strong>
        dari
        <strong>{{ $paginator->total() }}</strong>
        data
    </div>

    <nav>
        <ul class="pagination" id="pagination">

            {{-- PREVIOUS --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <button
                    class="page-link"
                    data-page="{{ $paginator->currentPage() - 1 }}"
                    {{ $paginator->onFirstPage() ? 'disabled' : '' }}>
                    ‹
                </button>
            </li>

            {{-- PAGE NUMBERS --}}
            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link dots">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                            <button
                                class="page-link"
                                data-page="{{ $page }}">
                                {{ $page }}
                            </button>
                        </li>
                    @endforeach
                @endif

            @endforeach

            {{-- NEXT --}}
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <button
                    class="page-link"
                    data-page="{{ $paginator->currentPage() + 1 }}"
                    {{ $paginator->hasMorePages() ? '' : 'disabled' }}>
                    ›
                </button>
            </li>

        </ul>
    </nav>

    <!-- JUMP PAGE -->
    <div class="d-flex gap-2 align-items-center mt-2">
        <span class="text-muted small">Ke halaman:</span>
        <input
            type="number"
            min="1"
            class="form-control form-control-sm"
            id="jumpPage"
            style="width:90px"
            placeholder="cth: 5"
        >
        <button class="btn btn-sm btn-primary" id="jumpBtn">
            Go
        </button>
    </div>

</div>
@endif

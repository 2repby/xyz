@if ($paginator->hasPages())
    <div class="d-flex justify-content-between mb-3">
    <nav>
        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif

        </ul>

    </nav>
        <!-- Форма для выбора количества элементов -->
        <form method="get" class="d-flex align-items-center" action={{url('item')}} >
            <label for="itemsPerPage" class="me-2 mb-0">Элементов на странице:</label>
            <select name="perpage" class="form-select w-auto me-2">
                <option value="2" @if($paginator->perPage() == 2) selected @endif >2</option>
                <option value="3" @if($paginator->perPage() == 3) selected @endif >3</option>
                <option value="4" @if($paginator->perPage() == 4) selected @endif >4</option>
            </select>
            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
@endif

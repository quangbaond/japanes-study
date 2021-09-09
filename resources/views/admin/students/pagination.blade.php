@if ($teacherReview->hasPages())
    <!-- Pagination -->
    <div class="pull-right pagination">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($teacherReview->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-angle-double-left"></i></span>
                </li>
            @else
                <li>
                    <a href="{{ $teacherReview->previousPageUrl() }}">
                        <span class="page-link"><i class="fas fa-angle-double-left"></i></span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $teacherReview->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @elseif (($page == $teacherReview->currentPage() + 1 || $page == $teacherReview->currentPage() + 2) || $page == $teacherReview->lastPage())
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @elseif ($page == $teacherReview->lastPage() - 1)
                            <li class="page-item disabled"><span class="page-link"><i class="fas fa-ellipsis-h"></i></span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($teacherReview->hasMorePages())
                <li class="page-item">
                    <a href="{{ $teacherReview->nextPageUrl() }}">
                        <span class="page-link"><i class="fas fa-angle-double-right"></i></span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-angle-double-right"></i></span>
                </li>
            @endif
        </ul>
    </div>
    <!-- Pagination -->
@endif
{{--<span class="page-link"><i class="fas fa-angle-double-right"></i></span>--}}

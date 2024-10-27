@if ($paginator->hasPages())
    <ul class="flex-c-m flex-w w-full p-t-38">

        @if (!$paginator->onFirstPage())
            <li wire:click='previousPage'
                class="flex-c-m how-pagination1 trans-04 m-all-7 pointer">
                <i class="zmdi zmdi-chevron-left"></i>
            </li>
        @endif


        @foreach ($elements as $element)

            @if (is_string($element))
                <li class="flex-c-m how-pagination1 trans-04 m-all-7 disabled">
                    <span>{{ $element }}</span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="flex-c-m how-pagination1 trans-04 m-all-7 active-pagination1">
                            {{ $page }}
                        </li>
                    @else
                        <li wire:click='gotoPage({{$page}})'
                            class="flex-c-m how-pagination1 trans-04 m-all-7 pointer">
                            {{ $page }}
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li wire:click='nextPage'
                class="flex-c-m how-pagination1 trans-04 m-all-7 pointer">
                <i class="zmdi zmdi-chevron-right"></i>
            </li>
        @endif
    </ul>
@endif

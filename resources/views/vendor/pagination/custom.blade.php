@if ($paginator->hasPages())
    <ul style="display:flex; list-style:none; padding:0; margin:0;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li style="margin:0 5px;"><span style="color:#bbb;">&lt;</span></li>
        @else
            <li style="margin:0 5px;"><a href="{{ $paginator->previousPageUrl() }}">&lt;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li style="margin:0 5px;"><span style="color:#bbb;">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li style="margin:0 5px;"><span style="background:#000;color:#fff;padding:3px 10px;border-radius:4px;">{{ $page }}</span></li>
                    @else
                        <li style="margin:0 5px;"><a href="{{ $url }}" style="padding:3px 10px;text-decoration:none;color:#333;background:#f1f1f1;border-radius:4px;">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li style="margin:0 5px;"><a href="{{ $paginator->nextPageUrl() }}">&gt;</a></li>
        @else
            <li style="margin:0 5px;"><span style="color:#bbb;">&gt;</span></li>
        @endif
    </ul>
@endif

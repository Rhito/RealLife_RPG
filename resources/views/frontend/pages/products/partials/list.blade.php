<div class="commodity-content-list"
     style="display:grid; grid-template-columns:repeat(5,1fr); gap:12px;">
    @if($products->count())
        @foreach($products as $item)
            @php
                $url = CommonHelper::writeUrl($item->slug, true, true);
            @endphp
            <div class="pro-container">
                <a href="{{ $url }}" style="text-decoration: none; color: inherit;">
                    <div class="product">
                        <div>
                            <div class="poster">
                                <img src="{{ CommonHelper::getUrlImageThumb($item->image) }}">
                            </div>
                            <h2>{{ CommonHelper::convertPrice($item->price) }}</h2>
                            <div class="product-res">
                                {{ __('lang.Sold') }} {{ $totalSold->get((string)$item->id, 0) }}
                            </div>
                            <p>{!! $item->name !!}</p>
                        </div>
                        <div class="product-footer">
                            <div><i class="el-icon-shopping-cart-full"></i>
                                <span class="buy-btn">{{ __('lang.Purchase_Now') }}</span>
                            </div>
                            <div data-product-id="{{$item->id }}" class="favorite"><i
                                        class="el-icon-star-off"></i></div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @else
        <div style="margin-left:200px; text-align: center; padding: 50px; font-weight: bold; color: #555;">
            <div class="el-empty__image">
                <svg viewBox="0 0 79 86" version="1.1" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <linearGradient id="linearGradient-1-1" x1="38.8503086%" y1="0%" x2="61.1496914%" y2="100%">
                            <stop stop-color="#FCFCFD" offset="0%"></stop>
                            <stop stop-color="#EEEFF3" offset="100%"></stop>
                        </linearGradient>
                        <linearGradient id="linearGradient-2-1" x1="0%" y1="9.5%" x2="100%" y2="90.5%">
                            <stop stop-color="#FCFCFD" offset="0%"></stop>
                            <stop stop-color="#E9EBEF" offset="100%"></stop>
                        </linearGradient>
                        <rect id="path-3-1" x="0" y="0" width="17" height="36"></rect>
                    </defs>
                    <g id="Illustrations" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="B-type" transform="translate(-1268.000000, -535.000000)">
                            <g id="Group-2" transform="translate(1268.000000, 535.000000)">
                                <path id="Oval-Copy-2"
                                      d="M39.5,86 C61.3152476,86 79,83.9106622 79,81.3333333 C79,78.7560045 57.3152476,78 35.5,78 C13.6847524,78 0,78.7560045 0,81.3333333 C0,83.9106622 17.6847524,86 39.5,86 Z"
                                      fill="#F7F8FC"></path>
                                <polygon id="Rectangle-Copy-14" fill="#E5E7E9"
                                         transform="translate(27.500000, 51.500000) scale(1, -1) translate(-27.500000, -51.500000) "
                                         points="13 58 53 58 42 45 2 45"></polygon>
                                <g id="Group-Copy"
                                   transform="translate(34.500000, 31.500000) scale(-1, 1) rotate(-25.000000) translate(-34.500000, -31.500000) translate(7.000000, 10.000000)">
                                    <polygon id="Rectangle-Copy-10" fill="#E5E7E9"
                                             transform="translate(11.500000, 5.000000) scale(1, -1) translate(-11.500000, -5.000000) "
                                             points="2.84078316e-14 3 18 3 23 7 5 7"></polygon>
                                    <polygon id="Rectangle-Copy-11" fill="#EDEEF2"
                                             points="-3.69149156e-15 7 38 7 38 43 -3.69149156e-15 43"></polygon>
                                    <rect id="Rectangle-Copy-12" fill="url(#linearGradient-1-1)"
                                          transform="translate(46.500000, 25.000000) scale(-1, 1) translate(-46.500000, -25.000000) "
                                          x="38" y="7" width="17" height="36"></rect>
                                    <polygon id="Rectangle-Copy-13" fill="#F8F9FB"
                                             transform="translate(39.500000, 3.500000) scale(-1, 1) translate(-39.500000, -3.500000) "
                                             points="24 7 41 7 55 -3.63806207e-12 38 -3.63806207e-12"></polygon>
                                </g>
                                <rect id="Rectangle-Copy-15" fill="url(#linearGradient-2-1)" x="13" y="45" width="40"
                                      height="36"></rect>
                                <g id="Rectangle-Copy-17" transform="translate(53.000000, 45.000000)">
                                    <mask id="mask-4-1" fill="white">
                                        <use xlink:href="#path-3-1"></use>
                                    </mask>
                                    <use id="Mask" fill="#E0E3E9"
                                         transform="translate(8.500000, 18.000000) scale(-1, 1) translate(-8.500000, -18.000000) "
                                         xlink:href="#path-3-1"></use>
                                    <polygon id="Rectangle-Copy" fill="#D5D7DE" mask="url(#mask-4-1)"
                                             transform="translate(12.000000, 9.000000) scale(-1, 1) translate(-12.000000, -9.000000) "
                                             points="7 0 24 0 20 18 -1.70530257e-13 16"></polygon>
                                </g>
                                <polygon id="Rectangle-Copy-18" fill="#F8F9FB"
                                         transform="translate(66.000000, 51.500000) scale(-1, 1) translate(-66.000000, -51.500000) "
                                         points="62 45 79 45 70 58 53 58"></polygon>
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
        </div>
    @endif
</div>

{{-- Phân trang --}}
@if ($products->hasPages())
    <div class="common-pagination" style="margin-top: 20px;">
        <div class="es-pagination el-pagination is-background">

            {{-- Nút prev --}}
            @if ($products->onFirstPage())
                <button type="button" class="btn-prev" disabled>
                    <i class="el-icon el-icon-arrow-left"></i>
                </button>
            @else
                <button type="button" class="btn-prev" disabled>
                    <a href="{{ $products->previousPageUrl() }}">
                        <i class="el-icon el-icon-arrow-left"></i>
                    </a>
                </button>
            @endif


            {{-- Danh sách trang --}}
            <ul class="el-pager">
                @php
                    $start = max($products->currentPage() - 2, 1);
                    $end = min($products->currentPage() + 2, $products->lastPage());
                @endphp

                {{-- Trang đầu tiên --}}
                @if ($start > 1)
                    <li class="number"><a href="{{ $products->url(1) }}">1</a></li>
                    @if ($start > 2)
                        <li class="el-icon more btn-quicknext el-icon-more"></li>
                    @endif
                @endif

                {{-- Các trang giữa --}}
                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $products->currentPage())
                        <li class="number active">{{ $i }}</li>
                    @else
                        <li class="number"><a href="{{ $products->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endfor

                {{-- Trang cuối --}}
                @if ($end < $products->lastPage())
                    @if ($end < $products->lastPage() - 1)
                        <li class="el-icon more btn-quicknext el-icon-more"></li>
                    @endif
                    <li class="number"><a
                                href="{{ $products->url($products->lastPage()) }}">{{ $products->lastPage() }}</a>
                    </li>
                @endif
            </ul>

            {{-- Nút next --}}
            @if ($products->hasMorePages())

                <button type="button" class="btn-next" disabled>

                    <a href="{{ $products->nextPageUrl() }}">
                        <i class="el-icon el-icon-arrow-right"></i>
                    </a>
                </button>
            @else
                <button type="button" class="btn-next" disabled>
                    <i class="el-icon el-icon-arrow-right"></i>
                </button>
            @endif
        </div>
    </div>
@endif
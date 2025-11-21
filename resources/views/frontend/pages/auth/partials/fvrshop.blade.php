<style>
    .container-product {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 sản phẩm mỗi dòng */
        gap: 20px; /* khoảng cách giữa các sản phẩm */
        margin-top: 20px;
    }

    .product {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        background: #fff;
        transition: all 0.3s ease;
    }

    .product:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .product .poster img {
        width: 100%;
        max-height: 180px;
        object-fit: cover;
        border-radius: 6px;
    }

    /* Responsive: màn nhỏ hơn 1024px sẽ hiển thị 2 cột */
    @media (max-width: 1024px) {
        .container-product {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Responsive: màn nhỏ hơn 600px sẽ hiển thị 1 cột */
    @media (max-width: 600px) {
        .container-product {
            grid-template-columns: 1fr;
        }
    }

</style>
<div data-v-2c463d0c="" class="set-container">
    <div data-v-7c4963b6="" data-v-2c463d0c="" class="collect-content">
        <div data-v-7c4963b6="" class="el-dialog__wrapper es-dialog" style="display: none;">
            <div role="dialog" aria-modal="true" aria-label="dialog" class="el-dialog el-dialog--center"
                 style="margin-top: 15vh;">
                <div class="el-dialog__header">
                    <div data-v-7c4963b6="" class="dialog-title">
                        <span data-v-7c4963b6="">{{__('lang.add_to_cart')}}</span>
                    </div>
                    <button type="button" aria-label="Close" class="el-dialog__headerbtn">
                        <i class="el-dialog__close el-icon el-icon-close"></i>
                    </button>
                </div><!---->
                <div class="el-dialog__footer">
                    <span data-v-7c4963b6=""></span>
                </div>
            </div>
        </div>
        <div data-v-7c4963b6="" class="page-title" style="cursor: pointer;">
{{--            <i data-v-7c4963b6="" class="el-icon-arrow-left"></i>--}}
            {{__('lang.favorite_products')}}
        </div>
        <div data-v-7c4963b6="" class="">

            @if($favorites->isEmpty())
                <div data-v-7c4963b6="" class="content"
                     style="translate: none; rotate: none; scale: none; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">
                    <!---->
                </div>
                <div data-v-7c4963b6="" class="el-empty">
                <div class="el-empty__image">
                    <svg viewBox="0 0 79 86" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs>
                            <linearGradient id="linearGradient-1-7" x1="38.8503086%" y1="0%" x2="61.1496914%" y2="100%">
                                <stop stop-color="#FCFCFD" offset="0%"></stop>
                                <stop stop-color="#EEEFF3" offset="100%"></stop>
                            </linearGradient>
                            <linearGradient id="linearGradient-2-7" x1="0%" y1="9.5%" x2="100%" y2="90.5%">
                                <stop stop-color="#FCFCFD" offset="0%"></stop>
                                <stop stop-color="#E9EBEF" offset="100%"></stop>
                            </linearGradient>
                            <rect id="path-3-7" x="0" y="0" width="17" height="36"></rect>
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
                                        <rect id="Rectangle-Copy-12" fill="url(#linearGradient-1-7)"
                                              transform="translate(46.500000, 25.000000) scale(-1, 1) translate(-46.500000, -25.000000) "
                                              x="38" y="7" width="17" height="36"></rect>
                                        <polygon id="Rectangle-Copy-13" fill="#F8F9FB"
                                                 transform="translate(39.500000, 3.500000) scale(-1, 1) translate(-39.500000, -3.500000) "
                                                 points="24 7 41 7 55 -3.63806207e-12 38 -3.63806207e-12"></polygon>
                                    </g>
                                    <rect id="Rectangle-Copy-15" fill="url(#linearGradient-2-7)" x="13" y="45"
                                          width="40" height="36"></rect>
                                    <g id="Rectangle-Copy-17" transform="translate(53.000000, 45.000000)">
                                        <mask id="mask-4-7" fill="white">
                                            <use xlink:href="#path-3-7"></use>
                                        </mask>
                                        <use id="Mask" fill="#E0E3E9"
                                             transform="translate(8.500000, 18.000000) scale(-1, 1) translate(-8.500000, -18.000000) "
                                             xlink:href="#path-3-7"></use>
                                        <polygon id="Rectangle-Copy" fill="#D5D7DE" mask="url(#mask-4-7)"
                                                 transform="translate(12.000000, 9.000000) scale(-1, 1) translate(-12.000000, -9.000000) "
                                                 points="7 0 24 0 20 18 -1.70530257e-13 16"></polygon>
                                    </g>
                                    <polygon id="Rectangle-Copy-18" fill="#F8F9FB"
                                             transform="translate(66.000000, 51.500000) scale(-1, 1) translate(-66.000000, -51.500000) "
                                             points="62 45 79 45 70 58 53 58"></polygon>
                                </g>
                            </g>
                        </g>
                    </svg></div>
                <div class="el-empty__description">
                    <p>{{__('lang.no_data_found')}}</p>
                </div><!---->
            </div>
            @else
                <div class="container-product">
                    @foreach($favorites as $fvr)
                        @php
                            $product = $fvr->product;
                            $url = $product ? CommonHelper::writeUrl($product->slug, true, true) : '#';
                        @endphp
                        <div class="product">
                            <div>
                                <a href="{{ $url }}" style="text-decoration: none; color: inherit;">
                                    <div class="poster">
                                        <img src="{{ $product ? asset('filemanager/userfiles/'.$product->image) : asset('images/no-product.png') }}"
                                             alt="{{ $product->name ?? 'Sản phẩm đã xóa' }}"/>
                                    </div>
                                    <h2>
                                        ${{ $product ? number_format($product->price, 2) : '0.00' }}
                                    </h2>
                                    <div class="product-res">
                                        {{ __('lang.Sold') }} {{ $product->total_sold ?? 0 }}
                                    </div>
                                    <p>{{ $product->name ?? 'Sản phẩm đã xóa' }}</p>
                                </a>
                            </div>

                            <div class="product-footer">
                                @if($product)
                                    <div class="buy-btn-wrapper"
                                         data-name="{{ $product->name }}"
                                         data-price="{{ number_format($product->price, 2) }}"
                                         data-img="{{ asset('filemanager/userfiles/'.$product->image) }}"
                                         data-album="{{ $product->image_extra }}"
                                         style="cursor:pointer; display:flex; align-items:center; gap:8px;">
                                        <i class="el-icon-shopping-cart-full"></i>
                                        <span class="buy-btn">{{ __('lang.Purchase_Now') }}</span>
                                    </div>
                                    <div class="favorite" data-product-id="{{ $product->id }}">
                                        <i class="el-icon-star-off"></i>
                                    </div>
                                @else
                                    <div style="color:#999; font-style:italic;">Sản phẩm đã xóa</div>
                                    <div class="buy-btn-wrapper" style="opacity:0.5; pointer-events:none;">
                                        <i class="el-icon-shopping-cart-full"></i>
                                        <span class="buy-btn">{{ __('lang.Purchase_Now') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="el-loading-mask" style="display: none;">
                <div class="el-loading-spinner"><svg viewBox="25 25 50 50" class="circular">
                        <circle cx="50" cy="50" r="20" fill="none" class="path"></circle>
                    </svg><!----></div>
            </div>
        </div><!---->
    </div>
</div>
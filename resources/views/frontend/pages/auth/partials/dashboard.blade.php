<style>
    .item {
        display: flex;
        align-items: center; /* Căn giữa hình và chữ theo chiều dọc */
        gap: 10px; /* Khoảng cách giữa hình và chữ */
        padding: 5px;
    }

    .item .poster img {
        width: 90px;
        height: 90px;
        object-fit: cover; /* Giữ tỷ lệ ảnh, không bị méo */
        border-radius: 4px; /* (tùy chọn) bo góc ảnh */
    }

    .item .info {
        display: flex;
        flex-direction: column;
    }

    .item .tit {
        font-size: 14px;
        line-height: 1.4;
    }

    .item .price {
        font-size: 13px;
        color: #333;
        font-weight: bold;
    }

</style>
<div data-v-2c463d0c="" class="dashboard">
    <div>
        <div class="balance">
            <div><span>{{__('lang.balance')}}</span>
                <p style="margin:0px">{{ number_format($user->vi_tien, 0, ',', '.') }}₫</p>
            </div>
        </div>
        <div class="count">
            <div class="count-item">
                <div>
                    <span>{{$count}}</span>
                    <p>{{__('lang.favorite_products')}}</p>
                </div>
            </div>
            <div class="count-item">
                <div><span>{{$countshop}}</span>
                    <p>{{__('lang.follow_store')}}</p>
                </div>
            </div>
        </div>
        <div class="visit">
            <div class="visit-title"> {{__('lang.follow_store')}} </div>
            @if($flShop->isEmpty())
                <div class="el-empty">
                    <div class="el-empty__image">
                        <!-- SVG hiển thị khi không có dữ liệu -->
                    </div>
                    <div class="el-empty__description">
                        <p>{{__('lang.no_data_found')}}</p>
                    </div>
                </div>
            @else
                <div class="visit-item">
                    @foreach($flShop as $fl)
                        <div class="visit-item-box">
                            <div class="visit-item-box-flex">
                                <div class="avatar">
                                    <img src="{{ asset('filemanager/userfiles/' . optional($fl->shop)->logo_cua_hang) }}" alt="">
                                </div>
                                <div class="visit-item-desc">
                                    <span>{{ optional($fl->shop)->ten_cua_hang ?? 'Shop đã xóa' }}</span>
                                    <div class="visit-item-text">
                                        Số lượng sản phẩm：{{ optional($fl->shop)->product_count ?? 0 }}
                                    </div>
                                    <div class="visit-item-text">
                                        Số lượt xem：{{ optional($fl->shop)->views ?? 0 }}
                                    </div>
                                    <div class="visit-item-text">
                                        Tỷ lệ đánh giá tốt：{{ optional($fl->shop)->rating_percent ?? 0 }}%
                                    </div>
                                </div>
                            </div>
                            @if($fl->shop)
                                <button type="button" class="el-button visit-btn el-button--default"
                                        onclick="window.location.href='{{ url('/store?storeId=' . $fl->shop->id) }}'"
                                        style="border-radius: 30px;padding: 5px;">
                                    <span> Ghé thăm cửa hàng </span>
                                </button>
                            @else
                                <button type="button" class="el-button visit-btn el-button--default"
                                        disabled
                                        style="border-radius: 30px;padding: 5px;">
                                    <span> Shop đã xóa </span>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div data-v-750899f6="" class="recharge">
{{--        <div data-v-750899f6="" class="box-btn">--}}
{{--            <div data-v-750899f6="" class="tit">{{__('lang.deposit')}}</div>--}}
{{--            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-bar-up" viewBox="0 0 16 16">--}}
{{--                <path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5m-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5"/>--}}
{{--            </svg>--}}
{{--        </div>--}}
        <div data-v-750899f6="" class="collection" style="margin-top: 0px">
            <div data-v-750899f6="" class="tit"> {{__('lang.favorite_products')}} </div>
            @if($favorites->isEmpty())
                <div data-v-750899f6="" class="el-empty">
                <div class="el-empty__image"><svg viewBox="0 0 79 86" version="1.1"
                                                  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs>
                            <linearGradient id="linearGradient-1-2" x1="38.8503086%" y1="0%" x2="61.1496914%"
                                            y2="100%">
                                <stop stop-color="#FCFCFD" offset="0%"></stop>
                                <stop stop-color="#EEEFF3" offset="100%"></stop>
                            </linearGradient>
                            <linearGradient id="linearGradient-2-2" x1="0%" y1="9.5%" x2="100%" y2="90.5%">
                                <stop stop-color="#FCFCFD" offset="0%"></stop>
                                <stop stop-color="#E9EBEF" offset="100%"></stop>
                            </linearGradient>
                            <rect id="path-3-2" x="0" y="0" width="17" height="36"></rect>
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
                                        <rect id="Rectangle-Copy-12" fill="url(#linearGradient-1-2)"
                                              transform="translate(46.500000, 25.000000) scale(-1, 1) translate(-46.500000, -25.000000) "
                                              x="38" y="7" width="17" height="36"></rect>
                                        <polygon id="Rectangle-Copy-13" fill="#F8F9FB"
                                                 transform="translate(39.500000, 3.500000) scale(-1, 1) translate(-39.500000, -3.500000) "
                                                 points="24 7 41 7 55 -3.63806207e-12 38 -3.63806207e-12"></polygon>
                                    </g>
                                    <rect id="Rectangle-Copy-15" fill="url(#linearGradient-2-2)" x="13" y="45"
                                          width="40" height="36"></rect>
                                    <g id="Rectangle-Copy-17" transform="translate(53.000000, 45.000000)">
                                        <mask id="mask-4-2" fill="white">
                                            <use xlink:href="#path-3-2"></use>
                                        </mask>
                                        <use id="Mask" fill="#E0E3E9"
                                             transform="translate(8.500000, 18.000000) scale(-1, 1) translate(-8.500000, -18.000000) "
                                             xlink:href="#path-3-2"></use>
                                        <polygon id="Rectangle-Copy" fill="#D5D7DE" mask="url(#mask-4-2)"
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
                    @foreach($favorites as $fvr)
                        @php
                            $product = $fvr->product;
                        @endphp
                        <div data-v-750899f6="" class="item">
                            <div data-v-750899f6="" class="poster">
                                <img data-v-750899f6=""
                                     src="{{ $product ? asset('filemanager/userfiles/'.$product->image) : asset('images/no-product.png') }}"
                                     alt="{{ $product->name ?? 'Sản phẩm đã xóa' }}">
                            </div>
                            <div data-v-750899f6="" class="info">
                                <div data-v-750899f6="" class="tit">
                                    {{ $product ? Illuminate\Support\Str::limit($product->name, 40) : 'Sản phẩm đã xóa' }}
                                </div>
                                <div data-v-750899f6="" class="detail">
                    <span data-v-750899f6="" class="price">
                        ${{ $product ? number_format($product->price, 2) : '0.00' }}
                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
        </div>
        <div data-v-3ea68790="" data-v-750899f6="" class="el-dialog__wrapper" style="display: none;">
            <div role="dialog" aria-modal="true" aria-label="Deposit"
                 class="el-dialog el-dialog--center recharge-method-dialog" style="margin-top: 15vh;">
                <div class="el-dialog__header"><span class="el-dialog__title">Deposit</span><button
                            type="button" aria-label="Close" class="el-dialog__headerbtn">
                        <i class="el-dialog__close el-icon el-icon-close"></i>
                    </button></div><!----><!---->
            </div>
        </div>
        <div data-v-795b7bdd="" data-v-750899f6="" class="el-dialog__wrapper" style="display: none;">
            <div role="dialog" aria-modal="true" aria-label="Deposit" class="el-dialog wallet-dialog"
                 style="margin-top: 15vh;">
                <div class="el-dialog__header"><span class="el-dialog__title">{{__('lang.deposit')}}</span><button
                            type="button" aria-label="Close" class="el-dialog__headerbtn"><i
                                class="el-dialog__close el-icon el-icon-close"></i></button></div><!----><!---->
            </div>
        </div>
        <div data-v-750899f6="" class="el-dialog__wrapper es-dialog" style="display: none;">
            <div role="dialog" aria-modal="true" aria-label="dialog" class="el-dialog el-dialog--center"
                 style="margin-top: 15vh; width: 475px;">
                <div class="el-dialog__header">
                    <div data-v-750899f6="" class="dialog-title">
                        <span data-v-750899f6="">Enter the transactionpassword</span><!----><!---->
                    </div>
                    <button type="button" aria-label="Close" class="el-dialog__headerbtn">
                        <i class="el-dialog__close el-icon el-icon-close"></i>
                    </button>
                </div><!----><!---->
            </div>
        </div>
    </div>
    <div data-v-74982bca="" class="withdraw">
{{--        <div data-v-74982bca="" class="box-btn">--}}
{{--            <div data-v-74982bca="" class="tit">{{__('lang.withdraw')}} </div>--}}
{{--            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-bar-up" viewBox="0 0 16 16">--}}
{{--                <path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5m-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5"/>--}}
{{--            </svg>--}}
{{--        </div>--}}
        <div data-v-74982bca="" class="collection" style="margin-top: 0px">
            <div data-v-74982bca="" class="tit"> {{__('lang.detailed_address')}} </div>
            @if($addresses->isEmpty())
            <div data-v-74982bca="" class="el-empty">
                <div class="el-empty__image"><svg viewBox="0 0 79 86" version="1.1"
                                                  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs>
                            <linearGradient id="linearGradient-1-3" x1="38.8503086%" y1="0%" x2="61.1496914%"
                                            y2="100%">
                                <stop stop-color="#FCFCFD" offset="0%"></stop>
                                <stop stop-color="#EEEFF3" offset="100%"></stop>
                            </linearGradient>
                            <linearGradient id="linearGradient-2-3" x1="0%" y1="9.5%" x2="100%" y2="90.5%">
                                <stop stop-color="#FCFCFD" offset="0%"></stop>
                                <stop stop-color="#E9EBEF" offset="100%"></stop>
                            </linearGradient>
                            <rect id="path-3-3" x="0" y="0" width="17" height="36"></rect>
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
                                        <rect id="Rectangle-Copy-12" fill="url(#linearGradient-1-3)"
                                              transform="translate(46.500000, 25.000000) scale(-1, 1) translate(-46.500000, -25.000000) "
                                              x="38" y="7" width="17" height="36"></rect>
                                        <polygon id="Rectangle-Copy-13" fill="#F8F9FB"
                                                 transform="translate(39.500000, 3.500000) scale(-1, 1) translate(-39.500000, -3.500000) "
                                                 points="24 7 41 7 55 -3.63806207e-12 38 -3.63806207e-12"></polygon>
                                    </g>
                                    <rect id="Rectangle-Copy-15" fill="url(#linearGradient-2-3)" x="13" y="45"
                                          width="40" height="36"></rect>
                                    <g id="Rectangle-Copy-17" transform="translate(53.000000, 45.000000)">
                                        <mask id="mask-4-3" fill="white">
                                            <use xlink:href="#path-3-3"></use>
                                        </mask>
                                        <use id="Mask" fill="#E0E3E9"
                                             transform="translate(8.500000, 18.000000) scale(-1, 1) translate(-8.500000, -18.000000) "
                                             xlink:href="#path-3-3"></use>
                                        <polygon id="Rectangle-Copy" fill="#D5D7DE" mask="url(#mask-4-3)"
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
                <div data-v-74982bca="" class="add_list">
                    @foreach($addresses as $address)
                    <div data-v-74982bca="" class="item">
                        <div data-v-74982bca="" class="info">
                            <div data-v-74982bca="" class="name-and-mobile"><span data-v-74982bca="" class="name">{{$address->ten_nguoi_nhan}}</span><span
                                        data-v-74982bca="" class="mobile">&nbsp;&nbsp;+{{$address->ma_quoc_gia}} {{$address->sdt}}</span></div>
                            <div class="el-switch">
                                <input type="checkbox" class="el-switch__input default-address-switch" data-id="{{ $address->id }}"
                                       data-user-id="{{ $address->user_id }}" {{ $address->mac_dinh ? 'checked' : '' }}>
                                <span class="switch toggle-icon" style="width: 40px; cursor:pointer;">
                                    @if($address->mac_dinh)
                                        <!-- mac_dinh = 1 -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="black" class="bi bi-toggle-on"
                                             viewBox="0 0 16 16">
                                            <path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8" />
                                        </svg>
                                    @else
                                        <!-- mac_dinh = 0 -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="gray" class="bi bi-toggle-off"
                                             viewBox="0 0 16 16">
                                            <path
                                                    d="M11 4a4 4 0 0 1 0 8H8a5 5 0 0 0 2-4 5 5 0 0 0-2-4zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8M0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5" />
                                        </svg>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div data-v-74982bca="" class="address">
                            {{ $address->country_name ?? ''}},
                            {{ $address->province_name ?? ''}},
                            {{ $address->district_name ?? ''}},
                            {{ $address->dia_chi_cu_the ?? ''}}
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div data-v-74982bca="" class="add-btn flex-center" id="open-address-popup">
            <span data-v-74982bca="">+ {{__('lang.add_new_address')}}</span>
        </div>

        <div data-v-16037ff1="" data-v-74982bca="" class="el-dialog__wrapper" style="display: none;">
            <div role="dialog" aria-modal="true" aria-label="Withdraw"
                 class="el-dialog el-dialog--center wallet-dialog withdraw-dialog" style="margin-top: 15vh;">
                <div class="el-dialog__header"><span class="el-dialog__title">{{__('lang.withdraw')}}</span><button
                            type="button" aria-label="Close" class="el-dialog__headerbtn"><i
                                class="el-dialog__close el-icon el-icon-close"></i></button></div><!----><!---->
            </div>
        </div>
        <div data-v-74982bca="" class="el-dialog__wrapper" style="display: none;">
            <div role="dialog" aria-modal="true" aria-label="dialog"
                 class="el-dialog el-dialog--center es-dialog" style="margin-top: 15vh; width: 600px;">
                <div class="el-dialog__header">
                    <div class="dialog-title"><span>{{__('lang.add_new_address')}}/{{__('lang.change_address')}}</span></div><button
                            type="button" aria-label="Close" class="el-dialog__headerbtn"><i
                                class="el-dialog__close el-icon el-icon-close"></i></button>
                </div><!----><!---->
            </div>
        </div>
        <div data-v-74982bca="" class="el-dialog__wrapper es-dialog" style="display: none;">
            <div role="dialog" aria-modal="true" aria-label="dialog" class="el-dialog el-dialog--center"
                 style="margin-top: 15vh; width: 475px;">
                <div class="el-dialog__header">
                    <div data-v-74982bca="" class="dialog-title"><span data-v-74982bca="">Enter the transaction
                                    password</span><!----><!----></div><button type="button" aria-label="Close"
                                                                               class="el-dialog__headerbtn"><i
                                class="el-dialog__close el-icon el-icon-close"></i></button>
                </div><!----><!---->
            </div>
        </div>
    </div>
</div>

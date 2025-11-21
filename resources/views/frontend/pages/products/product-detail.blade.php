@extends('frontend.layout.layout')
{{--@php dd($product->shop_products->shop_id); @endphp--}}
<style>
    .gallery-thumbs .swiper-slide.swiper-slide-active {
        border: 1px solid black !important;
    }

    .product-info-right-info-buy .el-button {
        line-height: 40px;
        text-decoration: none;
    }

    .product-info-right-info-buy .el-button:hover {
        background-color: #404040;
        border-color: #404040;
    }

    .product-info-right-info-des .active {
        border: 1px solid black !important;
    }
    .product-attribute-item {
        border: 1px solid #606266;
        border-radius: 4px;
        padding: 5px 9px;
        cursor: pointer;
    }
    .product-info-right-info-des-item .attr-item.active .product-attribute-item {
        border-color: #000;
    }
    .product-info-right-info-des-item .attr-item .product-attribute-item {
        margin: 0;
    }

    /* Container tổng */
    #commentForm {
        max-width: 600px;
        margin: 20px auto;
        padding: 16px;
        background: #f9f9f9;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        font-family: "Segoe UI", Arial, sans-serif;
    }

    /* Nhãn */
    #commentForm label {
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 6px;
        display: block;
        color: #444;
    }

    /* Input, select, textarea */
    #commentForm input[type="text"],
    #commentForm input[type="file"],
    #commentForm select,
    #commentForm textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 20px;
        outline: none;
        background: #fff;
        font-size: 14px;
        transition: all 0.2s;
    }

    #commentForm textarea {
        border-radius: 12px;
        resize: none;
        min-height: 100px;
    }

    /* Focus effect */
    #commentForm input:focus,
    #commentForm select:focus,
    #commentForm textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.15);
    }

    /* Sao đánh giá */
    .star-rating {
        display: flex;
        gap: 6px;
        font-size: 24px;
        cursor: pointer;
        color: #ccc;
        padding: 6px 0;
    }

    .star-rating span.active {
        color: #f39c12;
    }

    /* Button gửi */
    #commentForm button[type="submit"] {
        width: 100%;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 25px;
        padding: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    #commentForm button[type="submit"]:hover {
        background: #0056b3;
    }

    /* Khoảng cách giữa các group */
    #commentForm .form-group {
        margin-bottom: 16px;
    }

</style>


@section('content')
{{--    @include('chat.Frontend.ChatMessage')--}}
    <div class="app-container contain">
        <div class="product-details-contain">
            <div class="product-info">
                <div class="product-info-content flex-start">
                    <div class="product-info-left">
                        <div class="photo-zoom-wrapper">
                            <div class="photo-zoom-pro">
                                <img id="main-image" src="" alt="Main Image">
                                <div class="zoom-lens" id="zoom-lens"></div>
                            </div>
                            <div class="zoom-result" id="zoom-result"></div>
                        </div>
                        <!-- Thumbnail Swiper -->
                        <div class="swiper-container gallery-thumbs">
                            <div class="swiper-wrapper" id="thumb-wrapper">
                                @php
                                    // Lấy ảnh chính
                                    $album = [$product->image];

                                    // Nếu có ảnh phụ thì tách và merge vào
                                    if (!empty($product->image_extra)) {
                                        $extra = array_filter(explode('|', $product->image_extra));
                                        $album = array_merge($album, $extra);
                                    }
                                @endphp

                                @foreach($album as $k => $img)
                                    <div class="swiper-slide">
                                        <img src="{{ CommonHelper::getUrlImageThumb($img) }}" alt="thumb-{{$k}}">
                                    </div>
                                @endforeach

                                {{--  {{dd($album)}}--}}
                            </div>

                        </div>
                    </div>
                    <div class="product-info-right">
                        <h1 class="product-info-right-title"> {{ $product->name }} </h1>
                        <div class="product-info-right-info">
                            <div class="product-info-right-info-top flex-between">
                                <div class="product-info-right-info-price flex-start">
                                    <h2>{{ __('lang.price') }}</h2>
                                    <span class="price">{{ CommonHelper::convertPrice((isset($shopProduct->gia_ban) && $shopProduct->gia_ban > 0) ? $shopProduct->gia_ban : $product->price) }}</span>
                                </div>
                                <!---->
                                <div class="product-info-right-info-tool flex-start">
                                    <div class="product-info-right-info-tool-item flex-start"><i class="el-icon-service"
                                                                                                 style="color: var(--color-main); font-size: 20px;"></i><span>{{__('lang.Customer_service')}}</span>
                                    </div>
                                    <div data-product-id="{{$product->id }}" class="product-info-right-info-tool-item flex-start favorite"><i
                                                class="el-icon-star-off"></i><span>{{__('lang.Favorites')}}</span></div>
                                </div>
                            </div>
                            <div class="product-info-right-info-des">
                                <div class="product-info-right-info-des-item flex-start">
                                    <div><span style="margin-right: 5px;">{{ __('lang.Sold') }}</span><span>{{$product->sold}}</span>
                                    </div>
                                </div>
                                <div class="product-info-right-info-des-item flex-start"><span
                                            class="label-title"> {{ __('lang.lo_hang') }} </span><span>{{ __('lang.CSKH') }}</span>
                                </div>
                                <div class="product-info-right-info-des-item flex-start">
                                    <span class="label-title"> {{ __('lang.shipping') }} </span>
                                    <div class="freight-tips">
                                        <p> {{ __('lang.free') }} </p>
                                        <i class="el-tooltip el-icon-info" aria-describedby="el-tooltip-8010"
                                           tabindex="0"></i>
                                    </div>
                                </div>
                                <div class="product-info-right-info-des-item flex-start">
                                    <span class="label-title"> {{ __('lang.quantity') }} </span>
                                    <div class="el-input-number">
                                        <span role="button" class="el-input-number__decrease is-disabled"
                                              style="height: 38px;"><i class="el-icon-minus"></i></span><span
                                                role="button" class="el-input-number__increase" style="height: 38px;"><i
                                                    class="el-icon-plus"></i></span>
                                        <div class="el-input">
                                            <!----><input type="text" data-product-id="{{$product->id}}" data-product-attribute=""
                                                          product-shop-price="{{isset($shopProduct->gia_ban) ? $shopProduct->gia_ban : 0}}" product-discount="{{isset($shopProduct->gia_giam_gia) ? $shopProduct->gia_giam_gia : 0}}"
                                                          autocomplete="off" max="9999" min="1" class="el-input__inner"
                                                          name="quantity" role="spinbutton" aria-valuemax="9999"
                                                          value="1" aria-disabled="false"><!----><!----><!----><!---->
                                        </div>
                                    </div>
                                </div>
                                <div>
                                @if(count($attributes) > 0)
                                    @foreach($attributes as $key => $attr)
                                    <div class="product-info-right-info-des-item flex-start product-attr">
                                        <span class="label-title sku-title">
                                            {{ $attr['parent_name'] }}:
                                            <span id="selected-attribute">
                                                {{ $attr['children'][0]['name'] ?? '' }}
                                            </span>
                                        </span>
                                        <div class="attr-container">
                                        @if(isset($attr['children']) && count($attr['children']) > 0)
                                            @foreach($attr['children'] as $k => $item)
                                                <div class="attr-item {{$k == 0 ? 'active' : ''}}"
                                                     data-value="{{ $item['name'] }}" data-id="{{ $item['id'] }}" data-image="{{ $item['image'] }}">
                                                    @if(!empty($item['image']))
                                                    <div class="attr-img">
                                                        @php
                                                        $image = explode('|', $item['image']);
                                                        $img = $image[0] ?? '';
                                                        @endphp
                                                        <img src="{{ CommonHelper::getUrlImageThumb($img) ?? asset('images/no-image.png') }}"
                                                             alt="">
                                                    </div>
                                                    @else
                                                        <div class="product-attribute-item">
                                                            {{ $item['name'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                                </div>
                                <div class="product-info-right-info-des-item flex-start">
                                    <span class="label-title">{{ __('lang.price') }} </span>
                                    <p><span class="price">{{ CommonHelper::convertPrice((isset($shopProduct->gia_ban) && $shopProduct->gia_ban > 0) ? $shopProduct->gia_ban : $product->price) }}</span></p>
                                </div>
                            </div>
                            <div class="product-info-right-info-buy">
                                @auth
                                    <form id="buy-now-form" action="{{ route('settlement') }}" method="POST"
                                          style="display:none;">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" id="buy-now-id">
                                        <input type="hidden" name="qty" id="buy-now-qty">
                                        <input type="hidden" name="price" id="buy-now-price">
                                        <input type="hidden" name="discount" id="buy-now-discount" value="{{$shopProduct->gia_giam_gia ?? 0}}">
                                        <input type="hidden" name="attribute" id="buy-now-attribute" value="">
                                    </form>
                                    <a href="#" class="el-button el-button--primary btn-buy-now">
                                        <span> {{ __('lang.Purchase_Now') }} </span>
                                    </a>
                                    <div class="addcart flex-start addcartauth">
                                        <span> {{ __('lang.add_cart') }}</span>
                                    </div>
                                @else
                                    <a href="/settlement" class="el-button el-button--primary">
                                        <span> {{ __('lang.Purchase_Now') }} </span>
                                    </a>
                                    <div class="addcart flex-start addcartnotlogin">
                                        <span> {{ __('lang.add_cart') }}</span>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                <!---->
            </div>
            <div class="product-description">
                <h1 class="title1"> {{ __('lang.description') }} </h1>
                {!! $product->description !!}
            </div>
            <div class="product-comment">
                <div class="p-header">
                    <div class="title1">
                        {{__("lang.User_reviews")}}
                        <span>({{ $comments->total() }})</span>
                    </div>

                    {{-- Bộ lọc --}}
                    <div style="display: flex; flex-direction: row; gap:10px;">
                        <a href="{{ request()->fullUrlWithQuery(['filter'=>'with_image']) }}" class="button">
                            {{__("lang.Has_images")}} ({{ \DB::table('comment')->where('product_id',$product->id)->whereNotNull('hinh_anh')->count() }})
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter'=>'good']) }}" class="button">
                            {{__("lang.Danh_gia_tot")}}
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter'=>'medium']) }}" class="button">
                            {{__('lang.Average_reviews')}}
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter'=>'bad']) }}" class="button">
                            {{__("lang.Danh_gia_xau")}}
                        </a>
                    </div>
                </div>

                <div class="product-comment-content" style="padding-top: 10px;">
                    @foreach($comments as $comment)
                        <div class="product-comment-item">
                            <div class="product-comment-top flex-start">
                                <div class="userInfo">
                                    <img src="{{ $comment->user_avatar
                                        ? CommonHelper::getUrlImageThumb($comment->user_avatar)
                                        : asset('images/default-avatar.png') }}"
                                         alt="User">
                                    <div>{{ $comment->user_name }}</div>
                                </div>
                            </div>

                            {{-- Hiển thị số sao --}}
                            <div class="rate">
                                <div class="rate-box">
                                    <div role="slider" aria-valuenow="{{ $comment->diem_danh_gia }}" aria-valuemin="0"
                                         aria-valuemax="5" class="el-rate">
                                        @for($i=1; $i<=5; $i++)
                                            <span class="el-rate__item" style="cursor: auto;">
                                    <i class="el-rate__icon {{ $i <= $comment->diem_danh_gia ? 'el-icon-star-on' : 'el-icon-star-off' }}"
                                       style="color: {{ $i <= $comment->diem_danh_gia ? 'rgb(247, 186, 42)' : '#ccc' }};">
                                    </i>
                                </span>
                                        @endfor
                                    </div>
                                    <span style="color: var(--color-main); font-size: 12px;">{{__('lang.The_order_was_completed')}}</span>
                                </div>
                            </div>

                            {{-- Nội dung --}}
                            <p class="product-comment-text">{{ $comment->noi_dung_binh_luan }}</p>

                            {{-- Ảnh trong bình luận --}}
                            @if($comment->hinh_anh)
                                <div class="comment-image">
                                    <img src="{{ asset($comment->hinh_anh) }}" alt="{{$comment->hinh_anh}}" style="max-width:120px; border-radius:6px;">

                                </div>
                            @endif

                            {{-- Thời gian --}}
                            <span class="eval-time">
                    {{ date('Y-m-d H:i:s', $comment->thoi_gian_binh_luan) }}
                </span>
                        </div>
                    @endforeach
                </div>

                {{-- Form gửi bình luận --}}
                @auth
                    <form id="commentForm" method="POST" action="{{ route('comments.store') }}" enctype="multipart/form-data">

                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        <input type="hidden" name="shop_id" value="{{ App\Modules\Product\Models\ShopProducts::where('product_id', $product->id)->value('shop_id') }}">
                        <input type="hidden" name="thoi_gian_binh_luan" value="{{ time() }}">
                        <input type="hidden" name="diem_danh_gia" id="diem_danh_gia" value="0">

                        <div class="comment-bar">
                            <!-- icon sao -->
                            <div class="star-rating">
                                <span data-value="1">&#9733;</span>
                                <span data-value="2">&#9733;</span>
                                <span data-value="3">&#9733;</span>
                                <span data-value="4">&#9733;</span>
                                <span data-value="5">&#9733;</span>
                            </div>

                            <!-- ô nhập nội dung -->
                            <textarea name="noi_dung_binh_luan" id="noi_dung_binh_luan" placeholder="{{__('lang.Write_a_comment')}}..." rows="1"></textarea>
<br><br>
                            <!-- chọn ảnh -->

                            <input type="file" name="hinh_anh" id="hinh_anh" hidden>
                            <br>
                            <!-- nút gửi -->
                            <button type="submit" class="send-btn">➤</button>
                        </div>
                    </form>

                @endauth

                @guest
                    <p class="text-center mt-3">
                        Bạn cần <a href="{{ url('/login-form') }}">{{__('lang.Login')}}</a> {{__("lang.to_send_comment")}}.
                    </p>
                @endguest


                {{-- PHÂN TRANG --}}
                <div class="product-comment-pagination">
                    {{ $comments->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <div>
                <div>
                    <div class="product-details-content">
                        <h1 class="title1"> {{__("lang.product_description")}} </h1>
                        {!! $product->review_detail !!}
                    </div>
                </div>
            </div>
        </div>
        @if(session('success'))
            <script>
                alert("{{ session('success') }}");
            </script>
        @endif

        <div>
            <div class="product-details-merchant">
{{--                <h3>Thông tin Shop</h3>--}}
                <div class="product-details-merchant-top flex-start">
                    <div class="shop-img">
                        <img fit="fill"
                             src="{{ CommonHelper::getUrlImageThumb($shop->logo) }}"
                             alt="{{ $shop->name }}">
                    </div>
                    <h1>{{ $shop->name }}</h1>
                </div>

                 {{__('lang.Thong_ke')}}
                <div class="product-details-merchant-statistics flex-between">
                    <div class="all flex-center merchant-statistics" style="width: 50%;">
                        <h2>{{ $stats['total_products'] }}</h2>
                        <span>{{__('lang.All_products')}}</span>
                    </div>

                    <div class="line"></div>

                    <div class="follower flex-center merchant-statistics" style="width: 50%;">
                        <h2>{{ number_format($stats['total_followers']) }}</h2>
                        <span>{{__('lang.Follow')}}</span>
                    </div>
                </div>

                <div class="flex-center merchant-statistics">
                    <h2>{{ number_format($stats['total_revenue']) }}</h2>
                    <span>{{__('lang.Total_sales')}}</span>
                </div>

                <button type="button"
                        class="el-button el-button--primary is-plain"
                        onclick="window.location.href='{{ url('/store?storeId=' . $shop->id) }}'">
                    <span> {{__("lang.visit_store")}} </span>
                </button>
            </div>
        </div>
        <div class="el-loading-mask" style="display: none;">
            <div class="el-loading-spinner">
                <svg viewBox="25 25 50 50" class="circular">
                    <circle cx="50" cy="50" r="20" fill="none" class="path"></circle>
                </svg>
                <!---->
            </div>
        </div>
    </div>
    <script>
        // Khi document sẵn sàng
        document.addEventListener('DOMContentLoaded', function () {
            const slides = document.querySelectorAll('.gallery-thumbs .swiper-slide');

            slides.forEach(slide => {
                slide.addEventListener('click', function () {
                    // Bỏ active của slide khác
                    slides.forEach(s => s.classList.remove('swiper-slide-active'));
                    // Thêm active cho slide vừa click
                    this.classList.add('swiper-slide-active');
                });
            });
        });
        {{-- 29/09/2025 DiepTV modified start --}}
        // document.addEventListener('DOMContentLoaded', function () {
        //     const colorItems = document.querySelectorAll('.attr-item');
        //     const selectedColor = document.getElementById('selected-color');
        //
        //     colorItems.forEach(item => {
        //         item.addEventListener('click', function () {
        //             // Bỏ active cũ
        //             document.querySelectorAll('.attr-item').forEach(el => el.classList.remove('active'));
        //             this.classList.add('active');
        //
        //             // Cập nhật tên màu
        //             selectedColor.textContent = this.dataset.value;
        //         });
        //     });
        // });
        {{-- 29/09/2025 DiepTV modified end --}}
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const stars = document.querySelectorAll(".star-rating span");
        const input = document.getElementById("diem_danh_gia");

        stars.forEach(star => {
            star.addEventListener("mouseover", () => {
                resetStars();
                highlightStars(star.dataset.value);
            });

            star.addEventListener("mouseout", () => {
                resetStars();
                highlightStars(input.value);
            });

            star.addEventListener("click", () => {
                input.value = star.dataset.value;
                resetStars();
                highlightStars(input.value);
            });
        });

        function resetStars() {
            stars.forEach(s => s.classList.remove("active", "hover"));
        }

        function highlightStars(value) {
            stars.forEach(s => {
                if (s.dataset.value <= value) {
                    s.classList.add("active");
                }
            });
        }
    });
</script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="{{ asset(config('core.admin_asset').'/css/swiper-setup.css') }}"/>
    <link rel="stylesheet" href="{{ asset(config('core.frontend_asset').'/css/bootstrap.min.css') }}"/>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/swiper_setup.js') }}"></script>
    <script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/product.js') }}"></script>
@endsection
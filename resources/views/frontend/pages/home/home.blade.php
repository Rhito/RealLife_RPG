@extends('frontend.layout.layout')

<style>
    .main-classification-item span {
        display: inline-block;
        max-width: 90px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .swiper-container.swiper-container-initialized.swiper-container-horizontal {
        height: 100px;
    }

    .main-classification-item:hover span {
        color: black !important;
    }

    .img-content:hover {
        border-color: black !important;
    }

    .main-classification-item:hover .img-content {
        border-color: black !important;
        transition: ease-in-out;
        transform: scale(.95);
    }

    .pro-container:hover .product {
        border: 1px solid black !important;
        scale: 1.1;
        transition: 0.5s;
    }


    .product {
        transition: all 0.5s ease; /* hoặc chỉ scale, border nếu muốn */
    }


    .banner-content[data-v-6684c942] {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        height: 352px;
        overflow: hidden;
        background-position: 50%;
        background-size: cover;
        background-repeat: no-repeat;
        display: flex;
        justify-content: flex-end;
        margin-top: 20px !important;
        margin-bottom: 20px !important;
    }

    .banner-content > .content[data-v-6684c942] {
        width: 650px;
        height: 352px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center
    }

    .banner-content > .content > p {
        color: #2e2e23;
        font-size: 42px;
        margin: 0;
    }

    .banner-content > .content > h3 {
        color: #fa3835;
        font-size: 42px;
        margin: 0;
    }

    .banner-content > .content > div {
        padding: 0 40px;
        height: 50px;
        border-radius: 50px;
        line-height: 50px;
        background-color: #2e2e2e;
        color: #f1ce5a;
        font-size: 16px;
        margin-top: 20px;
        cursor: pointer
    }

    .banner-content > .content > div:hover {
        background-color: #181818
    }
    .top-shops .img-content {
        border-radius: 50% !important;
        overflow: hidden;
        width: 100px;
        height: 100px;
        border: 2px solid transparent;
        transition: border-color 0.3s ease, transform 0.3s ease;
    }

    .top-shops .img-content img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .top-shops .main-classification-item:hover .img-content {
        border-color: #ff4d4f !important;
    }

    .top-shops .main-classification-item:hover .img-content img {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }
    .main-classification-title .all{
        color:black !important;
        font-size: 12px;
    }
    .main-daily-title .all, .main-daily-title span {
        color: black !important;
        font-size: 12px;
    }
    .popup {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0; top: 0;
        width: 100%; height: 100%;
        /*background: rgba(0,0,0,0.5);*/
    }

    /* Khung popup */
    .popup-content {
        background: #fff;
        width: 1000px;
        max-height: 80%; /* giới hạn chiều cao */
        margin: 100px auto;
        /*padding: 20px;*/
        border-radius: 12px;
        overflow-y: auto; /* bật scroll khi nội dung dài */
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }

    /* Nút đóng */
    .close {
        float: right;
        font-size: 24px;
        cursor: pointer;
    }
    .popup {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;

        /* ẩn mặc định */
        opacity: 0;
        visibility: hidden;
        transform: scale(0.9);
        transition: all 0.3s ease;
    }

    .popup.show {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    .product-info {
        margin-top: 30px !important;
        padding: 25px 25px 30px;
    }
    .popup.show {
        opacity: 1;
        visibility: visible;
        background: rgba(0,0,0,0.6); /* nền mờ */
    }
    .product-info-content {
        align-items: flex-start;
    }
    .flex-end, .flex-start {
        display: flex
    ;
        align-items: center;
    }
    .flex-start {
        justify-content: flex-start;
    }
    .product-info-left

    {
        width: 378px;
        margin-right: 22px;
    }
    .photo-zoom-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .photo-zoom-pro {
        width: 100%;
        height: 350px;
        overflow: hidden;
        border: 1px solid #eee;
        cursor: crosshair;
        position: relative;

    }
    .el-input-number__decrease, .el-input-number__increase {
        position: absolute;
        z-index: 1;
        top: 1px;
        width: 40px !important;
        height: 38.3px !important;
        text-align: center;
        background: #F5F7FA;
        color: #606266;
        cursor: pointer;
        font-size: 13px;
    }
    .photo-zoom-pro img {
        width: 100%;
        height: 100%;
        object-fit: contain !important;
        display: block;
    }
    img {
        vertical-align: middle;
    }
    img {
        border: 0;
    }
    img, video {
        max-width: 100%;
        height: auto;
    }

    .zoom-lens {
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(0, 0, 0, 0.50);
        display: none;
        pointer-events: none;
    }
    .zoom-result {
        width: 378px;
        height: 350px;
        border: 1px solid #eee;
        margin-left: 10px;
        background-repeat: no-repeat;
        background-size: 200%;
        display: none;
        position: absolute;
        top: 0;
        left: 100%;
        z-index: 10;
        background-color: #fff;
    }
    .product-info-left .gallery-thumbs {
        height: 89px;
        margin-top: 15px;
    }
    .gallery-thumbs {
        margin-top: 20px;
    }
    .swiper-horizontal {
        touch-action: pan-y;
    }
    .swiper-container {
        margin-left: auto;
        margin-right: auto;
        position: relative;
        overflow: hidden;
        list-style: none;
        padding: 0;
        z-index: 1;
    }
    .product-info-right {
        max-width: 563px;
    }
    .product-info-right-title {
        font-weight: 600;
        font-size: 20px;
        line-height: 30px;
        color: black;
        margin-bottom: 14px;
        word-break: break-all;
    }
    .flex-between {
        justify-content: space-between;
    }
    .product-info-right-info-top {
        width: 100%;
        padding: 18px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    .flex-between, .flex-center {
        display: flex
    ;
        align-items: center;
    }
    .flex-end, .flex-start {
        display: flex
    ;
        align-items: center;
    }
    .flex-start {
        justify-content: flex-start;
    }
    .product-info-right-info-price h2 {
        min-width: 10px;
        text-align: left;
        font-weight: 400;
        font-size: 12px;
        color: #999;
        padding-right: 8px;
    }

    .product-info-right-info .price {
        font-weight: 500;
        font-size: 16px;
        color: #f89900;
    }
    .product-info-right-info-tool-item {
        cursor: pointer;
        margin-right: 30px;
    }
    .product-info-right-info-tool-item span {
        color: #333;
        margin-left: 6px;
    }
    .product-info-right-info-tool-item:last-child {
        margin-right: 0;
    }
    .product-info-right-info-tool-item {
        cursor: pointer;
        margin-right: 30px;
    }
    .product-info-right-info-tool-item .el-icon-star-off, .product-info-right-info-tool-item .el-icon-star-on {
        font-size: 18px;
        color: #f89900;
    }
    .product-info-right-info-tool-item span {
        color: #333;
        margin-left: 6px;
    }
    .product-info-right-info-des {
        font-weight: 400;
        font-size: 12px;
        color: #999;
    }
    .product-info-right-info-des-item {
        margin-top: 25px;
    }
    .product-info-right-info-des-item {
        margin-top: 25px;
    }
    .product-info-right-info-des .label-title {
        display: inline-block;
        min-width: 67px;
        margin-right: 16px;
    }
    .product-info-right-info-des .product-attr {
        flex-direction: column;
        align-items: baseline !important;
    }
    .product-info-right-info-des .product-attr .label-title {
        margin-bottom: 10px;
    }

    .product-info-right-info-des .label-title {
        display: inline-block;
        min-width: 67px;
        margin-right: 16px;
    }
    .product-info-right-info-des .attr-container {
        width: 100%;
        display: grid
    ;
        grid-template-columns: repeat(auto-fit, minmax(54px, 54px));
        grid-column-gap: 10px;
        grid-row-gap: 5px;
        align-content: center;
        padding-bottom: 4px;
    }
    .product-info-right-info-des .product-info-right-info-des-item .active {
        border: 1px solid black;
        border-radius: 4px;
    }
    .product-info-right-info-des .attr-item {
        margin-right: 0 !important;
        cursor: pointer;
    }

    .product-info-right-info-des .attr-item .attr-img {
        width: 54px;
        height: 54px;
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #eee;
    }
    .product-info-right-info-des .attr-item .attr-img img {
        width: 100%;
        height: 100%;
        -o-object-fit: cover;
        object-fit: cover;
    }
    .product-info-right-info .price {
        font-weight: 500;
        font-size: 16px;
        color: #f89900;
    }
    .product-info-right-info-buy {
        width: 100%;
        text-align: center;
        margin-top: 72px;
        margin-bottom: 40px;
        display: flex
    ;
        justify-content: space-between;
    }
    .product-info-right-info-buy .addcart, .product-info-right-info-buy .el-button {
        width: 45%;
        max-width: 570px;
        height: 44px;
        font-weight: 700;
        font-size: 14px;
        padding: 0;
    }
    .product-info-right-info-buy .el-button {
        line-height: 40px;
        text-decoration: none;
    }
    .el-button--primary {
        color: rgb(255, 255, 255);
        background-color: black;
        border-color: black;
    }
    .product-info-right-info-buy .addcart {
        justify-content: center;
        color: #f89900;
        background: linear-gradient(0deg, #fff7ec, #fff7ec), #eee;
        border: 1px solid #f89900;
        border-radius: 4px;
        cursor: pointer;
        will-change: filter;
        transition: filter .5s;
    }

    .el-dialog__header {
        padding: 0 0 0 25px;
        border-radius: 6px;
        height: 100%;
    }

    .el-dialog__header {
        height: 42px;
        background: #f5f5f5;
        padding: 0;
        /*display: flex*/

        align-items: center;
        border-radius: 4px 4px 0 0;
    }
    .popup__header{
        height: 18px;
        background: #f5f5f5;

    }
    .dialog-title {
        height: 31px;
        /*line-height: 50px;*/
        /*background-color: #f5f5f5;*/
        font-weight: 700;
        font-size: 18px;
        color: #333;
        border-radius: 6px;
    }
    .popup-title{
        font-weight: 700;
        font-size: 18px;
        color: #333;
        border-radius: 6px;
        padding: 10px 20px;
        background: #f5f5f5;
    }
    .product-info-left .gallery-thumbs .swiper-slide:active {
        border: 1px solid black !important;
    }

    .gallery-thumbs .swiper-slide.swiper-slide-active {
        border: 1px solid black !important;
    }
    .gallery-thumbs .swiper-slide.swiper-slide-active:active {
        border: 1px solid black !important;
    }

    .gallery-thumbs .swiper-slide.active-thumb {
        border: 2px solid black;
    }

    /* Responsive adjustments */

    /* PC (default, >1024px) - Keep original styles */

    /* Tablet (768px - 1024px) */
    @media (min-width: 768px) and (max-width: 1024px) {
        .banner-content > .content {
            width: 50%;
            height: auto;
            padding: 1rem;
        }

        .banner-content > .content > p, .banner-content > .content > h3 {
            font-size: 2rem;
        }

        .banner-content > .content > div {
            padding: 0 1.5rem;
            height: 2.5rem;
            line-height: 2.5rem;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .main-classification-item span {
            max-width: 80px;
            font-size: 0.9rem;
        }

        .img-content {
            width: 80px;
            height: 80px;
        }

        .main-daily-content {
            grid-template-columns: repeat(3, 1fr); /* 3 columns for tablet */
            gap: 1rem;
        }

        .main-recommend-content {
            grid-template-columns: repeat(3, 1fr); /* 3 columns */
            gap: 1rem;
        }

        .icon-tips-bottom {
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .icon-tips-bottom-item {
            flex: 1 1 45%;
            margin-bottom: 1rem;
        }

        .popup-content {
            width: 90%;
            max-height: 90%;
            margin: 5% auto;
            padding: 1rem;
        }

        .product-info-left {
            width: 45%;
            margin-right: 1rem;
        }

        .product-info-right {
            max-width: 50%;
        }

        .photo-zoom-pro {
            height: 250px;
        }

        .zoom-result {
            width: 250px;
            height: 250px;
        }

        .product-info-right-info-buy .addcart, .product-info-right-info-buy .el-button {
            width: 48%;
            height: 40px;
            font-size: 13px;
        }

        .swiper-container.swiper-container-initialized.swiper-container-horizontal {
            height: 80px;
        }
    }

    /* Mobile (<768px) */
    @media (max-width: 767px) {
        .banner-content {
            height: auto;
            flex-direction: column;
            margin-top: 10px !important;
            margin-bottom: 10px !important;
            justify-content: start!important;
        }
        .main-banner-right flex-start .main-banner-right-group {
            /*display: flex;*/
            /*justify-content: center;*/
            /*align-content: center;*/
            /*flex-direction: column;*/
            width: auto!important;
        }

        .banner-content > .content {
            width: 100%!important;
            height: auto!important;
            padding: 1rem;
            text-align: center;
        }

        .banner-content > .content > p, .banner-content > .content > h3 {
            font-size: 1.5rem;
        }

        .banner-content > .content > div {
            padding: 0 1rem;
            height: 2rem;
            line-height: 2rem;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .main-classification-title h1 {
            font-size: 1.2rem;
        }

        .main-classification-title .all {
            font-size: 0.8rem;
        }

        .main-classification-item span {
            max-width: 70px;
            font-size: 0.8rem;
        }

        .img-content {
            width: 60px;
            height: 60px;
        }

        .main-daily-title h1 {
            font-size: 1.2rem;
        }

        .main-daily-title .all {
            font-size: 0.8rem;
        }

        .main-daily-content {
            grid-template-columns: repeat(2, 1fr); /* 2 columns for mobile */
            gap: 0.5rem;
        }

        .main-recommend h1 {
            font-size: 1.2rem;
        }

        .main-recommend-content {
            grid-template-columns: repeat(2, 1fr); /* 2 columns */
            gap: 0.5rem;
        }

        .pro-container .product {
            padding: 0.5rem;
        }

        .product .poster img {
            height: 120px;
        }

        .product h2 {
            font-size: 1rem;
        }

        .product p {
            font-size: 0.9rem;
        }

        .icon-tips-bottom {
            flex-direction: column;
            align-items: center;
        }

        .icon-tips-bottom-item {
            margin-bottom: 1rem;
            text-align: center;
        }

        .icon-tips-bottom-item i {
            font-size: 40px;
        }

        .popup-content {
            width: 95%;
            max-height: 95%;
            margin: 2.5% auto;
            padding: 0.5rem;
            border-radius: 8px;
        }

        .product-info {
            flex-direction: column;
            padding: 1rem;
            margin-top: 1rem !important;
        }

        .product-info-left {
            width: 100%;
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .product-info-right {
            max-width: 100%;
        }

        .photo-zoom-pro {
            height: 200px;
        }

        .zoom-result {
            display: none; /* Hide zoom on mobile for simplicity */
        }

        .gallery-thumbs {
            height: 60px;
            margin-top: 10px;
        }

        .product-info-right-title {
            font-size: 1.2rem;
            line-height: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .product-info-right-info-top {
            padding: 0.8rem 0;
        }

        .product-info-right-info-price h2 {
            font-size: 0.9rem;
        }

        .product-info-right-info .price {
            font-size: 1.2rem;
        }

        .product-info-right-info-tool-item {
            margin-right: 1rem;
            font-size: 0.9rem;
        }

        .product-info-right-info-tool-item i {
            font-size: 16px;
        }

        .product-info-right-info-des-item {
            margin-top: 1rem;
        }

        .product-info-right-info-des .label-title {
            min-width: 50px;
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }

        .product-info-right-info-des .attr-container {
            grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
            grid-column-gap: 0.5rem;
            grid-row-gap: 0.5rem;
        }

        .product-info-right-info-des .attr-item .attr-img {
            width: 100%;
            height: auto;
            padding: 0.3rem;
        }

        .product-info-right-info-buy {
            margin-top: 2rem;
            margin-bottom: 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .product-info-right-info-buy .addcart, .product-info-right-info-buy .el-button {
            width: 100%;
            height: 40px;
            font-size: 13px;
        }

        .swiper-container.swiper-container-initialized.swiper-container-horizontal {
            height: 60px;
        }

        /* Hide unnecessary elements on mobile */
        .zoom-lens {
            display: none;
        }
    }

</style>
@section('content')
    @include('chat.Frontend.ChatMessage')
    {{--    @php--}}
    {{--    dd($daily_Deals);--}}
    {{-- @endphp--}}
    {{--    <pre>--}}
    {{--    {{ print_r($bannersLeft->toArray(), true) }}--}}
    {{--</pre>--}}
    <div data-v-6684c942="" class="main-banner app-container">
        <div class="el-row" style="margin-left: -2.5px; margin-right: -2.5px">
            <div class="el-col el-col-24 el-col-xs-24 el-col-sm-24 el-col-md-14 el-col-lg-14 el-col-xl-14"
                 style="padding-left: 2.5px; padding-right: 2.5px">
                <div class="main-banner-left">
                    <div class="swiper-container swiper-container-initialized swiper-container-horizontal">
                        <div class="swiper-wrapper" style="
                              transform: translate3d(-698px, 0px, 0px);
                              transition-duration: 0ms;
                              ">

                            @foreach($bannersLeft as $banner)
                                <div class="swiper-slide {{ $loop->first ? 'swiper-slide-active' : '' }}">
                                    <img src="{{ !empty($banner->image) ? asset('filemanager/userfiles/'.$banner->image) : asset('images/no-image.png') }}"
                                         alt="{{ $banner->name }}">
                                </div>
                            @endforeach

                        </div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    </div>
                    <div class="el-loading-mask" style="display: none">
                        <div class="el-loading-spinner">
                            <svg viewBox="25 25 50 50" class="circular">
                                <circle cx="50" cy="50" r="20" fill="none" class="path"></circle>
                            </svg>
                            <!---->
                        </div>
                    </div>
                </div>
            </div>
            <div class="el-col el-col-24 el-col-xs-24 el-col-sm-24 el-col-md-10 el-col-lg-10 el-col-xl-10"
                 style="padding-left: 2.5px; padding-right: 2.5px">
                <div class="main-banner-right flex-start">
                    <div class="main-banner-right-group">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @if(isset($widgets['Banner6']))
                                    <div class="swiper-slide">
                                        <img src="{{ asset('filemanager/userfiles/'.$widgets['Banner6']->image) }}"
                                             alt="Banner6">
                                    </div>
                                @endif
                                @if(isset($widgets['Banner7']))
                                    <div class="swiper-slide">
                                        <img src="{{ asset('filemanager/userfiles/'.$widgets['Banner7']->image) }}"
                                             alt="Banner7">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @if(isset($widgets['Banner8']))
                                    <div class="swiper-slide">
                                        <img src="{{ asset('filemanager/userfiles/'.$widgets['Banner8']->image) }}"
                                             alt="Banner8">
                                    </div>
                                @endif
                                @if(isset($widgets['Banner9']))
                                    <div class="swiper-slide">
                                        <img src="{{ asset('filemanager/userfiles/'.$widgets['Banner9']->image) }}"
                                             alt="Banner9">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="main-banner-right-group">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @if(isset($widgets['Banner10']))
                                    <div class="swiper-slide">
                                        <img src="{{ asset('filemanager/userfiles/'.$widgets['Banner10']->image) }}"
                                             alt="Banner10">
                                    </div>
                                @endif
                                @if(isset($widgets['Banner11']))
                                    <div class="swiper-slide">
                                        <img src="{{ asset('filemanager/userfiles/'.$widgets['Banner11']->image) }}"
                                             alt="Banner11">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @if(isset($widgets['Banner12']))
                                    <div class="swiper-slide">
                                        <img src="{{ asset('filemanager/userfiles/'.$widgets['Banner12']->image) }}"
                                             alt="Banner12">
                                    </div>
                                @endif
                                @if(isset($widgets['Banner13']))
                                    <div class="swiper-slide">
                                        <img src="{{ asset('filemanager/userfiles/'.$widgets['Banner13']->image) }}"
                                             alt="Banner13">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-v-6684c942="" class="main-classification app-container">
        <div class="main-classification-title flex-between">
            <h1>{{__('lang.Shop_by_Category')}}</h1>
            <a href="{{route('classification')}}">
                <div class="all">{{__('lang.View_All')}} <i class="el-icon-arrow-right"></i></div>
            </a>
        </div>
        <div class="swiper-container swiper-container-initialized swiper-container-horizontal ">
            <div class="swiper-wrapper"
                 style="transform: translate3d(-436.364px, 0px, 0px); transition-duration: 0ms;">

                @foreach($categories as $category)
                    <div class="swiper-slide"  style="width: 109.091px">
                        <a href="{{ route('products', ['category' => $category->id]) }}" style="display: block">
                            <div class="main-classification-item flex-center">
                                <div class="img-content">
                                    <img src="{{ $category->image ? asset('filemanager/userfiles/'.$category->image) : asset('images/no-image.png') }}"
                                         alt="{{ $category->name }}"/>
                                </div>
                                <span title="{{ $category->name }}">{{ $category->name }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>

            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
        </div>



        <div class="main-classification top-shops app-container">
            <div class="main-classification-title flex-between">
                <h1>{{__('lang.Top_Shops')}}</h1>
                <div class="all">{{__('lang.View_All')}} <i class="el-icon-arrow-right"></i></div>
            </div>
            <div class="swiper-container swiper-container-initialized swiper-container-horizontal">
                <div class="swiper-wrapper">
                    @foreach($shops as $shop)
                        <div class="swiper-slide"  style="width:109.091px">
                            <a href="https://banhang.khoweb.top/store?storeId=1023">
                                <div class="main-classification-item flex-center">
                                    <div class="img-content">
                                        <img src="{{ $shop->logo_cua_hang ? asset('filemanager/userfiles/'.$shop->logo_cua_hang) : asset('images/no-image.png') }}"
                                             alt="{{ $shop->ten_cua_hang }}"/>
                                    </div>
                                    <span title="{{ $shop->ten_cua_hang }}">{{ $shop->ten_cua_hang }}</span>
                                </div>
                            </a>

                        </div>
                    @endforeach
                </div>
            </div>


            <div data-v-6684c942="" class="main-daily app-container">
                <div class="main-daily-title flex-between">
                    <h1>{{ __('lang.Daily_Deals') }}</h1>
                    <div class="all"><a href="/products">{{ __('lang.More') }}<i class="el-icon-arrow-right"></i></a></div>
                </div>
                <div class="main-daily-content"
                     style="translate: none; rotate: none; scale: none; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">

                    @forelse($daily_Deals as $product)
                        @php
                            $url = CommonHelper::writeUrl($product->slug, true, true);
                            $shop_product = \App\Modules\Product\Models\ShopProducts::where('product_id', $product->id)->first();
                        @endphp

                        <div class="pro-container">
                            <div class="product">
                                <div>
                                    <a href="{{ $url }}" style="text-decoration: none; color: inherit;">
                                        <div class="poster">
                                            <img src="{{ asset('filemanager/userfiles/'.$product->image) }}"
                                                 alt="{{ $product->name }}">
                                        </div>
                                        <h2>${{ (isset($shop_product->gia_ban) && $shop_product->gia_ban > 0) ? $shop_product->gia_ban : $product->price  }}</h2>
                                        <div class="product-res">
                                            {{ __('lang.Sold') }} {{ $product->total_sold ?? 0 }}
                                        </div>
                                        <p>{{ $product->name}}</p>
                                    </a>
                                </div>
                                <div class="product-footer">
                                    <div class="buy-btn-wrapper"
                                         data-id="{{$product->id}}"
                                         data-name="{{ $product->name }}"
                                         data-product-attribute=""
                                         data-price="{{ (isset($shop_product->gia_ban) && $shop_product->gia_ban > 0) ? $shop_product->gia_ban : $product->price }}" data-discount="{{ $shop_product->gia_giam_gia ?? 0 }}"
                                         data-img="{{ asset('filemanager/userfiles/'.$product->image) }}"
                                         data-album="{{ $product->image_extra }}"
                                         style="cursor:pointer; display:flex; align-items:center; gap:8px;">
                                        <i class="el-icon-shopping-cart-full"></i>
                                        <span class="buy-btn">{{ __('lang.Purchase_Now') }}</span>
                                        @if(count($product->grouped_attributes) > 0)
                                            @foreach($product->grouped_attributes as $attr)
                                                <div class="product-info-right-info-des-item flex-start product-attr" style="display: none">
                                            <span class="label-title sku-title">
                                                {{ $attr['parent_name'] }}:
                                                <span id="selected-attribute">
                                                    {{ $attr['children'][0]['name'] ?? '' }}
                                                </span>
                                            </span>
                                                    <div class="attr-container">
                                                        @foreach($attr['children'] as $k => $child)
                                                            <div class="attr-item {{ $k == 0 ? 'active' : '' }}"
                                                                 data-value="{{ $child['name'] }}"
                                                                 data-id="{{ $child['id'] }}">
                                                                @if(!empty($child['image']))
                                                                    @php
                                                                        $imageAttr = explode('|', $child['image']);
                                                                        $imgAttr = $imageAttr[0] ?? '';
                                                                    @endphp
                                                                    <div class="attr-img">
                                                                        <img src="{{ CommonHelper::getUrlImageThumb($imgAttr) ?? asset('images/no-image.png') }}" alt="">
                                                                    </div>
                                                                @else
                                                                    <div class="product-attribute-item">
                                                                        {{ $child['name'] }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>


                                    <div class="favorite" data-product-id="{{ $product->id }}"><i class="el-icon-star-off"></i></div>
                                </div>
                            </div>
                            {{-- giữ nguyên modal --}}
                            <div class="el-dialog__wrapper es-dialog" independent-modal="true" style="display: none">
                                <div role="dialog" aria-modal="true" aria-label="dialog" class="el-dialog el-dialog--center"
                                     style="margin-top: 15vh">
                                    <div class="el-dialog__header">
                                        <div class="dialog-title">
                                            <span>Add to the shopping cart</span>
                                        </div>
                                        <button type="button" aria-label="Close" class="el-dialog__headerbtn">
                                            <i class="el-dialog__close el-icon el-icon-close"></i>
                                        </button>
                                    </div>
                                    <div class="el-dialog__footer"><span></span></div>
                                </div>
                            </div>
                        </div>
                    @empty

                        <div class="pro-container">
                            <div class="product">
                                <div>
                                    <div class="poster">
                                        <img src="https://mall-test.s3.amazonaws.com/test/2023-03-28/f97b477d-9c6f-4495-bc3d-54d30a88d4ed.jpg"/>
                                    </div>
                                    <h2>$57.86</h2>
                                    <div class="product-res">Sold 4,961</div>
                                    <p>
                                        Blue Yeti Nano Premium USB Microphone for PC， Mac， Gaming...
                                    </p>
                                </div>
                                <div class="product-footer">
                                    <div>
                                        <i class="el-icon-shopping-cart-full"></i><span
                                                class="buy-btn"> Purchase Now </span>
                                    </div>
                                    <div class="favorite" data-product-id="{{ $product->id }}"><i class="el-icon-star-off"></i></div>
                                </div>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>

            <div data-v-6684c942="" class="banner-content" style="background-image: url('{{ asset('filemanager/userfiles/banner_01.183cb7e4.png') }}')"
            >
                <div data-v-6684c942="" class="content">
                    <p data-v-6684c942="">{{__('lang.Become_a_Seller')}}</p>
                    <p data-v-6684c942="">{{__('lang.Total_Commission')}}</p>
                    <h3 data-v-6684c942="">{{__('lang.Max._$100,000')}}</h3>
                    <div data-v-6684c942=""><a href="partnership">{{__('lang.Join_Now')}}</a></div>
                </div>
            </div>
            <div data-v-6684c942="" class="main-recommend app-container">
                <h1>{{ __('lang.Popular_Items') }}</h1>
                <div class="main-recommend-content">


                    @foreach($popular_Products  as $item)
                        @php
                            $url = CommonHelper::writeUrl($item->slug, true, true);
                            $shop_product = \App\Modules\Product\Models\ShopProducts::where('product_id', $item->id)->first();
                        @endphp
                        <div class="pro-container">
                            <div class="product">
                                <div>
                                    <a href="{{ $url }}" style="text-decoration: none; color: inherit;">
                                        <div class="poster">
                                            <img src="{{ asset('filemanager/userfiles/'.$item->image) }}"
                                                 alt="{{ $item->name }}"/>
                                        </div>
                                        <h2>${{ number_format((isset($shop_product->gia_ban) && $shop_product->gia_ban) ? $shop_product->gia_ban : $item->price, 2) }}</h2>
                                        <div class="product-res">
                                            {{ __('lang.Sold') }} {{ $item->total_sold ?? 0 }}
                                        </div>
                                        <p>{{ $item->name }}</p>
                                    </a>
                                </div>
                                <div class="product-footer">
                                    <!-- Trong foreach -->

                                    <div class="buy-btn-wrapper"
                                         data-id="{{$item->id}}"
                                         data-name="{{ $item->name }}"
                                         data-price="{{ (isset($shop_product->gia_ban) && $shop_product->gia_ban > 0) ? $shop_product->gia_ban : $item->price }}" data-discount="{{ $shop_product->gia_giam_gia ?? 0 }}"
                                         data-img="{{ asset('filemanager/userfiles/'.$item->image) }}"
                                         data-album="{{ $item->image_extra }}"
                                         style="cursor:pointer; display:flex; align-items:center; gap:8px;">
                                        <i class="el-icon-shopping-cart-full"></i>
                                        <span class="buy-btn">{{ __('lang.Purchase_Now') }}</span>
                                        @if(count($item->grouped_attributes) > 0)
                                            @foreach($item->grouped_attributes as $attri)
                                                <div class="product-info-right-info-des-item flex-start product-attr" style="display: none">
                                        <span class="label-title sku-title">
                                            {{ $attri['parent_name'] }}:
                                            <span id="selected-attribute">
                                                {{ $attri['children'][0]['name'] ?? '' }}
                                            </span>
                                        </span>
                                                    <div class="attr-container">
                                                        @foreach($attri['children'] as $k => $it)
                                                            <div class="attr-item {{ $k == 0 ? 'active' : '' }}"
                                                                 data-value="{{ $it['name'] }}"
                                                                 data-id="{{ $it['id'] }}">
                                                                @if(!empty($it['image']))
                                                                    @php
                                                                        $image = explode('|', $it['image']);
                                                                        $img = $image[0] ?? '';
                                                                    @endphp
                                                                    <div class="attr-img">
                                                                        <img src="{{ CommonHelper::getUrlImageThumb($img) ?? asset('images/no-image.png') }}" alt="">
                                                                    </div>
                                                                @else
                                                                    <div class="product-attribute-item">
                                                                        {{ $it['name'] }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="favorite" data-product-id="{{ $product->id }}"><i class="el-icon-star-off"></i></div>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <!---->
            <div data-v-6684c942="" class="icon-tips">
                <div class="icon-tips-bottom flex-between">
                    <div class="icon-tips-bottom-item flex-center">
                        <i class="ri-heart-line" style="font-size: 59px"></i><span>{{__('lang.100%_Original')}}</span>
                    </div>
                    <div class="icon-tips-bottom-item flex-center">
                        <i class="ri-skip-back-line" style="font-size: 59px"></i> <span>{{__('lang.7_days_return')}}</span>
                    </div>
                    <div class="icon-tips-bottom-item flex-center">
                        <i class="ri-truck-line" style="font-size: 59px"></i> <span> {{__('lang.Freight_discount')}} </span>
                    </div>
                    <div class="icon-tips-bottom-item flex-center">
                        <i class="ri-wallet-line" style="font-size: 59px"></i> <span>{{__('lang.Secure_Payment')}}</span>
                    </div>

                </div>
            </div>
            {{--            <!-- Popup sản pham -->--}}
            <div id="popup" class="popup">
                <div class="popup-content">
                    <div class="popup__header">
                        <div class="popup-title">Add to the shopping cart
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <!-- Toàn bộ product-info cho popup -->
                    <div class="product-info">
                        <div class="product-info-content flex-start">
                            <div class="product-info-left">
                                <div class="photo-zoom-wrapper">
                                    <div class="photo-zoom-pro">
                                        <img id="popupMainImage" src="" alt="Main Image">
                                        <div class="zoom-lens" id="zoom-lens">

                                        </div>
                                    </div>
                                    <div class="zoom-result" id="zoom-result">

                                    </div>
                                </div>
                                <!-- Thumbnail Swiper -->
                                <div class="swiper-container gallery-thumbs">
                                    <div class="swiper-wrapper" id="popupThumbWrapper">
                                        <!-- Ảnh thumbnail sẽ đổ vào đây bằng JS -->
                                    </div>
                                </div>
                            </div>

                            <div class="product-info-right">
                                <h1 id="popupTitle" class="product-info-right-title"></h1>
                                <div class="product-info-right-info">
                                    <div class="product-info-right-info-top flex-between">
                                        <div class="product-info-right-info-price flex-start">
                                            <h2>{{ __('lang.price') }}</h2>
                                            <span id="popupPrice" class="price"></span>
                                        </div>
                                        <div class="product-info-right-info-tool flex-start">
                                            <div class="product-info-right-info-tool-item flex-start">
                                                <i class="el-icon-service" style="color: var(--color-main); font-size: 20px;"></i>
                                                <span>Dịch vụ khách hàng</span>
                                            </div>
                                            <div data-product-id="" class="product-info-right-info-tool-item flex-start favorite">
                                                <i class="el-icon-star-off"></i><span>Yêu thích</span>
                                            </div>
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
                                            <span class="label-title">{{ __('lang.quantity') }}</span>
                                            <div class="el-input-number">
                                                <span role="button" class="el-input-number__decrease"><i class="el-icon-minus"></i></span>
                                                <span role="button" class="el-input-number__increase"><i class="el-icon-plus"></i></span>
                                                <div class="el-input">
                                                    <input type="text" data-product-id="" data-product-attribute="" product-shop-price="" product-discount="" autocomplete="off" max="9999" min="1" class="el-input__inner" name="quantity" value="1">
                                                </div>
                                            </div>
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
                                                <input type="hidden" name="discount" id="buy-now-discount" value="">
                                                <input type="hidden" name="attribute" id="buy-now-attribute">
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
                    </div>
                    <!-- /product-info -->
                </div>
            </div>




            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script>
                $('.btn-buy-now').on('click', function(e){
                    e.preventDefault();
                    if($('input[name="quantity"]').length){
                        let _this = $('input[name="quantity"]');
                        let id = _this.attr('data-product-id');
                        let qty = parseInt(_this.val());
                        let price = _this.attr('data-price');
                        let discount = _this.attr('data-discount');
                        let attrId = _this.attr('data-product-attribute');
                        $('#buy-now-id').val(id);
                        $('#buy-now-qty').val(qty);
                        $('#buy-now-price').val(price);
                        $('#buy-now-discount').val(discount);
                        $('#buy-now-attribute').val(attrId);
                        $('#buy-now-form').submit();
                    }
                });

                document.addEventListener("DOMContentLoaded", function () {
                    // Banner bên trái (ảnh to)
                    const mainBannerLeft = new Swiper(".main-banner-left .swiper-container", {
                        loop: true,
                        autoplay: {
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                        slidesPerView: 1,
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                    });

                    // Banner bên phải (ảnh nhỏ)
                    const mainBannerRight = new Swiper(".main-banner-right .swiper-container", {
                        loop: true,
                        autoplay: {
                            delay: 4000,
                            disableOnInteraction: false,
                        },
                        slidesPerView: 1,
                    });
                });

                const swiperCategory = new Swiper(".main-classification .swiper-container", {
                    slidesPerView: "auto",
                    // spaceBetween: 4, // giảm khoảng cách (px)
                    loop: false
                });

                const swiperShop = new Swiper(".main-classification .swiper-container", {
                    slidesPerView: "auto",
                    // spaceBetween: 4, // giống trên
                    loop: false
                });

            </script>
            <script>
                const popup = document.getElementById("popup");
                const closeBtn = document.querySelector(".close");
                const quantityInput = document.querySelector('input[name="quantity"]'); // input bạn muốn set attribute

                document.querySelectorAll(".buy-btn-wrapper").forEach(wrapper => {
                    wrapper.addEventListener("click", () => {
                        const product_id = wrapper.dataset.id;
                        const name = wrapper.dataset.name;
                        const price = wrapper.dataset.price;
                        const discount = wrapper.dataset.discount;
                        const img = wrapper.dataset.img;
                        const album = wrapper.dataset.album ? wrapper.dataset.album.split('|') : [];

                        // Gán dữ liệu vào popup
                        document.getElementById("popupTitle").textContent = name;
                        document.getElementById("popupPrice").textContent = "$" + price;
                        document.getElementById("popupMainImage").src = img;

                        // Gán background cho zoom-result
                        const zoomResult = document.getElementById("zoom-result");
                        zoomResult.style.backgroundImage = `url('${img}')`;
                        // zoomResult.style.backgroundSize = "cover";
                        // zoomResult.style.backgroundPosition = "center";

                        // Reset thumbnail
                        const thumbWrapper = document.getElementById("popupThumbWrapper");
                        thumbWrapper.innerHTML = "";

                        // Ảnh chính cũng add vào thumbnail
                        const firstThumb = document.createElement("div");
                        firstThumb.className = "swiper-slide";
                        firstThumb.innerHTML = `<img src="${img}" alt="${name}">`;
                        thumbWrapper.appendChild(firstThumb);

                        // Album ảnh
                        album.forEach(src => {
                            if (src.trim() !== "") {
                                const thumbSlide = document.createElement("div");
                                thumbSlide.className = "swiper-slide";
                                thumbSlide.innerHTML = `<img src="${src}" alt="${name}">`;
                                thumbWrapper.appendChild(thumbSlide);
                            }
                        });

                        // Cho click thumbnail đổi ảnh chính + background zoom
                        thumbWrapper.querySelectorAll("img").forEach(th => {
                            th.addEventListener("click", () => {
                                document.getElementById("popupMainImage").src = th.src;
                                zoomResult.style.backgroundImage = `url('${th.src}')`;

                                thumbWrapper.querySelectorAll(".swiper-slide").forEach(slide => {
                                    slide.classList.remove("active-thumb");
                                });

                                // Thêm active cho ảnh đang chọn
                                th.parentElement.classList.add("active-thumb");
                            });
                        });
                        var attr = wrapper.querySelectorAll(".product-attr");
                        console.log(attr);
                        const product_attribute = document.querySelectorAll(".product-info-right-info-des");
                        product_attribute.forEach(container => {
                            container.querySelectorAll(".product-attr").forEach(el => el.remove());
                            attr.forEach(el => {
                                var clone = el.cloneNode(true);
                                clone.style.display = "";
                                container.appendChild(clone);
                            });
                        });
                        var attributeActive = wrapper.querySelectorAll('.attr-item.active');
                        var attrIds = []
                        attributeActive.forEach(active => {
                            // map thành mảng data-id (chuỗi)
                            attrIds = Array.from(attributeActive).map(active => Number(active.dataset.id));
                        });
                        // console.log(attrIds);
                        const input = document.querySelector("input.el-input__inner[name='quantity']");
                        if (input) {
                            input.setAttribute("data-product-id", product_id);
                            input.setAttribute("product-shop-price", price ?? 0);
                            input.setAttribute("product-discount", discount ?? 0);
                            input.setAttribute("data-product-attribute", JSON.stringify(attrIds));
                        }
                        const popupFavorit = popup.querySelector(".favorite");
                        if (popupFavorit) {
                            popupFavorit.setAttribute("data-product-id", product_id);
                        }

                        // Hiện popup
                        popup.classList.add("show");
                    });
                });
                popup.addEventListener("click", (e) => {
                    const item = e.target.closest(".attr-item");
                    if (!item) return; // không click vào attr-item thì bỏ qua
                    console.log("Clicked attr-item in popup:", item.dataset);
                    const productAttr = item.closest(".product-attr");
                    if (productAttr) {
                        // Remove active cũ
                        productAttr.querySelectorAll(".attr-item").forEach(el => el.classList.remove("active"));
                        // Set active cho item click
                        item.classList.add("active");
                        // Update label text
                        const selectedAttr = productAttr.querySelector("#selected-attribute");
                        if (selectedAttr) {
                            selectedAttr.textContent = item.dataset.value;
                        }

                    }
                    updateSelectedAttributes();
                });

                // Đóng popup
                closeBtn.onclick = () => popup.classList.remove("show");
                window.onclick = (e) => { if (e.target == popup) popup.classList.remove("show"); };
                function updateSelectedAttributes() {
                    const activeItems = popup.querySelectorAll(".attr-item.active");
                    const ids = Array.from(activeItems).map(el => Number(el.dataset.id));

                    if (quantityInput) {
                        quantityInput.setAttribute("data-product-attribute", JSON.stringify(ids));
                        console.log("Updated data-product-attribute:", quantityInput.dataset.productAttribute);
                    }
                }
            </script>




            {{--            <script>--}}
            {{--                const mainImage = document.getElementById("popupMainImage");--}}
            {{--                const zoomResult = document.getElementById("zoom-result");--}}
            {{--                const zoomLens = document.getElementById("zoom-lens");--}}

            {{--                // Cập nhật background cho zoom-result khi đổi ảnh--}}
            {{--                function updateZoom() {--}}
            {{--                    zoomResult.style.backgroundImage = `url('${mainImage.src}')`;--}}
            {{--                    zoomResult.style.backgroundRepeat = "no-repeat";--}}
            {{--                    zoomResult.style.backgroundSize = (mainImage.width * 2) + "px " + (mainImage.height * 2) + "px";--}}
            {{--                }--}}

            {{--                // Khi click thumbnail → đổi ảnh chính + update zoom--}}
            {{--                document.getElementById("popupThumbWrapper").addEventListener("click", (e) => {--}}
            {{--                    if (e.target.tagName === "IMG") {--}}
            {{--                        mainImage.src = e.target.src;--}}
            {{--                        updateZoom();--}}
            {{--                    }--}}
            {{--                });--}}

            {{--                // Hiệu ứng zoom khi di chuột vào ảnh chính--}}
            {{--                mainImage.addEventListener("mousemove", moveLens);--}}
            {{--                zoomLens.addEventListener("mousemove", moveLens);--}}
            {{--                mainImage.addEventListener("mouseenter", updateZoom);--}}

            {{--                function moveLens(e) {--}}
            {{--                    e.preventDefault();--}}
            {{--                    const rect = mainImage.getBoundingClientRect();--}}

            {{--                    let x = e.pageX - rect.left - window.scrollX - (zoomLens.offsetWidth / 2);--}}
            {{--                    let y = e.pageY - rect.top - window.scrollY - (zoomLens.offsetHeight / 2);--}}

            {{--                    // Giới hạn lens trong ảnh--}}
            {{--                    if (x > mainImage.width - zoomLens.offsetWidth) x = mainImage.width - zoomLens.offsetWidth;--}}
            {{--                    if (x < 0) x = 0;--}}
            {{--                    if (y > mainImage.height - zoomLens.offsetHeight) y = mainImage.height - zoomLens.offsetHeight;--}}
            {{--                    if (y < 0) y = 0;--}}

            {{--                    zoomLens.style.left = x + "px";--}}
            {{--                    zoomLens.style.top = y + "px";--}}

            {{--                    // Tỉ lệ phóng đại--}}
            {{--                    const cx = zoomResult.offsetWidth / zoomLens.offsetWidth;--}}
            {{--                    const cy = zoomResult.offsetHeight / zoomLens.offsetHeight;--}}

            {{--                    zoomResult.style.backgroundPosition = `-${x * cx}px -${y * cy}px`;--}}
            {{--                }--}}

            {{--            </script>--}}

            <script>

                // Khi document sẵn sàng
                document.addEventListener('DOMContentLoaded', function() {
                    const slides = document.querySelectorAll('.gallery-thumbs .swiper-slide');

                    slides.forEach(slide => {
                        slide.addEventListener('click', function() {
                            // Bỏ active của slide khác
                            slides.forEach(s => s.classList.remove('swiper-slide-active'));
                            // Thêm active cho slide vừa click
                            this.classList.add('swiper-slide-active');
                        });
                    });
                });
            </script>

            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
            <link rel="stylesheet" href="{{ asset(config('core.admin_asset').'/css/swiper-setup.css') }}"/>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/swiper_setup.js') }}"></script>
            <script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/product.js') }}"></script>
@endsection
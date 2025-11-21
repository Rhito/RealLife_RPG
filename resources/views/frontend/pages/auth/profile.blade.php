@extends('frontend.layout.layout')
@section('content')
    <style>
        .user-avatar {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cover-up {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            text-align: center;
            padding: 8px;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .user-avatar:hover .cover-up {
            opacity: 1;
        }

        /* Ẩn section */
        .hidden {
            display: none;
        }

        /* Style nút menu */
        .menu {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .menu .tab-btn {
            padding: 8px 16px;
            border: none;
            background-color: #f3f4f6;
            cursor: pointer;
            border-radius: 6px;
            transition: 0.3s;
            font-size: 16px;
        }

        .menu .tab-btn:hover {
            background-color: #e5e7eb;
        }

        /* Nút đang active */
        .menu .tab-btn.active {
            background-color: #4f46e5;
            color: white;
            font-weight: bold;
        }

        #address-popup {
            display: none; /* ẩn ban đầu */
            position: fixed; /* cố định trên viewport */
            top: 50%; /* canh giữa theo chiều dọc */
            left: 50%; /* canh giữa theo chiều ngang */
            transform: translate(-50%, -50%); /* dịch để thật sự ở giữa */
            width: 600px;
            z-index: 9999; /* đảm bảo popup ở trên cùng */
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            border-radius: 8px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .el-select-dropdown {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #dcdfe6;
            background: #fff;
            position: absolute;
            z-index: 10;
        }

        .el-select-dropdown__list li {
            padding: 5px 10px;
            cursor: pointer;
        }

        .el-select-dropdown__list li:hover {
            background-color: #f5f7fa;
        }

        .el-select-dropdown__empty {
            text-align: center;
            color: #c0c4cc;
            padding: 5px 0;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .custom-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            background-color: #fff;
            font-size: 15px;
            color: #374151;
            outline: none;
            appearance: none;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        /* Hiệu ứng hover */
        .custom-select:hover:not(:disabled) {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        /* Hiệu ứng khi focus */
        .custom-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }

        /* Placeholder mặc định màu xám */
        .custom-select option[disabled][selected] {
            color: #9ca3af;
        }

        /* Trạng thái disabled */
        .custom-select:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
            color: #9ca3af;
        }

        /* Thêm mũi tên đẹp */
        .form-group::after {
            content: "▼";
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            font-size: 12px;
            color: #6b7280;
            pointer-events: none;
        }

        .custom-select:disabled + .form-group::after {
            color: #d1d5db;
        }
        .form-phone { display:flex; gap:8px; align-items:center; }
        #country_select { min-width:160px; padding:8px; border:1px solid #ddd; border-radius:6px; background:#fff; }
        .el-input__inner { box-sizing: border-box; padding:10px; border:1px solid #dcdfe6; border-radius:6px; }
        /* Fallback / fix cho dropdown country của intl-tel-input */
        .iti__country-list, .iti__country { box-sizing: border-box; }

        /* Ẩn mặc định và đặt position absolute để không đẩy layout */
        .iti__country-list {
            position: absolute !important;
            display: none !important;
            max-height: 240px;
            overflow-y: auto;
            overflow-x: hidden;
            width: 280px; /* có thể điều chỉnh */
            background: #fff;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            z-index: 99999 !important;
            padding: 6px 0;
            border-radius: 6px;
        }

        /* Option/row trong list */
        .iti__country {
            padding: 6px 12px;
            white-space: nowrap;
            cursor: pointer;
        }

        /* Hover */
        .iti__country:hover {
            background: #f5f7fb;
        }

        /* Khi list mở, intl-tel-input sẽ thêm class .iti__country-list--show hoặc inline style.
           Bảo đảm nó hiển thị khi có attribute style/display từ plugin. */
        .iti__country-list[style*="display:block"], .iti__country-list--show {
            display: block !important;
        }
        .tab-btn {
            display: inline-flex; /* hoặc flex nếu muốn chiếm toàn bộ width */
            align-items: center;  /* căn giữa theo chiều dọc */
            gap: 8px;             /* khoảng cách giữa icon và chữ */
            padding: 6px 12px;    /* tuỳ chỉnh padding */
            border: none;
            background: none;
            cursor: pointer;
        }



        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* ===== Header của bảng ===== */
        thead {
            background: #f9fafb;
        }

        thead th {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            color: #555;
            padding: 12px 10px;
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
        }

        /* ===== Body của bảng ===== */
        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        tbody tr:hover {
            background: #f1f5f9;
        }

        tbody td {
            padding: 12px 10px;
            font-size: 14px;
            color: #333;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        /* ===== Ảnh sản phẩm ===== */
        tbody td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
            transition: transform 0.2s ease;
        }

        tbody td img:hover {
            transform: scale(1.1);
        }

        /* ===== Tổng tiền và các thông tin quan trọng ===== */
        .summary-section p {
            font-size: 14px;
            margin-bottom: 5px;
            color: #444;
        }

        .summary-section p strong {
            color: #111;
        }

        .summary-section .total {
            font-size: 18px;
            color: #ff5000;
            font-weight: bold;
            margin-top: 10px;
        }

        /* ===== Container của bảng ===== */
        .order-detail-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            table, thead, tbody, tr, td, th {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tbody td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                text-align: left;
                font-weight: 600;
                color: #555;
            }
        }
    </style>
    <div data-v-2c463d0c="" class="app-container">
        <div data-v-493b77e6="" data-v-2c463d0c="" class="nav-bar">
            <div data-v-493b77e6="" class="person">
                <div class="user-avatar">
                    <img src="{{ asset('filemanager/userfiles/' . $user->image) }}" alt="avatar" id="avatarPreview">
                    <div class="cover-up" onclick="document.getElementById('avatarInput').click()">Change</div>
                </div>

                <!-- Form upload -->
                <form id="avatarForm" action="{{ route('user.updateAvatar') }}" method="POST" enctype="multipart/form-data" style="display:none;">
                    @csrf
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit();">
                </form>

                <p data-v-493b77e6="" class="name">{{$user->email ?? $user->tel}}</p>
                <p data-v-493b77e6="" class="person-id"> ID: {{$user->id}} <i data-v-493b77e6=""
                                                                        class="el-icon-copy-document"></i></p>
            </div>
            <div data-v-493b77e6="" class="el-dialog__wrapper" style="display: none;">
                <div role="dialog" aria-modal="true" aria-label="Select a photo" class="el-dialog"
                     style="margin-top: 15vh; width: 442px;">
                    <div class="el-dialog__header">
                        <span class="el-dialog__title">Select a photo</span>
                        <button type="button" aria-label="Close" class="el-dialog__headerbtn">
                            <i class="el-dialog__close el-icon el-icon-close"></i>
                        </button>
                    </div><!---->
                    <div class="el-dialog__footer">
                        <span data-v-493b77e6="" class="dialog-afooter">
                            <button data-v-493b77e6="" type="button" class="el-button el-button--primary"><!----><!---->
                                <span>{{__('lang.confirm')}}</span>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div data-v-493b77e6="" class="el-divider el-divider--horizontal"><!----></div>
            <ul data-v-493b77e6="">
                <li data-v-493b77e6="">
                    <button  class="tab-btn active" onclick="showSection('dashboard', event)" aria-current="page" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-speedometer mr-1" viewBox="0 0 16 16">
                            <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2M3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.39.39 0 0 0-.029-.518z"/>
                            <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.95 11.95 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0"/>
                        </svg> {{__('lang.dashboard')}}
                    </button>
                </li>
{{--                <li data-v-493b77e6=""><button class="tab-btn" onclick="showSection('wallet', event)">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-wallet2 mr-1" viewBox="0 0 16 16">--}}
{{--                            <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>--}}
{{--                        </svg>--}}
{{--                        {{__('lang.my_wallet')}}--}}
{{--                    </button>--}}
{{--                </li>--}}
                <li data-v-493b77e6="">
                    <button class="tab-btn" onclick="showSection('order', event)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-journal-text mr-1" viewBox="0 0 16 16">
                            <path d="M5 10.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                        {{__('lang.my_orders')}}
                    </button>
                </li>
                <li data-v-493b77e6="">
                    <button class="tab-btn" data-v-493b77e6="" href="#/userInfo/collect-goods"  onclick="showSection('fvrshop', event)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-bag-heart mr-1" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0M14 14V5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1M8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                        </svg>
                        {{__('lang.favorite_products')}}
                    </button>
                </li>
                <li data-v-493b77e6="">
                    <button class="tab-btn" data-v-493b77e6="" href="#/userInfo/collect-shop"  onclick="showSection('flstore', event)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-shop mr-1" viewBox="0 0 16 16">
                            <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/>
                        </svg>
                        {{__('lang.follow_store')}}
                    </button>
                </li>
                <li data-v-493b77e6="">
                    <button class="tab-btn" data-v-493b77e6="" href="#/userInfo/setup"  onclick="showSection('setting', event)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-gear mr-1" viewBox="0 0 16 16">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                        </svg>
                        {{__('lang.setup')}}
                    </button>
                </li>
            </ul>
{{--            <div data-v-493b77e6=""--}}
{{--                 style="display: flex; flex-direction: column; width: 100%; align-items: center; font-size: 14px; font-weight: 600; margin-top: 60px;">--}}
{{--                <span data-v-493b77e6="">--}}
{{--                    {{__('lang.scan_qr_download_app')}}--}}
{{--                </span>--}}
{{--            </div>--}}
        </div>
        <div data-v-2c463d0c="" class="set-container">
            <div id="dashboard-section" class="content-section">
                @include('frontend.pages.auth.partials.dashboard')
            </div>

            <!-- Wallet -->
            <div id="wallet-section" class="content-section hidden">
                @include('frontend.pages.auth.partials.wallet')
            </div>
            <div id="order-section" class="content-section hidden">
                @include('frontend.pages.auth.partials.order')
            </div>
            <div id="setting-section" class="content-section hidden">
                @include('frontend.pages.auth.partials.setting')
            </div>
            <div id="flstore-section" class="content-section hidden">
                @include('frontend.pages.auth.partials.flstore')
            </div>
            <div id="fvrshop-section" class="content-section hidden">
                @include('frontend.pages.auth.partials.fvrshop')
            </div>
        </div>
    </div>

    <div id="address-popup" role="dialog" aria-modal="true" style="margin-top: 15vh; width: 600px; display: none;"
         aria-label="dialog" class="el-dialog el-dialog--center es-dialog"
         style="margin-top: 15vh; width: 600px; display: none;"> <!-- ẩn ban đầu -->
        <div class="el-dialog__header">
            <div class="dialog-title"><span>{{__('lang.add_new_address')}}/{{__('lang.change_address')}}</span></div>
            <button type="button" aria-label="Close" class="el-dialog__headerbtn" id="close-address-popup">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
            <div class="add-address-content">
                <form class="el-form" action="{{ route('address.store') }}" method="POST">
{{--                    @csrf--}}
                    <div class="el-form-item is-required">
                        <div class="el-form-item__content">
                            <input type="text" name="recipient_name" placeholder="{{__('lang.recipient_name')}}" maxlength="64" class="el-input__inner" required>
                        </div>
                    </div>

                    <div class="el-form-item is-required">
                        <div class="el-form-item__content">
                            <input type="email" name="email" placeholder="Email" maxlength="64" class="el-input__inner">
                        </div>
                    </div>

{{--                    <div class="el-form-item is-required">--}}
{{--                        <div class="el-form-item__content">--}}
{{--                            <input type="text" name="phone" placeholder="Please enter the mobile number" maxlength="20" class="el-input__inner" required>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-phone" style="margin-bottom: 10px">
                        {{-- Select hiển thị mã vùng --}}
                        <select id="country_select" name="mobile_country_code" aria-label="Country code">
                            <option value="">Loading...</option>
                        </select>

                        {{-- Input phone VISIBLE để intl-tel-input attach --}}
                        <input type="tel"
                               id="mobile"
                               name="phone"
                               placeholder="{{__('lang.phone_number')}}"
                               maxlength="30"
                               class="el-input__inner"
                               required
                               style="flex:1;" />

                        {{--                                             Hidden: gửi E.164 lên server --}}
                        <input type="hidden" name="mobile_e164" id="mobile_e164" value="">
                    </div>

                    <div class="check_address">
                        <div class="form-group">
                            <select name="country_code" id="country" class="custom-select" required>
                                <option value="" disabled selected>-- {{__('lang.choose_country')}} --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->iso }}">{{ $country->country }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <select name="province_code" id="province" class="custom-select" required disabled>
                                <option value="" disabled selected>-- {{__('lang.choose_province')}} --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <select name="district_code" id="district" class="custom-select" required disabled>
                                <option value="" disabled selected>-- {{__('lang.choose_district')}} --</option>
                            </select>
                        </div>
                    </div>

                    <div class="el-form-item">
                        <input type="text" name="postal_code" placeholder="{{__('lang.postal_code')}}" maxlength="32" class="el-input__inner">
                    </div>

                    <div class="el-form-item">
                        <textarea name="address" placeholder="{{__('lang.detailed_address')}}" rows="4" maxlength="255" class="el-textarea__inner" required></textarea>
                    </div>

                    <div class="el-form-item">
                        <label>
                            <input type="checkbox" name="is_default" value="1">
                            {{__('lang.set_as_default_address')}}
                        </label>
                    </div>

                    <div class="el-form-item">
                        <button type="submit" class="el-button el-button--primary">{{__('lang.confirm')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Popup chi tiết đơn hàng -->
    <div id="billDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white w-[100%] max-w-7xl p-6 rounded-lg shadow-lg relative overflow-hidden flex">

            <!-- Nút đóng -->
            <button onclick="closeBillDetail()"
                    class="absolute top-2 right-2 text-gray-600 hover:text-black text-lg">✖</button>

            <!-- Phần chi tiết đơn -->
            <div id="billDetailContent" class="flex-1 pr-4 border-r">
                <p class="text-gray-500">Đang tải dữ liệu đơn hàng...</p>
            </div>

            <!-- Chat box bên phải -->
            <div class="w-[400px] flex flex-col rounded-lg shadow-lg border border-gray-300 ml-4">
                <!-- Header -->
                <div class="bg-blue-600 text-white p-3 flex justify-between items-center rounded-t-lg">
                    <span class="font-semibold">💬 Chat với Shop</span>
                </div>

                <!-- Tin nhắn -->
                <div id="chatMessages"
                     class="flex-1 p-4 overflow-y-auto bg-white text-base space-y-3"
                     style="min-height: 320px; max-height: 320px; width: 320px;">
{{--                    <p class="text-gray-400 text-center">Chưa có tin nhắn...</p>--}}
                </div>

                <!-- Nhập tin -->
                <div class="p-3 border-t flex items-center gap-2 bg-gray-50 rounded-b-lg">
                    <!-- giữ billId ẩn -->
                    <input type="hidden" id="chatBillId" value="">
                    <input type="hidden" id="chatShopId" value="">
                    <!-- Chọn file -->
                    <label for="chatFile" class="cursor-pointer bg-gray-200 px-3 py-2 rounded-lg hover:bg-gray-300">
                        📎
                    </label>
                    <input type="text" id="chatInput"
                           class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nhập tin nhắn...">


                    <input type="file" id="chatFile" class="hidden">

                    <!-- Nút gửi -->
                    <button onclick="sendUserMessage()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                        Gửi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gửi tin nhắn khi bấm Enter
        document.addEventListener("DOMContentLoaded", function() {
            const chatInput = document.getElementById("chatInput");

            chatInput.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault(); // ngăn xuống dòng
                    sendUserMessage();      // gọi hàm gửi tin
                }
            });
        });
        async function sendUserMessage() {
            const billId = document.getElementById('chatBillId').value;
            const content = document.getElementById('chatInput').value;
            const fileInput = document.getElementById('chatFile');
            const chatMessages = document.getElementById('chatMessages');

            // Thêm kiểm tra: không gửi tin nhắn rỗng nếu không có file
            if (!content.trim() && fileInput.files.length === 0) {
                return; // Dừng lại nếu không có gì để gửi
            }

            if (!billId) {
                alert("Không tìm thấy mã đơn hàng để gửi tin nhắn.");
                return;
            }

            let formData = new FormData();
            formData.append('bill_id', billId);
            formData.append('content', content);
            if (fileInput.files.length > 0) {
                formData.append('file', fileInput.files[0]);
            }

            try {
                const response = await fetch("{{ route('chat.sendMessageUs') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json", // Thêm header này để đảm bảo Laravel biết bạn muốn nhận JSON
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    // ✅ SỬA ĐỔI QUAN TRỌNG: Lấy đối tượng message từ result
                    const message = result.message;

                    // Xây dựng HTML từ đối tượng message
                    let msgHtml = `<div class="text-right">
                          <div class="inline-block bg-blue-500 text-white px-3 py-2 rounded-lg max-w-xs break-words">
                            ${message.content ? message.content.replace(/\n/g, '<br>') : ""}
                            ${message.file_url ? `<br><a href="${message.file_url}" target="_blank" class="underline text-white font-semibold">Tệp đính kèm 📎</a>` : ""}
                          </div>
                       </div>`;

                    chatMessages.insertAdjacentHTML('beforeend', msgHtml);

                    // ✅ cập nhật lastMessageId để polling bỏ qua
                    lastMessageId = message.id;
                    // Xóa nội dung input
                    document.getElementById('chatInput').value = "";
                    fileInput.value = ""; // Đặt lại input file

                    // Cuộn xuống tin nhắn mới nhất
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } else {
                    alert(result.error || "Gửi tin nhắn thất bại. Vui lòng thử lại.");
                }
            } catch (err) {
                console.error("Lỗi khi gửi tin nhắn:", err);
                alert("Đã xảy ra lỗi kết nối. Vui lòng kiểm tra lại đường truyền.");
            }

        }

    </script>

    <script>
        let currentBillId = null;
        let lastMessageId = 0;
        let pollingInterval = null;

        // Load tất cả tin nhắn khi mở chat
        function loadChatMessages(billId) {
            const chatBox = document.getElementById('chatMessages');
            chatBox.innerHTML = '<p class="text-gray-400 text-center">Đang tải tin nhắn...</p>';

            currentBillId = billId;
            lastMessageId = 0;

            fetch(`/chat/messages/${billId}`)
                .then(res => res.json())
                .then(data => {
                    chatBox.innerHTML = '';
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(m => {
                            renderChatMessage(m);
                            lastMessageId = m.id; // ✅ gán id cuối cùng
                        });
                        chatBox.scrollTop = chatBox.scrollHeight;
                    } else {
                        chatBox.innerHTML = '<p class="text-gray-400 text-center">Chưa có tin nhắn...</p>';
                    }
                    // ✅ bắt đầu polling sau khi load lần đầu
                    startPollingChat();
                })
                .catch(err => {
                    chatBox.innerHTML = '<p class="text-red-500 text-center">Lỗi tải tin nhắn</p>';
                    console.error("Fetch error:", err);
                });
        }

        // Render 1 tin nhắn
        function renderChatMessage(m) {
            const chatBox = document.getElementById('chatMessages');
            const div = document.createElement('div');
            div.classList.add('mb-2');

            if (m.sender_id == {{ Auth::id() }}) {
                div.classList.add('text-right');
                div.innerHTML = `
            <span class="inline-block bg-blue-500 text-white px-3 py-2 rounded-lg">${m.message}</span>
            <div class="text-xs text-gray-400 mt-1">${m.time}</div>
        `;
            } else {
                div.classList.add('text-left');
                div.innerHTML = `
            <span class="inline-block bg-gray-200 text-black px-3 py-2 rounded-lg">${m.message}</span>
            <div class="text-xs text-gray-400 mt-1">${m.time}</div>
        `;
            }

            if (m.file_url) {
                div.innerHTML += `<br><a href="${m.file_url}" target="_blank" class="text-blue-500 underline">📎 File</a>`;
            }

            chatBox.appendChild(div);
        }

        // Polling lấy tin nhắn mới
        function startPollingChat() {
            if (pollingInterval) clearInterval(pollingInterval);

            pollingInterval = setInterval(() => {
                if (!currentBillId || !lastMessageId) return;

                fetch(`/chat/messages/${currentBillId}?last_id=${lastMessageId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(m => {
                                renderChatMessage(m);
                                lastMessageId = m.id; // ✅ cập nhật id mới nhất
                            });

                            const chatBox = document.getElementById('chatMessages');
                            chatBox.scrollTop = chatBox.scrollHeight;
                        }
                    })
                    .catch(err => console.error("Polling error:", err));
            }, 3000); // mỗi 3 giây
        }
    </script>





    {{--    Chuyển trang--}}
    <script>
        function showSection(section, event = null) {
            // Ẩn tất cả section
            document.querySelectorAll('.content-section').forEach(el => {
                el.classList.add('hidden');
            });

            // Hiển thị section được chọn
            document.getElementById(section + '-section').classList.remove('hidden');

            // Bỏ active ở tất cả nút
            document.querySelectorAll('.menu .tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Active nút hiện tại
            if (event) {
                event.target.classList.add('active');
            } else {
                // Nếu load từ localStorage -> tìm nút tương ứng để active
                document.querySelector(`.menu .tab-btn[onclick*="${section}"]`).classList.add('active');
            }

            // Lưu trạng thái tab vào localStorage
            localStorage.setItem('activeTab', section);
        }

        // Khi load trang
        document.addEventListener('DOMContentLoaded', function () {
            // Lấy tab đã lưu trước đó
            // const savedTab = localStorage.getItem('activeTab') || 'dashboard';
            // showSection(savedTab);
            const urlParams = new URLSearchParams(window.location.search);
            const tabFromUrl = urlParams.get('dashboard'); // ví dụ ?tab=profile

            // Nếu URL có tab, ưu tiên dùng tab này
            const savedTab = tabFromUrl || localStorage.getItem('activeTab') || 'dashboard';

            // Hiển thị tab
            showSection(savedTab);

            // Nếu URL có tab => cập nhật lại localStorage
            if (tabFromUrl) {
                localStorage.setItem('activeTab', tabFromUrl);
            }
        });
    </script>

{{--Mở popup thêm địa chỉ--}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const popup = document.getElementById('address-popup');
            const closeBtn = document.getElementById('close-address-popup');
            const openAddBtn = document.getElementById('open-address-popup2'); // Thêm mới
            const form = popup.querySelector('form');

            const mobileInput = document.getElementById('mobile');
            const countrySelect = document.getElementById('country_select');

            // --- Mở popup Thêm mới ---
            openAddBtn.addEventListener('click', () => {
                popup.style.display = 'block';

                // Reset form
                form.reset();

                // Reset selects
                $('#country').val('').trigger('change');
                $('#province').html('<option value="" disabled selected>-- {{__("lang.choose_province")}} --</option>').prop('disabled', true);
                $('#district').html('<option value="" disabled selected>-- {{__("lang.choose_district")}} --</option>').prop('disabled', true);

                // Reset phone
                if (mobileInput && window.intlTelInputGlobals) {
                    const iti = window.intlTelInputGlobals.getInstance(mobileInput);
                    if (iti) iti.setNumber('');
                }
                if (countrySelect) countrySelect.selectedIndex = 0;
            });

            // --- Mở popup Sửa ---
            document.querySelectorAll('.option-item[data-action="edit"]').forEach(editBtn => {
                editBtn.addEventListener('click', function () {
                    const item = this.closest('.item');

                    popup.style.display = 'block';

                    // Điền dữ liệu vào form
                    form.recipient_name.value = item.dataset.recipientName || '';
                    form.email.value = item.dataset.email || '';
                    form.address.value = item.dataset.address || '';
                    form.postal_code.value = item.dataset.postalCode || '';
                    form.is_default.checked = item.dataset.default == '1';

                    // Country select
                    $('#country').val(item.dataset.countryCode).trigger('change');

                    // Sau khi load province via ajax thì chọn province
                    setTimeout(() => {
                        $('#province').val(item.dataset.provinceCode).trigger('change');
                    }, 200);

                    // Sau khi load district via ajax thì chọn district
                    setTimeout(() => {
                        $('#district').val(item.dataset.districtCode).trigger('change');
                    }, 400);

                    // Phone
                    if (mobileInput && window.intlTelInputGlobals) {
                        const iti = window.intlTelInputGlobals.getInstance(mobileInput);
                        if (iti) iti.setNumber(item.dataset.phone || '');
                    }
                    if (countrySelect) {
                        // tìm option theo mã vùng
                        const opt = Array.from(countrySelect.options).find(o => o.value === item.dataset.phone?.replace('+',''));
                        if (opt) opt.selected = true;
                    }

                    // Thêm hidden field id nếu cần
                    if (!form.querySelector('input[name="address_id"]')) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'address_id';
                        form.appendChild(input);
                    }
                    form.address_id.value = item.dataset.id;
                });
            });

            // --- Đóng popup ---
            closeBtn.addEventListener('click', () => popup.style.display = 'none');
            window.addEventListener('click', (e) => { if (e.target === popup) popup.style.display = 'none'; });
        });
        if (form) {
            form.addEventListener('submit', function (e) {
                if (mobileInput && window.intlTelInputGlobals) {
                    let iti = window.intlTelInputGlobals.getInstance(mobileInput);

                    // Nếu chưa attach, attach tạm
                    if (!iti) {
                        iti = window.intlTelInput(mobileInput, {
                            initialCountry: "auto",
                            geoIpLookup: function(success) { success('vn'); },
                            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
                        });
                    }

                    // Kiểm tra: input rỗng hoặc số không hợp lệ
                    const number = mobileInput.value.trim();
                    if (!number || !iti.isValidNumber()) {
                        e.preventDefault();
                        alert('Vui lòng nhập số điện thoại hợp lệ.');
                        mobileInput.focus();
                        return;
                    }

                    // Gán E.164 cho hidden field
                    const hiddenE164 = document.getElementById('mobile_e164');
                    if (hiddenE164) hiddenE164.value = iti.getNumber();
                } else {
                    // fallback: nếu không dùng intl-tel-input
                    if (!mobileInput.value.trim()) {
                        e.preventDefault();
                        alert('Vui lòng nhập số điện thoại.');
                        mobileInput.focus();
                        return;
                    }
                }
            });
        }

    </script>

    {{--    Lấy thông tin đất nước, tỉnh, huyện--}}
    <script>
        $(document).ready(function () {
            const provinceSelect = $('#province');
            const districtSelect = $('#district');

            // Khi chọn Country -> load Province
            $('#country').on('change', function () {
                let countryCode = $(this).val();

                provinceSelect.prop('disabled', true).html('<option value="">-- Loading... --</option>');
                districtSelect.prop('disabled', true).html('<option value="">-- Chọn District --</option>');

                if (countryCode) {
                    $.ajax({
                        url: '/api/provinces/' + countryCode,
                        type: 'GET',
                        success: function (data) {
                            let options = '<option value="">-- Chọn Province --</option>';
                            data.forEach(function (province) {
                                options += `<option value="${province.admin1_code}">${province.name}</option>`;
                            });
                            provinceSelect.html(options).prop('disabled', false);
                        },
                        error: function () {
                            provinceSelect.html('<option value="">Không thể tải dữ liệu</option>');
                        }
                    });
                } else {
                    provinceSelect.html('<option value="">-- Chọn Province --</option>');
                }
            });

            // Khi chọn Province -> load District
            provinceSelect.on('change', function () {
                let countryCode = $('#country').val();
                let admin1Code = $(this).val();

                districtSelect.prop('disabled', true).html('<option value="">-- Loading... --</option>');

                if (admin1Code) {
                    $.ajax({
                        url: `/api/districts/${countryCode}/${admin1Code}`,
                        type: 'GET',
                        success: function (data) {
                            let options = '<option value="">-- Chọn District --</option>';
                            data.forEach(function (district) {
                                options += `<option value="${district.admin2_code}">${district.name}</option>`;
                            });
                            districtSelect.html(options).prop('disabled', false);
                        },
                        error: function () {
                            districtSelect.html('<option value="">Không thể tải dữ liệu</option>');
                        }
                    });
                } else {
                    districtSelect.html('<option value="">-- Chọn District --</option>');
                }
            });
        });
    </script>
{{--    xử lý sđt--}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileInput = document.getElementById('mobile');
            const countrySelect = document.getElementById('country_select');
            const hiddenE164 = document.getElementById('mobile_e164');
            const formMobile = document.getElementById('register-form-mobile');

            // helper: iso2 -> emoji flag
            function iso2ToFlagEmoji(iso2) {
                if (!iso2 || iso2.length !== 2) return '';
                const A = 0x1F1E6; // Regional Indicator Symbol Letter A
                const codePoints = [...iso2.toUpperCase()].map(c => A + c.charCodeAt(0) - 65);
                return String.fromCodePoint(...codePoints);
            }

            // Ensure intl-tel-input lib loaded
            function whenItiReady(cb) {
                if (window.intlTelInput && window.intlTelInputGlobals) return cb();
                // retry few times if library not yet loaded
                let tries = 0;
                const id = setInterval(() => {
                    tries++;
                    if (window.intlTelInput && window.intlTelInputGlobals) {
                        clearInterval(id);
                        cb();
                    } else if (tries > 20) { // ~2s timeout
                        clearInterval(id);
                        cb(); // fallback anyway
                    }
                }, 100);
            }

            whenItiReady(function () {
                // init intl-tel-input if input tồn tại
                let iti = null;
                if (mobileInput && window.intlTelInput) {
                    iti = window.intlTelInput(mobileInput, {
                        initialCountry: "auto",
                        geoIpLookup: function(success, failure) {
                            // tiện ích: auto detect country, nếu mạng chặn thì trả 'vn'
                            fetch('https://ipapi.co/json/').then(r => r.json()).then(d => {
                                success(d.country_code ? d.country_code.toLowerCase() : 'vn');
                            }).catch(() => success('vn'));
                        },
                        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
                        separateDialCode: false,
                        preferredCountries: ["vn","us","gb","cn","jp"],
                        autoHideDialCode: false
                    });
                }

                // Populate select with country data
                function populateCountrySelect() {
                    if (!countrySelect) return;

                    let countries = [];
                    if (window.intlTelInputGlobals && window.intlTelInputGlobals.getCountryData) {
                        countries = window.intlTelInputGlobals.getCountryData().slice();
                    } else {
                        // fallback small list
                        countries = [
                            {name: 'Vietnam', iso2: 'vn', dialCode: '84'},
                            {name: 'United States', iso2: 'us', dialCode: '1'},
                            {name: 'United Kingdom', iso2: 'gb', dialCode: '44'},
                            {name: 'Japan', iso2: 'jp', dialCode: '81'}
                        ];
                    }

                    // sort by name for easier search
                    countries.sort((a,b) => a.name.localeCompare(b.name, undefined, {sensitivity:'base'}));

                    // Build options with emoji flag, dial code, name
                    const html = countries.map(c => {
                        const flag = iso2ToFlagEmoji(c.iso2 || '');
                        // show: "🇻🇳 +84 — Vietnam"
                        return `<option data-iso="${c.iso2}" value="${c.dialCode}">${flag} +${c.dialCode}</option>`;
                    }).join('');

                    countrySelect.innerHTML = html;

                    // If iti available, try set selected to current country
                    if (iti) {
                        const cur = iti.getSelectedCountryData();
                        if (cur && cur.iso2) {
                            const opt = countrySelect.querySelector(`option[data-iso="${cur.iso2}"]`);
                            if (opt) opt.selected = true;
                        }
                    }
                }

                populateCountrySelect();

                // When user changes select -> update iti country
                if (countrySelect) {
                    countrySelect.addEventListener('change', function () {
                        const iso = this.options[this.selectedIndex].getAttribute('data-iso');
                        if (iti && iso) iti.setCountry(iso);
                        mobileInput && mobileInput.focus();
                    });
                }

                // When user changes country with intl widget (click flag) -> update select
                if (mobileInput && iti) {
                    mobileInput.addEventListener('countrychange', function () {
                        try {
                            const data = iti.getSelectedCountryData();
                            if (data && data.iso2) {
                                const opt = countrySelect.querySelector(`option[data-iso="${data.iso2}"]`);
                                if (opt) opt.selected = true;
                            }
                        } catch (err) { /* ignore */ }
                    });
                }

                // Submit: validate and set mobile_e164
                if (formMobile) {
                    formMobile.addEventListener('submit', function (e) {
                        if (iti) {
                            if (!iti.isValidNumber()) {
                                e.preventDefault();
                                // bạn có thể thay alert bằng hiển thị lỗi inline
                                alert('Số điện thoại không hợp lệ. Vui lòng kiểm tra lại.');
                                mobileInput.focus();
                                return;
                            }
                            if (hiddenE164) hiddenE164.value = iti.getNumber(); // +8490...
                            // select name="mobile_country_code" sẽ gửi dialCode như +84? hiện đang gửi '84' (no +)
                        } else {
                            if (!mobileInput.value.trim()) {
                                e.preventDefault();
                                alert('Vui lòng nhập số điện thoại.');
                                mobileInput.focus();
                            }
                        }
                    });
                }

                // If user types full number including +country, sync the select on blur
                if (mobileInput && iti) {
                    mobileInput.addEventListener('blur', function () {
                        try {
                            const data = iti.getSelectedCountryData();
                            if (data && data.iso2) {
                                const opt = countrySelect.querySelector(`option[data-iso="${data.iso2}"]`);
                                if (opt) opt.selected = true;
                            }
                        } catch (e) { /* ignore */ }
                    });
                }

            }); // end whenItiReady

        }); // end DOMContentLoaded
    </script>
{{--    Cập nhật địa chỉ mặc định--}}
    <script>
        $(document).ready(function () {
            $(document).on('click', '.switch', function () {
                const parent = $(this).closest('.el-switch');
                const checkbox = parent.find('.default-address-switch');
                const addressId = checkbox.data('id');
                const userId = checkbox.data('user-id');

                $.ajax({
                    url: "{{ route('address.setDefault') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        address_id: addressId,
                        user_id: userId
                    },
                    success: function (res) {
                        if (res.status === 'success') {
                            // Reset tất cả checkbox về unchecked
                            $('.default-address-switch').prop('checked', false);

                            // Đánh dấu checkbox hiện tại là checked
                            checkbox.prop('checked', true);

                            alert(res.message);
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function (err) {
                        console.log(err);
                        alert('Có lỗi xảy ra khi cập nhật địa chỉ mặc định!');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $(document).on('click', '.toggle-icon', function () {
                const parent = $(this).closest('.el-switch');
                const checkbox = parent.find('.default-address-switch');
                const addressId = checkbox.data('id');
                const userId = checkbox.data('user-id');
                const toggleIcon = $(this);

                $.ajax({
                    url: "{{ route('address.setDefault') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        address_id: addressId,
                        user_id: userId
                    },
                    success: function (res) {
                        if (res.status === 'success') {
                            // Reset icon tất cả các switch khác về OFF
                            $('.toggle-icon').html(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="gray" class="bi bi-toggle-off" viewBox="0 0 16 16">
                          <path d="M11 4a4 4 0 0 1 0 8H8a5 5 0 0 0 2-4 5 5 0 0 0-2-4zm-6 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8M0 8a5 5 0 0 0 5 5h6a5 5 0 0 0 0-10H5a5 5 0 0 0-5 5"/>
                        </svg>
                    `);

                            // Icon hiện tại đổi thành ON
                            toggleIcon.html(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="black" class="bi bi-toggle-on" viewBox="0 0 16 16">
                          <path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8"/>
                        </svg>
                    `);

                            // Reset checkbox
                            $('.default-address-switch').prop('checked', false);
                            checkbox.prop('checked', true);
                        } else {
                            alert(res.message);
                        }
                    },
                    error: function (err) {
                        console.log(err);
                        alert('Có lỗi xảy ra khi cập nhật địa chỉ mặc định!');
                    }
                });
            });
        });
    </script>
{{--Chuyển trang ở setting--}}
    <script>
        const links = {
            'transaction-link': 'transaction-password',
            'address-link': 'address',
            'account-link': 'account-cancel' // thêm id mới cho account cancellation
        };

        const setupIndex = document.querySelector('.setup.setup-index');

        const forms = {
            'transaction-password': document.querySelector('.setup.transaction-password'),
            'address': document.querySelector('.setup.address'),
            'account-cancel': document.querySelector('.setup.account-cancel') // form account cancellation
        };

        // back buttons
        const backButtons = document.querySelectorAll('.page-title');

        function hideAllForms() {
            Object.values(forms).forEach(f => f.style.display = 'none');
        }

        // click vào link chuyển form
        Object.keys(links).forEach(linkId => {
            const formKey = links[linkId];
            const link = document.getElementById(linkId);
            if (!link) return;
            link.addEventListener('click', function(e) {
                e.preventDefault();
                setupIndex.style.display = 'none';
                hideAllForms();
                forms[formKey].style.display = 'block';
            });
        });

        // click back tất cả các form
        backButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                hideAllForms();
                setupIndex.style.display = 'block';
            });
        });
    </script>
{{--    Hiển thị password--}}
    <script>
        try {
            const suffixes = Array.from(document.querySelectorAll('.el-input__suffix'));
            suffixes.forEach(suffix => {
                // tìm phần tử input gần nhất trong cùng block .el-input hoặc thẳng parent
                let input = null;
                const block = suffix.closest('.el-input') || suffix.parentElement;
                if (block) {
                    input = block.querySelector('input[type="password"], input');
                }
                if (!input) return;

                // click toggle: đổi type và đổi src img nếu có
                suffix.style.cursor = 'pointer';
                suffix.addEventListener('click', function (ev) {
                    // ngăn click gây focus mất chỗ (nếu cần)
                    ev.preventDefault && ev.preventDefault();

                    const isPwd = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPwd ? 'text' : 'password');

                    const img = this.querySelector('img');
                    if (img) {
                        // nếu bạn có 2 icon: icons8-eye-50.png (open) và icons8-closed-eye-50.png (closed)
                        // đổi đường dẫn nếu cần (đây giả sử bạn đặt đúng asset path trong blade)
                        img.src = isPwd
                            ? '{{ asset("filemanager/userfiles/icons8-eye-50.png") }}'
                            : '{{ asset("filemanager/userfiles/icons8-closed-eye-50.png") }}';
                    }
                });
            });
        } catch (err) {
            // không cần block toàn bộ app nếu có lỗi nhỏ
            console.warn('Password toggle init error:', err);
        }
    </script>
{{--Mở menu ở địa chỉ--}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const references = document.querySelectorAll('.el-popover__reference');

            references.forEach(ref => {
                const menu = ref.nextElementSibling; // menu nằm ngay sau icon

                // Click vào icon
                ref.addEventListener('click', (e) => {
                    e.stopPropagation(); // tránh click ra ngoài đóng ngay
                    // Ẩn tất cả menu khác trước
                    document.querySelectorAll('.custom-options').forEach(m => {
                        if(m !== menu) m.style.display = 'none';
                    });
                    // Toggle menu hiện/ẩn
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';

                    // Căn menu theo icon
                    const rect = ref.getBoundingClientRect();
                    menu.style.position = 'absolute';
                    menu.style.top = rect.bottom + window.scrollY + 'px';
                    menu.style.left = rect.left + window.scrollX + 'px';
                });

                // Click vào option
                menu.querySelectorAll('.option-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const action = item.dataset.action;
                        console.log('Chọn action:', action);
                        menu.style.display = 'none'; // đóng menu
                        // Thực hiện hành động tương ứng ở đây
                    });
                });
            });

            // Click ra ngoài sẽ ẩn menu
            window.addEventListener('click', () => {
                document.querySelectorAll('.custom-options').forEach(m => m.style.display = 'none');
            });
        });

    </script>
{{--xóa tài khoản--}}
    <script>
        function deleteUser(id) {
            if (!confirm('Bạn có chắc muốn xóa tài khoản này?')) return;

            fetch(`/user/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === 'success') {
                        window.location.href = "{{ route('user.register') }}"; // chuyển hướng về trang đăng ký
                    }
                })
                .catch(err => console.error(err));
        }
    </script>

{{--xem chi tiết đơn hàng--}}
    <script>
        function showBillDetail(billId) {
            const modal = document.getElementById('billDetailModal');
            const content = document.getElementById('billDetailContent');

            document.getElementById('chatBillId').value = billId;
            loadChatMessages(billId);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            content.innerHTML = `<p class="text-gray-500">Đang tải dữ liệu...</p>`;

            fetch(`/bill/${billId}/detail`)
                .then(res => res.json())
                .then(data => {
                    // ====== Thông tin cơ bản ======
                    const tinhTrangThanhToan = data.tinh_trang_thanh_toan === 'da_thanh_toan'
                        ? 'Đã thanh toán' : 'Chưa thanh toán';

                    const trangThaiMuaHang = {
                        dat_don_hang_thanh_cong: 'Đặt đơn hàng thành công',
                        cho_xac_nhan: 'Chờ xác nhận',
                        thanh_toan_thanh_cong: 'Thanh toán thành công',
                        don_hang_dang_duoc_dong_goi: 'Đơn hàng đang được đóng gói',
                        don_hang_dang_roi_kho: 'Đơn hàng rời kho',
                        dang_van_chuyen: 'Đang vận chuyển',
                        da_nhan_hang_thanh_cong: 'Đã nhận hàng thành công',
                        huy_don_hang: 'Đã hủy đơn hàng',
                        hoan_tra: 'Hoàn trả'
                    }[data.trang_thai_mua_hang] ?? 'Không xác định';

                    let html = `
                    <div class="mb-6">
                        <p style="font-size: 18px"><strong>Mã đơn:</strong> ${data.ma_don_hang}</p>
                        <p><strong>Ngày mua:</strong> ${new Date(data.created_at).toLocaleString()}</p>
                        <p><strong>Trạng thái thanh toán:</strong> ${tinhTrangThanhToan}</p>
                        <p><strong>Trạng thái mua hàng:</strong> ${trangThaiMuaHang}</p>
                    </div>

                    <div class="mb-6">
                        <p><strong>Vận chuyển:</strong> ${parseInt(data.van_chuyen || 0).toLocaleString()} đ</p>
                        <p><strong>Giảm giá:</strong> -${parseInt(data.giam_gia || 0).toLocaleString()} đ</p>
                        <p class="font-semibold text-lg mt-2">
                            <strong>Tổng tiền:</strong> ${parseInt(data.tong_tien || 0).toLocaleString()} đ
                        </p>
                    </div>

                    <div class="mb-6">
                        <p><strong>Người đặt:</strong> ${data.user?.name ?? 'Không xác định'}</p>
                        <p><strong>Email:</strong> ${data.user?.email ?? '-'}</p>
                        <p><strong>Địa chỉ giao hàng:</strong> ${data.address?.full_address ?? 'Không có thông tin'}</p>
                    </div>
                `;

                    // ====== Bảng chi tiết sản phẩm ======
                    html += `
                    <table class="w-full border border-gray-200 mb-6">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-3 border">Sản phẩm</th>
                                <th class="py-2 px-3 border">Ảnh</th>
                                <th class="py-2 px-3 border">Màu sắc</th>
                                <th class="py-2 px-3 border">Kích cỡ</th>
                                <th class="py-2 px-3 border">Số lượng</th>
                                <th class="py-2 px-3 border">Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                    (data.bill_items || []).forEach(item => {
                        const product = item.product_detail?.product ?? { name: 'Không xác định' };

                        // ===== Xử lý ảnh sản phẩm =====
                        let image = 'no-image.png';
                        if (item.product_detail?.image_extral) {
                            try {
                                const parsedImages = JSON.parse(item.product_detail.image_extral);
                                image = parsedImages[0] || 'no-image.png';
                            } catch (e) {
                                console.error('Lỗi parse image_extral:', item.product_detail.image_extral, e);
                            }
                        }

                        // ===== Xử lý màu và size từ attribute =====
                        let color = '-';
                        let size = '-';

                        if (item.product_detail?.attribute) {
                            const attr = item.product_detail.attribute;
                            const parentName = attr.parent?.name?.toLowerCase() ?? '';

                            if (parentName.includes('màu')) {
                                color = attr.name;
                            } else if (parentName.includes('size') || parentName.includes('kích')) {
                                size = attr.name;
                            }
                        }

                        // ===== Hiển thị HTML =====
                        html += `
                            <tr>
                                <td class="border px-3 py-2">${product.name}</td>
                                <td class="border px-3 py-2">
                                    <img src="${'{{ asset('filemanager/userfiles') }}'}/${product.image}"
                                         alt="${product.name}"
                                         class="w-12 h-12 object-cover rounded-md border">
                                </td>
                                <td class="border px-3 py-2">${color}</td>
                                <td class="border px-3 py-2">${size}</td>
                                <td class="border px-3 py-2 text-center">${item.quantity}</td>
                                <td class="border px-3 py-2 text-right">${parseInt(item.price || 0).toLocaleString()} đ</td>
                            </tr>
                        `;
                    });


                    html += `</tbody></table>`;

                    content.innerHTML = html;
                })
                .catch(() => {
                    content.innerHTML = `<p class="text-red-500">Không thể tải dữ liệu chi tiết.</p>`;
                });
        }

        function closeBillDetail() {
            const modal = document.getElementById('billDetailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.tab-item');
            const container = document.getElementById('order-list-container');

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    // Xóa active cũ
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const status = this.getAttribute('data-status');

                    fetch(`/orders/filter?trang_thai_mua_hang=${status}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            container.innerHTML = data.html;
                        })
                        .catch(err => {
                            console.error('Error:', err);
                        });
                });
            });
        });
    </script>


@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Element UI Icons -->
<link rel="stylesheet" href="//unpkg.com/element-ui/lib/theme-chalk/index.css">

<style>
    .header-fiexd {
        padding-top: 22px;
        position: fixed;
        top: 0;
        z-index: 999;
        padding-bottom: 22px;
        left: 0;
        width: 100%;
        height: auto !important;
        transition: padding .5s linear 0s;
        background: #fff
    }

    .header-fiexd.active {
        padding: 0px !important;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .app-container {
        max-width: 1200px;
        margin: 0 auto
    }

    .flex-between {
        justify-content: space-between;
    }

    .el-button--primary {
        color: rgb(255, 255, 255);
        background-color: black;
        border-color: black;;
    }
    .with-flags option {
        font-family: inherit;
    }
    .custom-select { position: relative; width: 160px; cursor: pointer; }
    .custom-select .selected {
        display: flex; align-items: center; gap: 8px;
        padding: 6px 10px;
        /*border: 1px solid #ccc; */
        border-radius: 6px;
    }
    .custom-select img { width: 20px; height: 14px; }
    .custom-select .options {
        position: absolute; top: 100%; left: 0; right: 0;
        background: white; border: 1px solid #ccc; border-radius: 6px;
        max-height: 200px; overflow-y: auto; display: none;
    }
    .custom-select .options li {
        display: flex; align-items: center; gap: 8px; padding: 6px 10px;
    }
    .custom-select .options li:hover { background: #eee; }
    .custom-select.open .options { display: block; }
    .custom-select img {width: 18px;height: 18px;}
    .custom-select {
        width: 60px;
    }
    form {
         margin-bottom: 0px;
     }

    .custom-select {
        position: relative; /* để dropdown bám theo phần chọn */
        display: inline-block;
    }

    .custom-select .selected {
        display: flex;
        align-items: center;
        cursor: pointer;
        gap: 6px;
    }

    .custom-select .options {
        position: absolute;
        top: 120%;
        right: 0;      /* ✅ bám mép phải của .selected */
        left: auto;    /* ✅ không bám trái nữa */
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        list-style: none;
        margin: 0;
        padding: 0;
        min-width: 160px;
        display: none;
        z-index: 1000;

    }

    .custom-select .options li {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        cursor: pointer;
    }

    .custom-select .options li:hover {
        background: #f0f0f0;
    }

    .custom-select.open .options {
        display: block;
    }

</style>
{{-- 26/09/2025 - DiepTV add start --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/global.js') }}"></script>
{{-- 26/09/2025 - DiepTV add end --}}

<div data-v-6684c942="" class="header">
    <div class="header-fiexd">
        <div class="header-top flex-between app-container">
            <div class="header-logo flex-start">
                <a href="/">

                <img  src="{{ asset('filemanager/userfiles/admin/TikTok-Wholesalelogo.e950f9dd.svg') }}" alt="Ảnh" style="width: 200px;" >
                </a>
            </div>
            <div slot="reference" class="header-search">
                <i class="el-icon-search">

                </i>
                <div aria-haspopup="listbox" role="combobox" aria-owns="el-autocomplete-8754" class="el-autocomplete">
                    <div class="el-input">
                        <!---->
                        <input type="text" id="search-input" autocomplete="off" valuekey="value" popperclass="search-popper"
                               placeholder="{{ __('lang.Search_for_brands/products/suppliers')}}"
                               fetchsuggestions="function () { [native code] }"
                               triggeronfocus="true" debounce="300" placement="bottom-start"
                               popperappendtobody="true"
                               class="el-input__inner" role="textbox" aria-autocomplete="list" aria-controls="id"
                               aria-activedescendant="el-autocomplete-8754-item--1"/>
                        <!----><!----><!----><!---->
                    </div>

                    {{--popup của thanh tìm kiếm--}}
                    <div role="region" id="search-popup" class="el-autocomplete-suggestion el-popper search-popper"
                         style="display: none; transform-origin: center top; z-index: 2067; width: 380px; position: fixed; top: 80px; left: 498px;"
                         x-placement="bottom-start">
                        <div class="el-scrollbar">
                            <div class="el-autocomplete-suggestion__wrap el-scrollbar__wrap"
                                 style="margin-bottom: -6px; margin-right: -6px;">
                                <ul class="el-scrollbar__view el-autocomplete-suggestion__list" role="listbox"
                                    id="el-autocomplete-9796">
                                    <li id="el-autocomplete-9796-item-0" role="option" class="">
                                        <div class="search-content"><!---->
                                            <div title="Logitech G Extreme 3D Pro USB Joystick for Windows - Black/Silver" class="search-content-item"> Logitech G Extreme 3D Pro USB Joystick for Windows - Black/Silver </div>
                                            <div title="Logitech G Extreme 3D Pro USB Joystick for Windows - Black/Silver" class="search-content-item"> Logitech G Extreme 3D Pro USB Joystick for Windows - Black/Silver </div>

                                            <div class="search-content-history">
                                                <div class="search-content-history-header flex-between"><h1>{{__('lang.History_search')}}</h1>
                                                    <div><i class="el-icon-delete"></i> {{__('lang.Empty')}}</div>
                                                </div>
                                                <div class="search-content-history-list">
                                                    <div class="empty">{{__('lang.No_data_found')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="el-scrollbar__bar is-horizontal">
                                <div class="el-scrollbar__thumb" style="transform: translateX(0%);"></div>
                            </div>
                            <div class="el-scrollbar__bar is-vertical">
                                <div class="el-scrollbar__thumb" style="transform: translateY(0%);"></div>
                            </div>
                        </div>
                        <div x-arrow="" class="popper__arrow" style="left: 35px;"></div>
                    </div>
                    {{--End popup của thanh tìm kiếm--}}

                </div>
                {{--28/09/2025: Sang modified--}}
                <button type="button" id="search-button" class="el-button el-button--primary">
                    <!----><!----><span>{{__('lang.Search')}}</span>
                </button>
            </div>
            @php
                use App\CRMDV\Models\Admin;
                use Illuminate\Support\Facades\Auth;
                    $user = Admin::select('email')
                            ->where('id', Auth::id())
                            ->first();
            @endphp
            <div class="header-user">
                <div class="flex-start">

                    @if(Auth::guard('users')->check())
                        <i class="el-icon-user"></i>
                        <div class="no-login">
                        <span class="text user-name">
                            <a href="{{ route('profile') }}" style="color: black;text-decoration-line: none">
                                {{ Auth::guard('users')->user()->email ?? Auth::guard('users')->user()->tel }}
                            </a>
                        </span>
                            <span>{{ __('lang.or') }}</span>
                            <span class="text">
                                <a href="{{ route('user.logout') }}" style="color: black;text-decoration-line: none">
                                    {{ __('lang.Logout') }}
                                </a>
                            </span>
                        </div>
                    @else
                        <i class="el-icon-user"></i>
                        <div class="no-login">
                        <span class="text user-name">
                            <a href="{{ route('user.login') }}" style="color: black;text-decoration-line: none">
                                {{ __('lang.Login') }}
                            </a>
                        </span>
                            <span>{{ __('lang.or') }}</span>
                            <span class="text">
                                <a href="{{ route('user.register') }}" style="color: black;text-decoration-line: none">
                                    {{ __('lang.Register') }}
                                </a>
                            </span>
                        </div>
                    @endif


                    <div style="margin-right: 20px">
                        <i class="el-icon-headset openChatIcon" id="Open Chat"></i>
                    </div>
{{--                    <i class="el-icon-chat-line-round" style="font-size: 22px"></i>--}}
{{--                    <form action="{{ route('set-locale') }}" method="POST">--}}
{{--                        {{csrf_field()}}--}}
{{--                        <select name="locale" onchange="this.form.submit()" class="with-flags">--}}
{{--                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>--}}
{{--                                🇺🇸 {{ __('lang.en') }}--}}
{{--                            </option>--}}
{{--                            <option value="vi" {{ app()->getLocale() == 'vi' ? 'selected' : '' }}>--}}
{{--                                🇻🇳 {{ __('lang.vi') }}--}}
{{--                            </option>--}}
{{--                            <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>--}}
{{--                                🇫🇷 {{ __('lang.fr') }}--}}
{{--                            </option>--}}
{{--                            <option value="de" {{ app()->getLocale() == 'de' ? 'selected' : '' }}>--}}
{{--                                🇩🇪 {{ __('lang.de') }}--}}
{{--                            </option>--}}
{{--                            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>--}}
{{--                                🇪🇸 {{ __('lang.es') }}--}}
{{--                            </option>--}}
{{--                            <option value="pt" {{ app()->getLocale() == 'pt' ? 'selected' : '' }}>--}}
{{--                                🇵🇹 {{ __('lang.pt') }}--}}
{{--                            </option>--}}
{{--                            <option value="it" {{ app()->getLocale() == 'it' ? 'selected' : '' }}>--}}
{{--                                🇮🇹 {{ __('lang.it') }}--}}
{{--                            </option>--}}
{{--                            <option value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>--}}
{{--                                🇷🇺 {{ __('lang.ru') }}--}}
{{--                            </option>--}}
{{--                            <option value="ja" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>--}}
{{--                                🇯🇵 {{ __('lang.ja') }}--}}
{{--                            </option>--}}
{{--                            <option value="ko" {{ app()->getLocale() == 'ko' ? 'selected' : '' }}>--}}
{{--                                🇰🇷 {{ __('lang.ko') }}--}}
{{--                            </option>--}}
{{--                            <option value="zh" {{ app()->getLocale() == 'zh' ? 'selected' : '' }}>--}}
{{--                                🇨🇳 {{ __('lang.zh') }}--}}
{{--                            </option>--}}
{{--                            <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>--}}
{{--                                🇸🇦 {{ __('lang.ar') }}--}}
{{--                            </option>--}}
{{--                            <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>--}}
{{--                                🇮🇳 {{ __('lang.hi') }}--}}
{{--                            </option>--}}
{{--                        </select>--}}
{{--                    </form>--}}
                        <form action="{{ route('set-locale') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="custom-select" id="customLang">
                                <!-- phần hiển thị đang chọn -->
                                <div class="selected">
                                    <img src="{{ asset('filemanager/userfiles/admin/' . app()->getLocale() . '.png') }}" alt="flag">
                                    <i class="el-icon-caret-bottom"></i>
{{--                                    <span>--}}
{{--                @switch(app()->getLocale())--}}
{{--                                            @case('vi') Tiếng Việt @break--}}
{{--                                            @case('fr') Français @break--}}
{{--                                            @case('de') Deutsch @break--}}
{{--                                            @default English--}}
{{--                                        @endswitch--}}
{{--            </span>--}}
                                </div>

                                <!-- danh sách tùy chọn -->
                                <ul class="options" style="width: 160px">
                                    <li data-value="en"><img src="{{ asset('filemanager/userfiles/admin/en.png') }}" alt="Ảnh"  style="width: 18px"> English</li>
                                    <li data-value="vi"><img src="{{ asset('filemanager/userfiles/admin/vn.png') }}" alt=""> Tiếng Việt</li>
                                    <li data-value="fr"><img src="{{ asset('filemanager/userfiles/admin/fr.png') }}" alt=""> Français</li>
                                    <li data-value="de"><img src="{{ asset('filemanager/userfiles/admin/de.png') }}" alt=""> Deutsch</li>
                                    <li data-value="es"><img src="{{ asset('filemanager/userfiles/admin/es.png') }}" alt=""> Español</li>
                                    <li data-value="it"><img src="{{ asset('filemanager/userfiles/admin/it.png') }}" alt=""> Italiano</li>
                                    <li data-value="ru"><img src="{{ asset('filemanager/userfiles/admin/rs.png') }}" alt=""> Русский</li>
                                    <li data-value="ja"><img src="{{ asset('filemanager/userfiles/admin/kr.png') }}" alt=""> 日本語</li>
                                    <li data-value="ko"><img src="{{ asset('filemanager/userfiles/admin/kkr.png') }}" alt=""> 한국어</li>
                                    <li data-value="zh"><img src="{{ asset('filemanager/userfiles/admin/cn.png') }}" alt=""> 中文</li>
                                    <li data-value="ar"><img src="{{ asset('filemanager/userfiles/admin/colon.png') }}" alt=""> العربية</li>
                                    <li data-value="hi"><img src="{{ asset('filemanager/userfiles/admin/india.png') }}" alt=""> हिन्दी</li>
                                </ul>
                            </div>

                            <!-- select thật để submit -->
                            <select name="locale" id="localeSelect" style="display:none;">
                                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                <option value="vi" {{ app()->getLocale() == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                                <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="de" {{ app()->getLocale() == 'de' ? 'selected' : '' }}>Deutsch</option>
                                <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>Español</option>
                                <option value="pt" {{ app()->getLocale() == 'pt' ? 'selected' : '' }}>Português</option>
                                <option value="it" {{ app()->getLocale() == 'it' ? 'selected' : '' }}>Italiano</option>
                                <option value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>Русский</option>
                                <option value="ja" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>日本語</option>
                                <option value="ko" {{ app()->getLocale() == 'ko' ? 'selected' : '' }}>한국어</option>
                                <option value="zh" {{ app()->getLocale() == 'zh' ? 'selected' : '' }}>中文</option>
                                <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>العربية</option>
                                <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>हिन्दी</option>
                            </select>
                        </form>

                </div>
            </div>
        </div>
    </div>
    <div class="header-nav">
        <ul style="display: flex">
            <li class="active">
                <a href="/"
                   style="text-decoration-line: none;color: black">{{__('lang.Home')}}
                </a>
            </li>
            <li class=""><a href="{{route('classification')}}"
                            style="text-decoration-line: none;color: black">{{__('lang.Category')}}</a></li>
            <li class=""><a href="{{route('products')}}"
                            style="text-decoration-line: none;color: black">{{__('lang.Product')}}</a></li>
            <a href="{{route('discounts')}}"
               style="text-decoration-line: none;color: black">{{__('lang.Discounts')}}</a>
            </li>
            <li class="">
                <a href="{{route('partnership')}}"
                   style="text-decoration-line: none;color: black">{{__('lang.Partnership')}}</a></li>
            <li class=""><a href="{{url('/admin/login')}}"
                            style="text-decoration-line: none;color: black">{{__('lang.Seller_login')}}</a></li>
        </ul>
    </div>
    <!---->
</div>

<script>
    window.addEventListener("scroll", function () {
        const header = document.querySelector(".header-fiexd");
        if (window.scrollY > 50) {
            // khi lăn xuống quá 50px thì add class
            header.classList.add("active");
        } else {
            // khi về đầu trang thì bỏ class
            header.classList.remove("active");
        }
    });


    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById("search-input");
        const popup = document.getElementById("search-popup");

        // Khi focus thì hiện popup
        input.addEventListener("focus", function () {
            popup.style.display = "block";
        });

        // Khi click ra ngoài thì ẩn popup
        document.addEventListener("click", function (e) {
            if (!input.contains(e.target) && !popup.contains(e.target)) {
                popup.style.display = "none";
            }
        });
    });
</script>
<script>
    (function(){
        const customSelect = document.getElementById('customLang');
        const selected = customSelect.querySelector('.selected');
        const optionsEl = customSelect.querySelector('.options');
        const options = optionsEl.querySelectorAll('li');
        const realSelect = document.getElementById('localeSelect');

        // mở/đóng khi click vào vùng selected
        selected.addEventListener('click', function(e){
            e.stopPropagation();                 // quan trọng: ngăn click bùng ra document
            customSelect.classList.toggle('open');
            // cập nhật aria
            optionsEl.setAttribute('aria-hidden', customSelect.classList.contains('open') ? 'false' : 'true');
        });

        // chọn option
        options.forEach(function(opt){
            opt.addEventListener('click', function(e){
                e.stopPropagation();               // ngăn event bubble ra document
                const value = opt.dataset.value;
                const flag = opt.dataset.flag || (opt.querySelector('img') ? opt.querySelector('img').src : '');
                const text = opt.textContent.trim();

                // đổi ảnh + text hiển thị
                const imgNode = selected.querySelector('img');
                const spanNode = selected.querySelector('span');
                if(imgNode && flag) imgNode.src = flag;
                if(spanNode) spanNode.textContent = text;

                // set giá trị select ẩn và mark selected option
                realSelect.value = value;
                Array.from(realSelect.options).forEach(o => o.selected = (o.value === value));

                // đóng dropdown
                customSelect.classList.remove('open');
                optionsEl.setAttribute('aria-hidden', 'true');

                // submit form
                // -- nếu muốn debug giao diện trước, comment dòng bên dưới và thử chọn để xem ảnh có đổi không
                realSelect.form.submit();
            });
        });

        // click ra ngoài -> đóng dropdown
        document.addEventListener('click', function(e){
            if(!customSelect.contains(e.target)){
                customSelect.classList.remove('open');
                optionsEl.setAttribute('aria-hidden', 'true');
            }
        });

        // optional: đóng bằng ESC
        document.addEventListener('keydown', function(e){
            if(e.key === 'Escape') {
                customSelect.classList.remove('open');
                optionsEl.setAttribute('aria-hidden', 'true');
            }
        });

        // 28/09/2025: SangPT modified: search shop
        $(document).ready(function() {
            $('#search-button').on('click', function() {
                const keyword = $('#search-input').val().trim();

                if (keyword.length === 0) {
                    alert('Vui lòng nhập từ khóa tìm kiếm.');
                    return;
                }

                const searchUrl = '{{ route('store_search') }}' + '?keyword=' + encodeURIComponent(keyword);

                $.ajax({
                    url: searchUrl,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // if (response.status === 'success' && response.shops.length === 1) {
                        // Kiểm tra nếu có bất kỳ shop nào được tìm thấy (từ 1 trở lên)
                        if (response.status === 'success' && response.shops.length > 0) {
                            // chi co 1 ten shop trung
                            const storeId = response.shops[0].id;

                            // {{ route('view_store', ['storeId' => 'TEMP_ID']) }}
                            const redirectUrl = '{{ route('view_store', ['storeId' => 'TEMP_ID']) }}'.replace('TEMP_ID', storeId);

                            window.location.href = redirectUrl;
                        // } else if (response.status === 'success' && response.shops.length > 1) {
                            // nhieu ten shop trung
                            // alert('Tìm thấy nhiều cửa hàng, vui lòng tìm kiếm đúng tên cửa hàng');

                        } else {
                            // khong co shop nao
                            // alert('Không tìm thấy cửa hàng nào khớp với từ khóa.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Đã xảy ra lỗi trong quá trình tìm kiếm.');
                        console.error('Search error:', error);
                    }
                });
            });
        });
        // 28/09/2025: SangPT end modified
    })();
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatButton = document.getElementById('openChatButton');
        const chatWidget = document.getElementById('chatWidget');
        const chatIcons = document.querySelectorAll('.openChatIcon');

        function openChatFromIcon() {
            if(chatWidget) chatWidget.style.display = "flex"; // hiển thị form
            if(chatButton) chatButton.style.display = "none"; // ẩn button chat gốc
        }

        chatIcons.forEach(icon => {
            icon.addEventListener('click', openChatFromIcon);
        });
    });

</script>
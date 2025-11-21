@extends('frontend.layout.layout')
<style>
    .header {
        visibility: hidden;
    }
    .mer-chant-header[data-v-1ab1c4d0] {
        font-size: 14Px;
        background: transparent;
        position: relative;
        max-height: 440Px;
    }
    .mer-chant-header .down-header .left[data-v-1ab1c4d0] {
        flex: 1;
        cursor: pointer;
    }
    .mer-chant-header .down-header[data-v-1ab1c4d0] {
        height: 70Px;
        width: 1200Px;
        margin: 0 auto;
        display: flex
    ;
        align-items: center;
        position: absolute;
        top: 0;
        left: 50% !important;
        transform: translateX(-50%);
        z-index: 10;
    }
    .mer-chant-header .content[data-v-1ab1c4d0] {
        width: 1200Px;
        height: 520Px;
        margin: 0 auto;
        position: absolute;
        padding-bottom: 100Px;
        left: 50% !important;
        transform: translateX(-50%);
    }
    .mer-chant-header .content .content-text[data-v-1ab1c4d0] {
        width: 58%;
        position: absolute;
        top: 14%;
        display: flex
    ;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }
    .mer-chant-header .content .content-text .title[data-v-1ab1c4d0] {
         color: #EB174F;
         font-size: 48Px;
         margin-bottom: 4px;
         margin-top: 1.875rem;
     }
    .mer-chant-header .content .content-text p[data-v-1ab1c4d0] {
        width: auto;
        font-size: 20Px;
        color: #a6a6a6;
        margin: 0;
        padding: 0;
    }
    .merchantSettled .merchantSettled-wap[data-v-633ac152] {
        background: #fff;
        display: flex
    ;
        justify-content: center;
        align-items: center;
        width: 75rem;
        margin: 0 auto;
        z-index: 99;
        margin-top: 35px;
    }

    .merchantSettled .merchantSettled-wap .step-div[data-v-633ac152] {
        width: 100%;
        background: #fff;
        border-radius: .25rem;
        margin-top: -2.875rem;
    }
    .merchantSettled .merchantSettled-wap .step-div .content[data-v-633ac152] {
        padding: 1.25rem 1.875rem;
        border: 1px solid #eee;
    }
    .merchantSettled .merchantSettled-wap .step-div .content .title[data-v-633ac152] {
        font-size: 1.5rem;
        color: #333;
        font-weight: 700;
    }
    .merchantSettled .merchantSettled-wap .step-div .content .info[data-v-633ac152] {
        margin-top: -.3125rem;
        margin-bottom: 1.25rem;
        position: relative;
        z-index: 99;
    }
    .merchantSettled .merchantSettled-wap .step-div .content p[data-v-633ac152] {
        font-family: PingFang HK;
        font-style: normal;
        font-size: .875rem;
        color: #333;
        padding: .375rem 0;
    }
    .bg-image {
        position: relative;
        width: 100%;
        height: 470px;
        z-index: -1;
    }
    .merchantSettled .merchantSettled-wap .step-div .content[data-v-633ac152] {
        padding: 1.25rem 1.875rem;
        border: 1px solid #eee;
    }
    .merchantSettled .merchantSettled-wap .step-div .content p[data-v-633ac152] {
        font-family: PingFang HK;
        font-style: normal;
        font-size: .875rem;
        color: #333;
        padding: .375rem 0;
    }
    .merchantSettled .merchantSettled-wap .step-div .content .title[data-v-633ac152] {
        font-size: 1.5rem;
        color: #333;
        font-weight: 700;
        margin: 0;
    }
    .merchantSettled .merchantSettled-wap .step-div .content .info span[data-v-633ac152] {
        color: #eb174f;
        cursor: pointer;
    }
    .el-form-item {
        margin-bottom: 22px;
    }
    .van-uploader {
        position: relative;
        display: inline-block;
    }.van-uploader__upload {
         position: relative;
         display: -webkit-box;
         display: -webkit-flex;
         display: flex
     ;
         -webkit-box-orient: vertical;
         -webkit-box-direction: normal;
         -webkit-flex-direction: column;
         flex-direction: column;
         -webkit-box-align: center;
         -webkit-align-items: center;
         align-items: center;
         -webkit-box-pack: center;
         -webkit-justify-content: center;
         justify-content: center;
         box-sizing: border-box;
         width: 80px;
         height: 80px;
         margin: 0 8px 8px 0;
         background-color: #f7f8fa;
     }
    .van-uploader__input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        cursor: pointer;
        opacity: 0;
    }
    .el-form-item.is-error .el-input__inner, .el-form-item.is-error .el-input__inner:focus, .el-form-item.is-error .el-textarea__inner, .el-form-item.is-error .el-textarea__inner:focus {
        border-color: #f56c6c;
    }
    [data-v-633ac152] .el-input__inner {
        height: 3.125rem !important;
        width: 20rem;
        font-size: .875rem;
        border: 1px solid #eee;
    }
    .demo-ruleForm .uploder-center[data-v-633ac152] {
        display: flex
    ;
    }
    .demo-ruleForm .uploder-center .uploder-two-wrap .tips[data-v-633ac152] {
        color: #333;
        font-size: .75rem;
        text-align: center;
        line-height: 1;
    }
    .demo-ruleForm .uploder-center .uploder-two-wrap[data-v-633ac152] {
        margin-right: 1.875rem;
    }
    .demo-ruleForm .correct-warp img[data-v-633ac152] {
        width: 6.25rem;
        margin-right: 1.25rem;
    }
    .demo-ruleForm .tab-wrap[data-v-633ac152] {
        display: flex
    ;
        margin-top: 1.25rem;
        margin-bottom: 1.25rem;
    }
    .demo-ruleForm .tab-wrap .active[data-v-633ac152] {
        color: #fff !important;
        background:  #eb174f !important;
    }

    .demo-ruleForm .tab-wrap .tab-item[data-v-633ac152] {
        width: 9.25rem;
        height: 2.125rem;
        background: #eee;
        border-radius: .25rem;
        text-align: center;
        cursor: pointer;
        line-height: 2.125rem;
        margin-right: 1.25rem;
        color: #333;
        font-size: .875rem;
    }
    .password-icon[data-v-633ac152] {
        width: 1rem;
        cursor: pointer;
    }
    .el-input__suffix {
        position: absolute;
        height: 100%;
        right: .3125rem;
        top: 0;
        text-align: center;
        color: #c0c4cc;
        transition: all .3s;
        pointer-events: none;
    }
    [data-v-633ac152] .password .el-input, [data-v-633ac152] .password .el-input__inner {
        width: 20rem;
    }
    .merchantSettled .merchantSettled-wap .step-div .bottom[data-v-633ac152] {
        height: 8.75rem;
        font-size: .875rem;
        display: flex
    ;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
        margin: 1.25rem 0;
    }
    .van-button--normal {
        padding: 0 15px;
        font-size: 14px;
    }
    .van-button__content {
        display: -webkit-box;
        display: -webkit-flex;
        display: flex
    ;
        -webkit-box-align: center;
        -webkit-align-items: center;
        align-items: center;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        height: 100%;
    }
    .van-button {
        position: relative;
        display: inline-block;
        box-sizing: border-box;
        height: 44px;
        margin: 0;
        padding: 0;
        font-size: 16px;
        line-height: 1.2;
        text-align: center;
        border-radius: 2px;
        cursor: pointer;
        -webkit-transition: opacity .2s;
        transition: opacity .2s;
        -webkit-appearance: none;
        width: 146px;
        border: none;
        display: flex;
        flex-direction: column;
    }
    .van-checkbox  {
        display: flex;
        align-items: center;
    }
    .color-yellow[data-v-633ac152] {
        color: #eb174f !important;
        cursor: pointer;

        align-items: center;
    }

    input[type="checkbox"] {

        width: 20px;
        height: 20px;
        border: 2px solid #999;
        border-radius: 50%;
        outline: none;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }

    /* Khi được check */
    input[type="checkbox"].round-check:checked {
        background-color: #4caf50;
        border-color: #4caf50;
    }

    /* Dấu check (làm pseudo-element) */
    input[type="checkbox"].round-check:checked::after {
        content: "✓";
        color: white;
        font-size: 14px;
        position: absolute;
        top: 1px;
        left: 4px;
    }
    .van-checkbox__label{
        /*display: flex;*/
        align-items: center;
    }
    .van-button__text{font-size: 14px}


    .circle-checkbox {
        width: 22px;
        height: 22px;
        border: 2px solid #EB174F;
        border-radius: 50%;   /* Bo tròn */
        appearance: none;     /* Xóa style mặc định */
        -webkit-appearance: none;
        -moz-appearance: none;
        outline: none;
        cursor: pointer;
        position: relative;
        vertical-align: middle;
        margin-right: 6px;
    }

    /* Khi được chọn */
    .circle-checkbox:checked {
        background-color: #EB174F;
        border-color: #EB174F;
    }

    /* Dấu tích */
    .circle-checkbox:checked::after {
        content: "✔";
        color: #fff;
        font-size: 16px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -55%);
    }
    /* Dropdown ẩn mặc định */
    #country_dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%; /* nhỏ hơn input */
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        opacity: 0;
        transform: scaleY(0.9);
        transform-origin: top;
        transition: all 0.2s ease;
    }

    #country_dropdown.show {
        display: block;
        opacity: 1;
        transform: scaleY(1);
    }

    /* Item style */
    .el-select-dropdown__item {
        padding: 8px 12px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .el-select-dropdown__item:hover {
        background: #f5f5f5;
    }

    /* Input text khi đã chọn */
    .el-input__inner.selected {

        font-weight: 500;
    }
    .el-select-dropdown {
        overflow: hidden !important;
    }
    .el-select-dropdown__item.selected {
        color: red;
        font-weight: bold;
    }
    .uploder-center {
        display: flex;
    }
    .uploder-two-wrap {
        margin-right: 15px;
    }
    .checkbox-error {
        color: red;
        font-size: 12px;
        display: none;
        margin-top: 4px;
    }

    /* Khi checkbox chưa check mà submit */
    input[type="checkbox"]:invalid + label + .checkbox-error {
        display: block;
    }
    .correct-warp {
        display: flex
    ;
    }
    .popup-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .popup-box {
        background: #333;
        color: #fff;
        padding: 20px;
        border-radius: 8px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    }
    .popup-box h4 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 18px;
    }
    .popup-box ul {
        margin: 0;
        padding-left: 20px;
    }
    .popup-box button {
        margin-top: 15px;
        background: #ff3366;
        color: #fff;
        border: none;
        padding: 8px 14px;
        border-radius: 5px;
        cursor: pointer;
    }
    a.btn-submit,
    a[href="/register-form"] {
        display: inline-block;
        padding: 10px 20px;
        margin: 5px;
        text-decoration: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* Nút Đăng nhập */
    a.btn-submit {
        background-color: #007bff;   /* xanh dương */
        color: #fff;
        border: 2px solid #007bff;
    }

    a.btn-submit:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    /* Nút Đăng ký */
    a[href="/register-form"] {
        background-color: #28a745;   /* xanh lá */
        color: #fff;
        border: 2px solid #28a745;
    }

    a[href="/register-form"]:hover {
        background-color: #1e7e34;
        border-color: #1e7e34;
    }

</style>
<script>
    function closePopup() {
        document.getElementById('errorPopup').style.display = 'none';
    }
</script>
@if ($errors->any())
    <div id="errorPopup" class="popup-overlay">
        <div class="popup-box">
            <h4>Validation Errors</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>
@endif
{{--@include('chat.Frontend.ChatMessage')--}}
<div data-v-633ac152="" data-v-874e272e="" id="merchantSettled" class="merchantSettled">
    <div data-v-1ab1c4d0="" data-v-633ac152="" class="mer-chant-header" id="product">
        <div data-v-1ab1c4d0="" class="down-header"><a data-v-1ab1c4d0="" href="/" aria-current="page"
                                                       class="left router-link-exact-active router-link-active">

                <img  src="{{ asset('filemanager/userfiles/admin/shoplogo.247e230e.svg') }}" alt="Ảnh" style="height: 40px;" >
            </a>
            <div data-v-1ab1c4d0="" class="right">
                <div data-v-1ab1c4d0="" class="el-dropdown">
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
                            </div>

                            <!-- danh sách tùy chọn -->
                            <ul class="options" style="width: 160px">
                                <li data-value="en"><img src="{{ asset('filemanager/userfiles/admin/en.png') }}" alt="Ảnh"  style="width: 18px"> English</li>
                                <li data-value="vn"><img src="{{ asset('filemanager/userfiles/admin/vn.png') }}" alt=""> Tiếng Việt</li>
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
                            <option value="vn" {{ app()->getLocale() == 'vn' ? 'selected' : '' }}>Tiếng Việt</option>
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
                    <ul data-v-1ab1c4d0="" class="el-dropdown-menu el-popper" id="dropdown-menu-7119"
                        style="display: none;">
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,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">
                                English </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAHISURBVHgBzZgxTsMwFIZ/R0ydusGYngCCxEwqsQMnaNmROALsDIWRDY7QEzRcgPYGCRtjYYaa91ynTUPaxjRO/Et/5Spt/Ml+z8+2gLl88jn5iByS29qsKTnRjshD3baiPnlEloYek3uoUCE5/gdI3vGuYDwNgwpA8h5gOcWl5WM+1NKSY91HaZjYIowRVLsmmGzAb5w+GzFTJqYK1W8AJnVYBBQ3CBS7NDqpewwi+OOJgutzXgoaU4tKzTXQFTRW/qxgyJqQB3Q8grmAI/omFoLCKRwRwRx60mAJr0GhJ9wCavOUGVdei1JATomBpnBHU6eAKMESzrJXuKN3zrIJHBENTuRc6VDF9eMMI69VvCepS7MvTA4iBHv8Zf8eLzReIZqUwAMCvf1gyTezk0DFSsQxOtxYLow/uEJTErhLmwsgcUJncYlH1C3qUwQUMinHyrMx1TWpzu917R4TIggIaLE4r9Qy9UDgEhZvLHIw3SzMHyANpX4Iu1ATDZPkHxRWew0VWIkpfucaGNX31v+P6YgkcYvdl4SEM1klzwZtBcIq2A3MAz6iXp6zmVQJUAbMB1/pzWhlF6rNzl7pcWJEmBft4bqpWadfg4Ncda0pohEAAAAASUVORK5CYII=">
                                Deutsch </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAJhSURBVHgBzZhPTttAFMa/GYFQd47UTXfDogW6aThAVXMA2iQXIFG3lZobJF11VQlOAJW6Ji4cIO4J8I7QDe6+UqxWKk2DZvomtkUIDrYBx/OTRjOK3+LLvD8z8xhyMhwOBef8DS2rSimbZisamoCGrwdjzJVSfq1UKj5ywDJbNo6ab18+2fnUemojB3++9Lzf7z7uruH75yz2PNWidmyjcXxOpvs/f41t5ET9HVUV2MEAa+dneLaTZr8090utZ4Evd2jVxsMgImHVC4w+bMIPkoz4HDECbLn/gGKmaT/Cyskp1gUyCdJi+FKfoquK4hAMqp8kis+IsSZiwASKh0TJ3gmENV/QJGYWIiaCVcl9HSQKorRGMTGTRptcZ+OGILAOSoLiaT9eh4Imu8MEykPENSraIf4eJUM1qqlnPklzFJriWbFPIQSnzKrBEBhWauQy9grGoF6QICVgDMzWQS1gCHQXsrQgC4agIkFGoQUFMARyWaCD2hhB5DKfQ6lvMAQJ/OBg3IMhkLtcDjl2YAh0njkcTt2nlYvy8TYw8MO0ZzLTm6lI6E60q+dQ0OHrA9owH+Xhxw/Jq8Io0UJJ0O504/WVIGfbpRKwhwVDqb43/cy+fnSoyy4F+CLLgD/CqDv9w3VBTj2AGtcXFE8+pfnW7JP65uGqy4C83CpWlPK0GJ3ms1+ST/tQ1GYRMaVj5gL/EsVo5nc/tPv0w7Fx5IVvNiZwP7SLWs8xcG8zSr8P6Rp1uL1K/60FqXIHPH9ccSmtm+s4W91IEaPJ3kGLiFt6UTtPRGO6pRfodh7N3l1aev8BOcTLcgjW58oAAAAASUVORK5CYII=">
                                Français </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAABeSURBVHgB7dUxEYBAEMDAO+Z1YAAF6MQDQ4sCVGAAE5TwCj7tFVkF6ZJfF4VMUYxBxCBiEDGIGETaeT1RSca6O9cRg4hBxCBiEDGItO09opK8l9m5jhhEDCIGEYPID7PpDMU7R76gAAAAAElFTkSuQmCC">
                                Русский </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,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">
                                Español </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,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">
                                Português </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAIWSURBVHgBzZhPTttAFMa/N0bZsPERZlmhSiULpOxqpAp11/YETU9AOEHSE6RU3RdO0HaHurG7y85ZVFV3mBt4hwDZj3mTBJJgRCz8Z37SFzuO5fzseTO2h1ASZtZm8c5k1yQw8ecRUpNknsjkFxElqAMj0jcJuTyxyUdURfyyF5gDnvPzOce3gyfFth4V0YGP7cuhOdAA1aCR4wRfD3aRXX/GUZQW7aSKNsYvepq3L0MGqpJZZgCvE2P8VmMTISvjcYhZ0daFhpeHRVIrQtJMcxmN+tFQ+Q+MA/9RIakZNCMzg0wreJ0hioTinb1+TTXzFAOM3wRYF2JSQ7SFp74vVq2QXB002VQP0Ysxygox0SHaJuO+LJR0c1NddXbxzSAKZBhQucfv4QrGRYHxGu7wShHZxwlH4ECKWsMdfCNEPtzBV3AMI8Qp3CGVJnNHiJEo5PwH7nChWNEUrkAUKZXRT7iCcVHd/5MEs3eodmFMcXSW2G5PTKdony/yYYW6/yYnUuFoC/nvw9+nd0JCpugT2mO0WLkT2vs7iZhwjKZhPl5cnRUh+2XramT2aG4YkKbKb0bLm1aEutNpSpn60Eg9WRm1v/5K/eDmKsMA5bRfq5R0cStzlqz/VHi3t1Kdq24tNSU1k18XymxEvNPrVzYds/RCWMGJ2QmrmMsTcokJK0JJ+H5KL8Ds8VeyPKUniUykt5ae0rsFOeO1PbENpB0AAAAASUVORK5CYII=">
                                Italiano </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,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">
                                Melayu </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAUvSURBVHgBvZhtTBRHGMef2d077o4Dlgrh8NSuCgpSCqS2xZjYw2pjjVWvtEKrrfCpbWIrTZrU+kWaNKl+aNQPmsaXKk3axJgU8Q3axHI2TbSJhYNoy1tgG1EB4W4phwd3uzOdXTgqdwecsvD/NM+87Pz2P8/MzR6CJ5Rt0xlB4YzbEIZ8liiOf40W3s/E8QwhYJFHJMwgkR8dFn0Gi4uXB2rEuo/EJ3k+irWjbfP3ZV5j/G4AxjHKGGIawxIMCUG/2yQHj/TU7aqKZcyMQNYt5x0WHDjTZ0oSYBaKV0bFF3pbK3+7ub/qqYC8FRX8c72OA31+qAgyHOgla9B/JGXI96XoKpeitbPRKr2NjQLasLHWlp68vfpPD8WOeWVnVIA1FCYSf2l2yss13d3XpBmBDn9eKJhznq9Psa3IWrU4HjLTLPDrHS8EFAJ6adBg4T1mfrs9vahGEq9IUwJVHnbwjxKZGw0jTcKC+HRIS1gCKtSzC0xw8Va/rk7RNOAxa3R8EQiec0ktI1GBVu9Y9vVwIreJ0N3ROXAbUq2LIMW6EObKqVHWYPsjNdckt577OQLom32FZQP2uIOhWMYB+KvnJsy1UwhIoSWj+Hqg7byoxkyoIecVy4HwzgqW4dLtk9DSe0uLi9ekwukPssAax4BeUneweqyEYs0h+XdHWcZitkwttw9NPgvmw6lhzsTblm0Wfe0/NWlA5c70M8kpRltm4liHjqHJA+Yjp4KI5ZXWc1WsACB8d+3hwbWrk8GeFgcZ41Dz7RQHWEgVNlUhHpgKCfBhwW6CyycLICcjXutw9R5A3f3IgWaDFd7M2wNZaau1uKFzCNp7/KCH7PLgp2gRYqu7ibJdrVhIHao+ngcv5SaCuhC1FKr2fuTvC0sTsTjvY8hduBb0FOnpO4usCDX6CMkPVQqLzHD5RH5MThVlvg1J5gWgmx70iijJbPYO+v384/WqUxeoUy+OOxWC0u/0iS7DiCIhjmWJrCgRjapTV07mw6rlY05doVC/UCj9zulIMZgAQzCO2tjvCUDjnf/3v90ytzCa6ASM2WiMuAIYOAQnvsqGnVttWtzkBfixC+ZchgCWuAQCkg9gIocsZgaOV2bDO1vGYJopzOmOyMHqYZlnXweJpmdAN/l8IqfIweu0KGiEmjOrYOcb0ztD6IV+/coSeDWzlLqs30Liu3f/4WgKuWl5t+rMMepMCGY6Z9avLKUwJRoM9g4C8T0CPaQMj7gQnV4Y4lDXt4dyYFeYM34lHEZ1ZseEM8GGZvC+vx/wvYcwa9EzhRhHl2pHy9Vj+fWvb0xxqOVmmuKn2iP7hzsjt3WBZ+uHgHsk0EMIYfdK0l6gXWw25CZonyaqMz90RoMJ5UzJhDMe5x7dYMYmQUc0sFDcXOvoOuXhBBIBM7fOjM8iZkHbUrU0cfWr6oZyRiZhMPPgDBXHKpWh8sSd+sYlUVyzVUjGHFM4BhNypnTCGe9bewE/8IC+wkdXkI5DoWjS5XjdRW9lnF9xz5czNJFFPwQrH6+bBOS84JasQ8T52pJicZIz735Gt3Y/6CmavCImwaICEKWw+kh5q6sFWL6iXu4fELzv7aMwA6ArDCJuTALObBDF8Lao3zPJTqcISqBgoOSTo/rCaJvmqDEpUBQNRoOd6RFt7PIyrLD0mw0JMAtRV+gSMeXZ0OKath/EqL8hs4wBtJe+Y37sw1RHkIsDcjYD2vT5wyoSTBAQGLbRN3bQFRfopAIhiB+bnkgMQhJtcwFm3AqM1ky1NFPpP6E1bSOQW9oQAAAAAElFTkSuQmCC">
                                Afrikaans </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAR5SURBVHgBzVhLaBtHGP7+2ZFi2XVRbBz6ILA5hEJbGuXYQ4kS6KmFuKXQQmMiX0Lpxe6hl15iQ+5RS68ltkObhBbcB07oSUpJKaUGCdJzpDYHExzXSpMokVY7k39WDz+yK60iK86Hhh3t/Dvzzf+af5fQJeIfLdoiKo5D6wQISWjEze3GcInvFbVGkYiySqifSnPvFdEFKKzgsS+upHI3Kyf5gaT/RBpMkn8KrWnJ/Nd5rVS6dOHDeYRAR0I8YVJr59zUNzn7299W2sp+dep1vP3GS75jsagsDg9EZlhzbYlJBBMxZjjNnWmtO/HWvDPC2NAg9j0fCxKyuc1pY2pglomV/ISE382ZC3/YSqsMd6cRCqEtj8acOSZmIwyheGrRXvprPVODSjQXIzJiinvKu25v5r72+q7RbJhmc8v4kdpiMmOm2UvXMxY5tlC8gAXPMaE03nxlDNKKeLrw04chdOPWXSxkbyAk7NU7DxdZAUc5Elvmo22EzqJlJrMTFqBw5tDaxUT6T1zJrXYWJu8BWGQulL59fvyz5pDYRCbVJGPUWq063KpQRlM7Bm2Sg0dmQCjE95joo+n4icXkY4RgIorhum6LiBDCI7eTEGzaoaiAFOybgn2C8xYJOrcxzqjWdIr1YDtMxqmxOxszkYKUEpZleTtSGgEuXW+64dpQtcAmebWYVBiMEJgP/n/g4H7F5aVce/TjH04aLp6DvDj5S27fkE6YRSs1hf0jA7h85hiiIuLtwGjp8/llfP/7rcCdmz18/UkCR1574fExQ5onr/Fmhaj7pJSWt4kmpCWyg9HIUWnCvFJzEyv3gGFW5d0qYfCByzuglhI1sRldwr1qsPmMdIS1+dxAxF+AH61WFWtces0nVJMmDUg4GDeGq7nAnbLD5pJ11beeqJ9R7VGXJy28jB3EOBod6BS145Id6kjzn0tWdzl302qGkqZGFAWKUdtxXvuQ5Dns1uGMXqCxcLWAa3+v4cmg2VgqKfnctHsj0gTh1+XbfA2RGAOgSMQlbRRXPaNeFPSyPR2X2GZVY2XBzqm32NFqegn6CWL/kftHh0qcleMbhICRYZMXOewsUb/D59TI8B68vDeGfsISqkScCAvs/PbmARPlEbFxsJrE6CJE9PcIzsF5GbXoKrCV0HYYYl6dsjPeHwyBf4xN8nh2kKV61aYLeCZABzwjzF68nilXaknsIiKWyJ85ceiw5xpnlwrzHNZJ7BI40XD1qNKm33LTvRM/FqiDc/cLXEkV186/f8D0N5ewk9gl8KE+0+xvCeRXP72c5tJyCk8RK//d/3L1uw+mfQmZ1yBOfhnOfwk8HRS5gDwc9BbbJGVe4gq6/yjogLdXX1Jc/xYcx9F9Qi40mSbW19fj5XI5zcT0DiOt6x8yfNHxdFpa/jd16drN0+WKa+NJwWVFLBopnnrn4ORbB8eybUUREqMTP6cU6SnOYl06PGUFibm1hXfnQ0mjS3if9KQ4zgV9kowfENmm0mtMZz7plUi7WU0yr6r8Se9id5/0HgEMHRhCpxlKmAAAAABJRU5ErkJggg==">
                                Ελληνικά </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAASESURBVHgBzVhbTFxFGP7m7IWlBXqAAku34MEm2IQgxMYaG5WF1ks00ibGhBcDrTXWB9O++Nz6YEwTk2I0MfEFiSYafMBKLWpTt40EiGjZ2sakCtnFxTZQWnbZpbiXc8Z/9sJl3SvL7dv8e+bMzDnzzX+bmcOQJUzmXsWozz8MxhuhcisYZKqWHwlNYnD6hFvHNScDnFR3heR8tJwxWKYdCy3fdVD3dnrAmqi9lggNTb8JHefxTXZ6ppOkGxlAStehwNxnLbJcdDDGupKRSYNGoviZBjjo2p6uc1JCstwrF1VdPMd0Ohu9SNmWrwf//+yzgSKIkZwjkZENIZn8hBcYbdBCp4RNhTxzYAeUamO4XdwXFGFVIDKnSEbFJJEJIdncr2i6PNIKa2xvs+CxhrwwhRefK4O1qZyKDMdes2DfozJygNCWLRGpFYSEmbgBNhpVEVoYsT/Atz1P41BTKUpkIxSLhHdO7sF7Z+rw628e5AiFpDfefLrlN8ayo+9zNfTCiWO7YMqTMD72AFW7jXh8XyHq63bgoeoCcFXF4Mh9DA7PoqWpGIdaynHN7kWJ6sHx+Qvpo2QlzDRx07vAD7GKxbCPhnWXKFftMuBCz35MTPqxR9mG3ZbtwlKLcLoWcHfah5Li7WhpHYDbraYK+7RQgWZDJG8tTYjGO80QeZnrdhAdb9zAnNdPGlpJRkCpMuHmLR9a236Bx6NSe07RJ8zUFSvrxZ9s6e/QwBWKa7S9UonnD1agttaEfxfEQJx+LC6DMpSVMnzf+wR+sk3h6/N3MDXwDyIKXxU54eQi6XaHCYWgnZTEoKSKHy9PwT0XwFMHilG3tzA8SKJ0HgwAX/Y4cHVoFjduzsOcm5LENDro0i2JMCcyjdFKzHo09F++i5lpjUwWCOsnHqRN6CQjRaEHA4Nz8HhV5MhHwCrSgKTp+JE4pjjeXoO336rG+Pg8vvhqjCq1pXYy69kPruHevQV0nq1HQ70BawUa5YieSWhaNh4KC4wYHrqPvZ87oWoa7ANWfNo9Qc6dTw7sh8e3gGebH8bBl4eRd/pPVFYaaBIa1gIUYQ0SzVhZXunzBfDHLS9CKsOT+3cipEn48BMXSksMUFWOjz6eRJlZj53lBviDlAL+DpKPZZl9koCsYxVOrSRuZiivyEfzSz+TL3Fcsrlx/foMXHeCaH11BJUVRszMBLHGkGkJZ3Iyvt/03RZOEyZ3iaLvr7HIcjHh8pOR/JlvprIilArh0IkMO/r70tolOK4DmTD0r/v63CYWyGnpFihVvWC5B7+bcUY7OZ7MjzYWpHW7pHFcxRYB6XdCxKsdWwRE6IpYp8XC5sAWAJmsRlp2htpUsMhxySlFbzI6M60zOsXfYjrRImZTsDkQmqkRhcVFiLaRR7FJIK2cSdhApDq1yGZjw0SNmiohxJGEEuXoBhJypDrFxkgpouMGkVGQCWK5aR3JjPJsA0iocj18SrwzrZlSIUSngTUyoSO4us85KYmtxuFtmXwXiiHrfVbU9od5ZLZKVGImcAthkaVILNpZf9L7Dy0jxn7ubxg3AAAAAElFTkSuQmCC">
                                中文繁体 </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAUNSURBVHgBxVg9bBxFGH0zu3dnC5NsYiRiy5buQAghRdguoAEpthCCEhooKHAoaJ0ChISQHDeR0kDcUNAkqUIXoEBISOCUacCuaCLdWnEEwgqs49+73Z3hzeyeff+3G5zknee8tzvzzfv+d1cgJ6peuVyCekcAU4CY1YCHZBgEHL7kUNC3apDfVQLfRw6IrBM3vMl5B/JDEphFPqwqqOWJ4O61LJMHErrrTcw6cK7ysIz/B1pNLQ0i1pNQ1fO8gjyxKLS+gGOE1rgSYnupEgQBshKqemfKBeHe5OVpZITQyAHh1yHnusWX6E6m8CsyusgQMUKkdKBULlZ+DaKDlGwlQzflIGMFjA0l+fZBCC0Uz+h0DES5CH2TWev1JGRipp2MHiA8/KuGoUsxnI9DuHMSiqRURv/RstMsIYtdCZm0RlMAG5GFs0VEo3We7r3BiakRhLcV3DWB8DfFTSTyQVwwmdxEMiV0arKKFutolD6hC+5L1K+KnnaSvKBpEfk8idxpsqemaGHIxY2Z6AN/PFivHM6y1mlzVQwF53UX4q2QActpWiabdNPRnL9zFDnqTa4fVahPOgNdnqKcckhpC7EghwsQJR4XgchRiKmxU6mheNZBWNmBGmYzMIMxrF1jMWmHEsnQSa4Bz2gUP1IY+YHytg6sDipDP5DsApaKTXMUq9FzCqcXS9Aze3CltJrFMrRqC81aLRxuKbD9k4v6Za7cTDRv7CVtcvGrzHiaZ5APx9j7WUL84tpZUmeJLVl2Ph06Pc8d35asm7vf0yJm85drCJ3YyrfDpHONf5cL2LkSAfu0R7pBg5BILA1sSUSbI6jdOIBTpd5WjYbbNPp3K7UuhdDnGiJdXUDtG4n7C07rNH4efB7j4FtqD1pK9NdW/rGPonQhRb6MI90pWlqX28+WXtJ0kUDiJPPfwVMvFNEwmRhQkcXht3GXUS4bMe41y5mi3HzSBGDpDdpkW2D3i2HUL3lw6oybVyMM7gw24KAYR9ErxtWRkYgc8IwK3pE4aj9h7DGMrffp/Q0WO3WA/dsxRr8cxsHJCHIbfXV0Z2jRzxiLoxq7X7Gw/phoIbMVb0+wIDbVMmp3hot3mN7bSQyYizYwn6XVCpyzkWrcFtS2QPJXzDwf+noHYnwIW++xrUSlwwaczuzLyBD6t9lKdi8r2mmbqVpLXBdCBpGpTuOUELpwHcq4F6UcGq7rnWW8EpipQeeFzkU9W8cI09yJUH+tZiW65usez2+yZfwZMyOFreRHH/QbvmSg3kIGWKHNI6UZ6gjeDYlTi08DL9btOeNqW31EU0Cbkj1g0N3rrMliFZnQrk9iSfcknfE33fnPHsIa0NslYuCgu1aEeawpiLjaKSBb7YhpBXYz6GlaZo1rlJOIT30sVfbiqOu6Iu0tpNYreEjI9Nv53T38letGNgWVWB078P2UvriOh0Qjpmy3z9LWe0BpvZwSS9B5g5b3zq8VMp/L/DMPqpWWXXmbeh5PCNx7qXF8SGgy2FjRqdkeJ5gOy2Pb/jW0EzKI4F5E5jJwLPBrEhebT7QQYsYFoZbvmidLPAYyTHPzoBj0JJSS8klqzizAo8OqIWPSvP1C1xRISImZRxFTJmb2ZXcyBgMLx4ZXnmeh6XiiHYQuae9rpc+P7fgr/dbleGFFYtALXJHpjcghoVis8PB6cyYdC6EGbO8D+EpPnzMPPZRQRusrvYDvlFbYKtfYa3O/0vsPDe8XgOYRcaEAAAAASUVORK5CYII=">
                                中文简体 </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAASqSURBVHgBvZhtbFNVGMf/z+lte9ttUEG3oQxuYUYEnFNjghHiZjLF+DInRAIfZH4gUYKMKInGD7wkfjDxyyDG+BKzLSZmxAhZjC8YIyPTGCBIAypEiS1MY4tRu63vvb3Hc+/S2jta19ve7felp+eec+4/59zzf55zCBYJyj7FTe5ujXg759QB4j6AfNNPeVSUQ+AIOYBR4jSyJBUOWRgeVGnDq3U39ZLGtoseHbBGgEjrXxr/c6iSxrMKCso3dkjkGBAtFdRGSAg7MJuwsoKCPp/PkXXvJ449sBHO0Z9zpw/6o9EoKhUUlJsVifFjotiOuSEkadRZ6vuikmKIn7BhiaoSxYr/6Ms0T2J0FFWsgv5OlBOkfzPzJCZPuyMt3llEYcmMbc3ZgKk5I5DXaxR5LI45QyxdSyo8ahI07mkK5mdH7noQns3dcLW3wbGk2Xiei0SQOXUGieGjSH/zHWwm1JKI+AuC8rPjar8DvsNvwHWPeXNxVYX64yVoyQSkW26GNhnDX1t6oQZDsAvhUb26R0nGH0598sYuLHrvMNiiGwqNMud/QOzQ20iOfAqeTBbqnatvg3frZmROn0Hqq5OwA66xXvEzRPo2l1uXBxvHjoP5FhYaxIc+RPTl/eBTU2UHcbathXp1XESwCdiBsAG/xIg/ufjI4PVidu0VqrX/HSArZrAYkt3gqTSqJSO0sLqtmx5wrl5VqMz9/gcmDr4+q5hSLHxtH2rBQfxOVv/MNqW4Mvb+ELTINVSD/OjDWPDqS0ZZWt4CT8/joiBV3J+DOpi00q8UV2bGatvS9X070XTqBJq+HwM11ANih1pARAqvp2Dd+vpnL/8KK0i3tsIjZsbT8xikZUuNOrb2diSOHEXig2FYxMfEPBXBdUOAFcjpFLOQBc1YGh6PGbmGVZgWjxfyEpJlOFf6LQ2Q/ekipt58F5H7u4QF/GaYaObceXi3PY26HdthkShTL/1sSpRc69ehFibFDr22/iFE7t4ALToJcjAr3UMsNTpmstqGF3cJt/ahGlKfHcdU/1tGWb0yjuRHx8BzlduHWOErjp1nLyqeR7o25oMoud1gTY1IffJ5ZaMUfXPpk99a3VVmQcA7jj6pIawGAnu8Tz0B8sjGA1fbGmPLZk6fFT6QKTuA1Lpi2kDz7pzLoRacnJ5nfpFCZs9dGP17x27wicnCw4bdz6Fx7AvoQdfwk+KOIoYt2PcKJGWZbXFMENDT2en0Q27uJcYHpBUKFg8PwrlmlamlFk8g98tlaIkkhJEa4eWfF/YiG7gAuyAikX6Eh0omaK4N96Fuyya41t0LR2Oj8TwXDiMrtnPi4xGkvvwaNmNO0AxBcnMHmEjwZyqvrzN+uZgdVBFwKyE/O3q5YBJGTks4NLOxnksb+fQcidHfmRdjEqSjOtMHhIQA5o/Q9Dv/wyRIP96qGuvRby8wD2L0g+LMI/V1vq7bgMqpc45FBcodpUsGGkOUO31XqW+qZsSYqivdWe7eaNZcw/Ao4nacaEPiQPhs/kBYjsovrAzz1PpEF2s3IlzcpDEaLN5JtgjKox+b3MS7RdTSr/MUUaWYrvQ4RcWMjjJOgWqu9P4FOjjhTM9neMMAAAAASUVORK5CYII=">
                                Türkçe </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAALtSURBVHgBzZhdSJNRGMf/7zan+dUqx2p1MdFAM8roIoigmVAXXfgRRXSjRFdBqHRTdCGBwYpAwauCLgzqpg/swstyeSnUvOgiNH0naWRkGmIunXs6Z+9Wc0P3nL1u9oOHDfY/Z//3fLzneY4GRYjIIz4aRNSK8IpwxEIyLyIYC7+IV5qmBZENhJFWEYOkTkBECzYL0ZlXhE7m0U0ZE40dIrpp85F9OlTNeMgY6myhk7EW2WZ0yj56WlNkTJNOuUPOwprpsyR56hThQe6ojf1nKmRs663CG/ehJRjSoTg64fkFzA29Q0ifAkUIhdXl2HHiCKzFRVAkKF6g5fKLLT46KmaW9GmMddzD3JthhBcW1/xmybfDea4elXfaUOBxc7uUG6lFmOrTYoYCMOYzLdMPnmO0/S4iod8b6jSrFQced2H3pbNg4heG6jQytp7OafG59ylG23ziCcCm+tFtuC83ceXlcpc1cpSh4BdM3OpVMiMZu34fS+NTXHmjNHSSo/x0oztlvXCQC3/8Zg9Xflga8qRTLX+dxbcXr5Ep3weGosYYeFmGFj9OgMJhZMrqrxB+Dn/gSB3SUNqTd3nmB8yyPDPLkTksHBWtrMAsloI8ng5G2rkh9j1lMIvduYsjm2cZKj1aI46DQmSKdVsBig9WcqRBaehtOpXNUYKyxjpkirO5HnllrCRxUhoa4Sj3+9qjxlSxu3aiousaV+6Xhvo5yvy9LlQ97IQKms2KCl+HyiHbb4nVTX6O2nX+NA697GGNlK20GDVPfHC3NoDJiPQS3/Z93FbOplM4FngG95Vm2LanGpNm9129iONjA3BdOAMFoueLqQRNNBIJ2nuEJqdBqxEUiQStpLZKvHPyocjfBC2h72hBuFW0rPPA1EO5Z/1UgIwyKJsFYjI6pati6X8qFHNoKsA2kzR92VhTsk+1y4YkY7KA1Mk8OiUUhKaJGctkwQ+Swr2QBkXo35WeF8aLVEbilZ4MP4xDW/lK7w/IrIt1RKmy2gAAAABJRU5ErkJggg==">
                                日本語 </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,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">
                                한국어 </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAANOSURBVHgBzZjPS1RRFMfPue89HU1tpCkwkZ6QIBWpi6CIcFxESzVaBeG0DioQcmmt0kVgtGgTqMskAv+D2RT9gLQfkEj2npQEETKOGjNv3runc2emyXScH/6Ymc/w5vLmnnPvd+7vexCK5IfZbmoCezXpdQJikAD8kHoUEQCyAdBGgrAAnG605+wiigcs1PCn2RZC0AYEUFC5SZSAlNudWBVKnGXhYwF7bhIKIK+gVfNU0AFvXCKZXAXsFNZuG5LuNtrzkzsSRER++W1peO3li9v8RwtvyjxUXzg3ZjS33EPESMFOLMbkZ4b2D0vVUYwYi/YfK68oNvBTacT8RfWCf6MGsUnTMD8mlI7OdJ0ZMmPVJUdN63Hcs+FbOB54PQYa4f8EeVJa3GnmVnNKmxFsP9dy2RSSB7YmtNaMoIvX7ocO1TeMA0jY2oul4e27r6GFVw8mdfViWdFbCxRleR5AGbpMgboe4mQS288OmY5OVrmEbJAE0qVW4biJvrJrSUIgNOoTZOjdQBWhiNsFO4S+y01zL2EVQeHzVZlQKSD4RbXPqIfKwS/I9dagghCaYfwq/5TPEBGC5CfAyhjUjC2OHmn8UBmTDIEkLIozp5snpMdbBqk9rJzK+NKAFE4Ons5Lg/MrK4k2TCstB5T81LQma495iTs6aKMpLbTFdP+PH6RW6Vk+gnQlLaLRaOBAXe1nBBFQu8jOitydJBYU4pvIvyuS53lDqWOuzHL0lVR43o78LciG67qvqTwMZBXkOE6HlHKZSssY5CIej/dT6bBo0zUIt2mpgfWZ908gkdBJUN6gQrGo4jRfjd3Q1dnDA9nOK0ix2Hv5Sv38wmMZiwU40JGMZOzJuokCNJKzMXT6m2zb3py97RXj2PTzZ/HjJ4LrvtqPHrhsyWdeofFqyhOUCyXBriKdqnfckG7IU/ap39lX4xS8hxF0erKJSeqFPNDUVNX30UeDeiRyQ5e/mwF0vtghGLzxEOZ2V43q8pdQ1ZAKZMnrh+0v4Zw+UCDLIyMHY0+nb8q11avViUR70hlTS36qoFRRlP7OvBOG9QRMNC7tUcAqG286zp8MxFb7tHis26cZzR7IFimpzgBc4VaIaGqT5MiZwyG9piJDen8A83hPuU13+kQAAAAASUVORK5CYII=">
                                ภาษาไทย </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,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">
                                Filipino </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAJPSURBVHgBzZg/aBNRHMe/767KgT3SglYUSfNHFycLjuqqi1JaOulSAkEdpP53MyhIqIOTijo4uTmIW10qulVqHXQQ2jQUKYiCUO2g0Dy/v1zpn3BJLmn67n3IN7/k3rvLh7vk3XtRaJVr2T44KsdXA6vZDWifVfGxzOcyNBaZCW57jfuzc60cXkXqNTLiIvsxB63O8oNOoBU0pqEqDzFeeh6le3OhG4eGoPVd9jyMLaHLfLqM8blXaEdIAz3+lcy9P13OBXQSrZ7C/XsLxYVfYc1O6D5AkmVSVdCmjK7fpHQelZ1vcDXdjyhCPJR0fMscQds0/SYchetO4lImiUZClOllecmksf2k4bkvcDOTQD0hUoTYG0Mfw4oqIkyIZ2eQJQ/TOOo8rmeG195uaHqA2HDuVMc6rArx7IyypBAXMsbJwIv1M3QRcbPinJOieHayrLNhfb4tfAV8H6Y40LNvbxfrmQYdjAqRnFyyk7CHARHaD3uoCqVgD3tEaBfsoVuENOxBidBv2MOyCP2EPZRFaAb2sChCn2APE3Lr6OOL76HNS0umR+qD1bkmpd6xHK9tvT02hn+eBxN4njddKBSCySGF8oyujR8MCaYyumbHdy7zOUahedTCrUMxCg0iDLY8ikHoCerB1gQzY1BoCsHSC42kkkzJgFAJwaK0Oezdz0xto9B7BMv16HCv3h3B9e20zGMmgS0gv4D5Doh8YYbRQWTg+tCGiNwFZEXsIgLR/kHbjCybTjOnEMzHU0w3U0Ewt5LpjMwg5Kb9jPmBFvgPXD6BzN49/n8AAAAASUVORK5CYII=">
                                العربية </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAQFSURBVHgBvZjPbxtFFMe/sz9sx3ZSh5Y2oU3ZSilpLm2CoBIgUQeJRuJAW5Ubh6QnDgi1HDm5/AW0QogjVBy4gNoKCY6YM0IxcAgkFd3UaWmrNnXjJll7f0zfrFs7Sb3rnXWTj7TaWc8bz3f2zbw3swySXDcMw9a9EwAf40CeATk0LkGFfjc5YybjrOja9auj5m0TErCohnMHh6bpNkVXHlLwksKUC8NzNy5Fse4oaH54X55G/A0VDXSHqTB2vpOwQEEzRi6X1fsKHPwcni8XVuzq5+NmpYKogmYPDRiqq12m6jFsDaZr2xPt5hdrL0b/Fd27KJYoZf2DcNM2iREYqq5dFn0iSJCYM3HE6PscaC85kIeNZfTeAtoJEss67gTuebOG7OQaYnJullYyNgsiCohJ9vgqMsdiC4LaCCstQU+CnoEYCFelX68hdaQObbeLmBjXXtk/1RQEzs4iJuk3amA9HEqGIzO5irh4nE/7gsQyB+Ox40323ZarMjSXuiA/S3lSYU7iJGIiXJR+22o+Z95Zg7rTQ1yYZp/UVPBjPMiCHJp+ywr8g57XyF3autYUZvunqlj9PRnYxiol4VXbZyxKwkfY3PD+mTCXpQ7XMfjlPWiDsSesj7uk4NbHu2D9kQwzMxUalRFmYf2VQPn0AKo/pRGXRz+nsTA52EmMIMdoyXNEpP+jZez69OGm+B4MtxnuFvqx/EMGUZESJND3Otj73V1KF+EurM3ruP3JTtT/0yEDjZVVZBrYNzWUP9hDww8xorpb0y9KiyEqJIhLCRKkxurhe02qSx62IQvlUlOhwfwGSbIRInLveyuQhbbKCwrnrCTTiKU86qwVnbnFcO+LHVj6age403pt2eNrfjqRwvOKCteUKzJt0kfrYMlGR/aiisUPd+PB1324f7HPLzs31YZwskm9KpdKuKNfUUb/MU0qF6M26n2/4YpHv6RRPjUA6+9Es86aSeDG6T1Y/jGzwTaSGDoujZqmqYkHWsCX1AjnLZEmeo7W/Njy8PtsWxt3ScWdz16A9WcC/Weq5GLuu7UjdHbzb0+f/z04dJ112BPpQw7UnLfhrYTav+yA1xmc/9VQO5oA5sh8+YAoN2Mu7UfOoAN2WYssxrdf0DqK8aED5NNiU9DotcUiqbqIbcajPkfWnWY3ZCXd1c6TXKkw0A3CVWt+ny02CDpgmhVXVU4JQ2yDGM9WJ8apTwQJEogw4KnqxNaK4iUhRizzzTVtNxJCVMJWx7diTok5s2JrbcUIOgaIWToikeoC6/J47buIVrK/eEKI/MFKCFM5P0tLVO6EwnmRK8q3I8/rg9Uzwg4Z9HXEO8E4z1MMpiMUM7Dukx69iQrzUxErubZyNcg1QTwGGWCKt0sSRsoAAAAASUVORK5CYII=">
                                Tiếng Việt </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAPTSURBVHgBzZhPTBRXHMe/v5ld2ZDUrNKqQWoGbBs1HtaikYRUIWlvRrc3E03EU+VkOdk9sfaiTZtUe9k0aUPbxIMHY/Rk0pCCq6lNMCyHhmKRXbksBJBXqzjs7szP996Kgi7CDLDrJ5mZt++9nffN7897bx7BI5xoskD5IzAQAXNLvoBwnjms2oJEIhhEBkzyQg/YuUbtqYyX99NyO3Li4zaYdEIO1AIvEKekwAv0xd1fl9V9qQ6c2NsCg7tk0cLKyMgrvpSwRQVxVySMnNEpu3yJVYUuYF3hLJ1MiZKtpSo5EbFgBq7KGIlgbcjAdVpLxReVFGOYf2DlLvIlaoGgopvM/jKIeT66DPig2zrffcaCDjpmyiRGwRRBLtA5v+qFhXRaG9SFSuCSdF1fjyq+tJBBnagUxWmlWFQ3bZ1yuup1LP6x8QTmBMn7aVQaQpt+MLOFmak03gaqa+oD8hGVBfghk5nEpUtJ3Lo1hKqqIJqaPsDx45+grm4jfBKVkzFfZR9cvvwnHzz4NSeT//Do6CRns4KvXPlL1p3l3t5B9kmXEtTPHhkeHuPDh79jIWZ4YOAB37gxwDdvDvLIyLiuO3AgzuPj/7EP0iqGpqWpwl7seurUzzh2rBkNDZvR3f03xsYEbDuHnTu3YteurZiefoJkchCxWBQeEYZXMQrpHjlwHdLpCQwPZ3Hnzr+4dy+LoaEsHj+2tTAl1AdhAz7I5fJ6YCGeIBRah0LBwdOneQQCJiYm/tcWWr++Gn5QWaYWNk9W2rIljEePbNTWbtDW2r//QziOg02b3sG2be/i/v1xWNZ78IFQQZ1mj8g056NHf2BpCZau4uvX7/Lt20M6wFW2HTr0rQ58H/Qrl/XCI83NH6GxsR4dHb/JYM5j9+73sWNHLYJBQwb8T4hG92L79s3wwQOVZWqL+j180Nc3gkTid4yOTsE0De3C9vbPsG9fA3zSoZeOjBh5K5YOK9xQr/dDNedDasvaggrCcvf48MzsnmLaM5b1zbSWkPoawbwd48bzoTRVaE/Ecp1++JVdr8ovJkaDcRIVggjxubI5V5jpLmSqPzU3yOYmlBOXLk7F7G/mfi5YOhx7Ni6zLoUyoVzl5Kri8+sWCBJxCBf0ORe/w9dcjMtoFXEhFhWkRcVs3XEtRTFTSouRY73aVnK116Jse4/860WsNjJm3NmqkmIUSx7H1JwLtTGhc6VTgrK4yuTJmN3zpn7LPrDSwsCnicjriUiPHOWXqTP26hxYvUr4XMgyTD5C8iSNXWm14lXcTxkQZJJgJcLglOvQtcVcsxjPAC7R5cqOcn7eAAAAAElFTkSuQmCC">
                                हिन्दी </div>
                        </li>
                        <li data-v-1ab1c4d0="" tabindex="-1" class="el-dropdown-menu__item">
                            <div data-v-1ab1c4d0="" class="lang-item"><img data-v-1ab1c4d0=""
                                                                           src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAACXBIWXMAABYlAAAWJQFJUiTwAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAFnSURBVHgBzZiBbYMwEEW/UQdgBGeCdoOyQbMBdJNuEFboCJkAOgHZwHQDNrjeFVMhlEQmBfu+9AgQYp7sIxY2WBkCLH+8MS9MweQeycD0npY5m3F/+7BIxTQMraRjSmwVbqxg3AMiS9y/xPjHOXPaQGSJtJmvlbG+q2knnK/FYBm3o0y4lB+mGDLzgs/vCZ0onsxfTd2SqRLITBTXhFxCIaepdybKuVBHACWmERdD46PnoCOHjDdH6MlRhF6hJ88iZKEnhTahXIqaoChPvw+cosiQDdCTQZtQL0Jf0JNvEbpAT1pDRBaapg5jTI/xHSp1LuKS+YNPpE8tGzMd8dDJsFmkSc+9c5CdbHbyHenycfUs91JN8VPf1OQvc6ajeHFyT9wLX2D9hTFkLEISQaoLllkM3x41JW2uW2xYiFW0TW85psBW8WKPFHzDlKH3MVgZGsdelvQKjH+kwnxJT2gxTtpnPzUF5wcJwibcv2WC2gAAAABJRU5ErkJggg==">
                                Bahasa Indonesia </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div data-v-1ab1c4d0="" class="content">
            <div data-v-1ab1c4d0="" class="content-text">
                <p data-v-1ab1c4d0="" class="title" style="width: 1000px;">{{ __('lang.tiktok_wholesale') }}
                </p>
                <p data-v-1ab1c4d0=""> {{ __('lang.partner_program_intro') }}
                </p>
            </div>
        </div>
        <img src="./filemanager/userfiles/banner.3b4dbbc3.jpg" class="bg-image">


    </div>
    <div data-v-633ac152="" class="merchantSettled-wap">
        <div data-v-633ac152="" class="step-div">
            <div data-v-633ac152 style="    font-family: -apple-system, BlinkMacSystemFont, Helvetica Neue, Helvetica, Segoe UI, Arial, Roboto, PingFang SC, miui, Hiragino Sans GB, Microsoft Yahei, sans-serif;">
                <div data-v-633ac152="" class="content content2">
                    <p data-v-633ac152="" class="title">{{ __('lang.business_information') }}
                    </p>
                    <p data-v-633ac152="" class="info">{{ __('lang.already_seller') }}<span data-v-633ac152=""><a href="/admin"> {{ __('lang.click_to_login') }}</a></span></p>
                  @auth
                        @if(auth()->user()->ten_cua_hang)
                            <div class="alert alert-info">
                                Shop của bạn đã được đăng ký: <strong>{{ auth()->user()->ten_cua_hang }}</strong>
                            </div>
                        @else
                        <form data-v-633ac152="" class="el-form demo-ruleForm el-form--label-left" action="{{ route('partnership.store') }}" enctype="multipart/form-data" id="myForm" method="post">
                            <div data-v-633ac152="" class="el-form-item is-required">
                                <label for="sellerImg" class="el-form-item__label" style="width: 200px;">{{ __('lang.shop_logo') }}</label>
                                <div class="el-form-item__content" style="margin-left: 200px;">
                                    <div class="van-uploader">
                                        <div class="van-uploader__wrapper">
                                            <div class="van-uploader__upload" id="uploadContainer">
                                                <i class="bi bi-camera-fill" id="cameraIcon" style="font-size: 30px; color: #aaa;"></i>
                                                <input type="file" accept="image/png,image/jpg,image/jpeg" id="uploadInput" name="logo_cua_hang" style="position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div data-v-633ac152="" class="el-form-item is-required">
                                <label for="sellerName" class="el-form-item__label" style="width: 200px;">{{ __('lang.shop_name') }}</label>
                                <div class="el-form-item__content" style="margin-left: 200px;">
                                    <div class="el-input el-input--suffix">
                                        <input type="text" autocomplete="off" name="ten_cua_hang" placeholder="{{ __('lang.store_name_prompt') }}" maxlength="50" class="el-input__inner">
                                    </div>
                                </div>
                            </div>

                            <div class="el-form-item is-required">
                                <label for="country" class="el-form-item__label" style="width: 200px;">{{ __('lang.country') }}</label>
                                <div class="el-form-item__content" style="margin-left: 200px;">
                                    <div class="el-select el-select--medium" style="position: relative; display:inline-block;">
                                        <div class="el-input el-input--medium el-input--suffix">
                                            <input type="text" id="country_display" placeholder="{{ __('lang.country_prompt') }}" readonly class="el-input__inner" name="">
                                            <span class="el-input__suffix">
                                            <span class="el-input__suffix-inner">
                                                <i class="el-select__caret el-input__icon el-icon-arrow-up"></i>
                                            </span>
                                        </span>
                                        </div>
                                        <div class="el-select-dropdown el-popper" id="country_dropdown">
                                            <div class="el-scrollbar">
                                                <div class="el-select-dropdown__wrap el-scrollbar__wrap">
                                                    <ul class="el-scrollbar__view el-select-dropdown__list">
                                                        @foreach($countries as $country)
                                                            <li class="el-select-dropdown__item" data-value="{{ $country->iso }}">
                                                                <span>{{ $country->country }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="quoc_gia" id="country_value" value="{{ old('country') }}">
                                    </div>
                                </div>
                            </div>

                            <div data-v-633ac152="" class="el-form-item is-required">
                                <label for="name" class="el-form-item__label" style="width: 200px;">{{ __('lang.legal_name') }}</label>
                                <div class="el-form-item__content" style="margin-left: 200px;">
                                    <div class="el-input el-input--suffix">
                                        <input type="text" autocomplete="off" placeholder="{{ __('lang.real_name_prompt') }}" maxlength="256" name="ten_that" class="el-input__inner">
                                    </div>
                                </div>
                            </div>

                            <div data-v-633ac152="" class="el-form-item is-required">
                                <label for="idimg_1" class="el-form-item__label" style="width: 200px;">{{ __('lang.id') }}</label>
                                <div class="el-form-item__content" style="margin-left: 200px;">
                                    <div class="uploder-center">
                                        <div class="uploder-two-wrap">
                                            <div class="van-uploader">
                                                <div class="van-uploader__wrapper">
                                                    <div class="van-uploader__upload" id="uploadContainer1">
                                                        <i class="bi bi-camera-fill" id="cameraIcon1" style="font-size: 30px; color: #aaa;"></i>
                                                        <input type="file" accept="image/png,image/jpg,image/jpeg" class="van-uploader__input" name="ID_card_photo_on_the_front" id="uploadInput1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tips">{{ __('lang.front_document') }}</div>
                                        </div>
                                        <div class="uploder-two-wrap">
                                            <div class="van-uploader">
                                                <div class="van-uploader__wrapper">
                                                    <div class="van-uploader__upload" id="uploadContainer2" >
                                                        <i class="bi bi-camera-fill" id="cameraIcon2" style="font-size: 30px; color: #aaa; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                                        <input type="file" accept="image/png,image/jpg,image/jpeg,image/gif" class="van-uploader__input" name="ID_card_photo_on_the_back" id="uploadInput2" style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tips">{{ __('lang.back_document') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-v-633ac152="" class="el-form-item"><label class="el-form-item__label"
                                                                                style="width: 200px;">{{ __('lang.image_example') }} </label>
                                <div class="el-form-item__content" style="margin-left: 200px;">
                                    <div data-v-633ac152="" class="correct-warp"><img data-v-633ac152=""
                                                                                      src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGcAAABECAMAAACrp9tTAAAC91BMVEXt7e3u7u4AAADu7u7+/v7s7Oz39/f09PT09PTt7e3v7+/v7+/w8PD29vbt7e339/f////y8vLu7u79/f3////v7+/v7+/s7Ozv7+/////7sDPh5fb29vbU2vL7+/75rjv3+P3s7vn+68zZ3vPk6Pfu7u77+/scJTP+9eb/qoPz9PTw8vrp6/j19vw+RVH9/v75rz7/+/MVHSn/q4T5rzT2rTT7/P7+rYb8zIQbIzFfOipSLh1GJRcuFAr//fv5+fn82aP/s4r9q4P7qIH3qIHuoXzsj2/mh2b5t1E6Qk04P0r5s0bkojnjnzNrRjHKjC9uOy8WHy1dNiJQLyEOFiE7HRD19fX/+O/+9un+8uD+7tP95b/94rr/wKL82J7RsZ78tpPGpIz/r4f3rYb7roXlnHj7x3emiXPZlnKegG/7w27ShWnYj2jPiWW/f2P6vV+pc1f7u1GSW0Y0O0bkm0GHUz/xqjjppTYdJjTmojLTlDLQkDFrQTEmKjGsfS+cZy2qcys5MitWNymOZihlNCdjOiSIWiBPLB1zShxqRBw8IRZQKw8oEAf//Pbe4/X49/T+9+v+7dPuycn96cjrvr790r33xL3rz7roubn837D93Kv/yavUqqvfvanVtqHxvaD5v57woZ781Zr/u5rWqI780Izkq4r/sInNoYnphIm5m4jHm4bzrIXfo4OolYLTm37yo3zdn3v2nXranHj6x3fEi3fxl3XyhHXtgHXJlXT7xHLQlHHiknHJknDwpW7limznimvNj2nHh2iUdmfNh2GwfF/soluoflqhc1iZVlj6uVd8ZVfdmlF5XU/6tk6aYE3jlEyvikyZfEvwqUjroUijd0iTY0g3PUdzSj/OkD5rTD5+UT13SzmCSjlFODlyVDh2SjjuqDaDRTQ2MDRMRDPEhzIxLTK5fTGrczEYIjFvQy52Ni2xdCtjTiuneConKCl/XidsUiYkJCVtQCQWHCRBNSNLKh1zSxtmQRsJDxpSMBVAIg02GQ3KynmGAAAAGXRSTlOAvwDf/kD75+CPIBD67aD+8/Lx8OGfb2Aw6MlFwgAAAw1JREFUWMNiYGJiYmWgLZAA2sHAxMXMzi5DQ8DGIcTHzcTAxczLIklbIMLOyMTAJyNJeyDIysDMIkl7YC/KoCpJB2DPSB971Ebtoa49fcpkA5LsUZYlG4xoe1TIB4MyvY3aM0LtMdTAAWBK9eVIAzjsUZLCAZSgSnWlSQMKo/YMS3tITW/6hOwxrX5R31BlgrCHTEDAHt8f1q+CWm1++dPUHpPnrTZ+wdY2oe3W/rS0502Q7e7la45afesK6jalnT3ln7sOZ2hqGpsnb7Syfko7e6pDz8521gSDZbfqaWcPQP6h8bGaELA4s5Os/KNIjD0Pm6YmJCeBAi4lNv477ewx8Ztec+eBuabr7dLT8xpQ7DFTIA6YEWOP/NUF2ekHDzi5b83MTrxJu/gpOZSQmr5+v5N74roNS47Qzp4rufM1nV3cjN1cjDVX5JBlT2TcliyLGDv89hjkzjV3zdnmvGmPa9LqYyj2KCgSAPrg6t/CQB4EPGLwlwdnVq29W9TyqKU0YOdxedLSGygB9O6ShwKDmXjtMb3vl3dy0owpc/KK2qRI9w9Ai+ThwDsSb3ndYXtt+8ppC3cUBFeRXo6Gn5NHAAu89vj+tkkNeB2Q0dRGRr0QLY8EPAzx1z/dwVa2f9o7TMiwxwHZHoMI/PWpj1WPbU+nKUa9jbMAINOeyy4nagouEN8O0SYv3Mr2GRs7OrmX+JSRbo+GN5I9abjTQWXd2yeNmzWBwO1GbWNzJUq4EdOwWYoUbBNx2WPS/MlIXV392V5Hx5TzluqWge8/mJJY7oTnw62Jw5lPAXsXog4GgY/v1b4Es4w+klq+GaYZQHLpLBac9nwNCVRHBpYVRl/K4fbIaaMDrBbZTbbIP5XlMAF3Oerz08gIZDrcHqMKo5A6POmArHohqvBisZennp6ep5ceGHjpeepdL75UGAWzRwcdkNdvNOzXwgLCDO2GZv8UBYzaM2oPTYAaI53G/TkZGMIkaQ9UWRm4mdkEJGkLhDk4wfNMPLz8MrQDbBw8nExM4HkzMQZaAnFuoB0A7P5BLsP4hxAAAAAASUVORK5CYII="><img
                                                data-v-633ac152=""
                                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGcAAABECAMAAACrp9tTAAAAkFBMVEUAAADt7e3u7u7u7u7v7+/39/f9/f3u7u7t7e3t7e3r6+v19fXu7u79/f3u7u7t7e3v7+/v7+/v7+/v7+/////h5fbp6/jw8vr7/P77sDPu7u73+f329vbt7/nl6Pf09fz29/z09fv+/v/5+/7+4bL8w2b/9ub7ukzy9Pvz8/P/+/T8x27+7dD+5bz7vlf7sTidTytrAAAAFHRSTlMAgP7fIPzx3qBwQPHu5r+Pf09AEDz6N9UAAAGVSURBVFjD7dnbbtswDIBhdW562nkjRbaMTFrqWqfd4f3fbnaTi8XoGri2gjTQf0kJ+BDAig3IOff9S3WTs+ri1HWdVouPEfIVz95XPVQtIXeLyrlvN5C/1ZW7XEL+lifuZI9OFDYQjWJgyhoZGEw4sWymat3UuqkKmPZrKmLMScE0mUSVpKJRE6tylP84HKSGgBJqqFFQFBTqIKR+M8VakT00gKHb0a9hCLU2hOCRvGf0hN1WJEFsOBSnOC840bgFI7YWWmLiBAlaY0nNZkptoqjdlKzb0a+RWZuSECiJNpEaISMmYaIU7TnnCP8PhhWnOH3H4NzdbvXrIY/zcH+93SM8JVNL287d9aD7tYNT88U5Bocnt9OZqcNyaGrNwPkzcH7neQ7i7Y+tHn+uHT+1cOTvhX8rzkwOz3J+dieznJ/dpXJ+3oTD44uvcTyOzh+yE3l8h/wczOAkylMzcBTz5AeO+Wcr3wfFeZPO5Z6cqxXkb/XVuU/5f9Dy/On+Z3WW9/5ncb6+aLoYf5/1bkQfPnfMvvoLTBw2j2ZJhnAAAAAASUVORK5CYII=">
                                    </div>
                                </div>
                            </div>
                            <p data-v-633ac152="">{{ __('lang.verify_email_or_mobile') }}</p>

                            <div data-v-633ac152="" class="tab-wrap">
                                <div data-v-633ac152="" class="tab-item active" onclick="switchTab('email')">
                                    {{ __('lang.email') }}
                                </div>
                                <div data-v-633ac152="" class="tab-item" onclick="switchTab('mobile')">
                                    {{ __('lang.mobile') }}
                                </div>
                            </div>

                            <!-- Email field -->
                            <div data-v-633ac152="" class="el-form-item" id="email-field">
                                <label for="username" class="el-form-item__label" style="width: 200px;">{{ __('lang.email') }}</label>
                                <div class="el-form-item__content" style="margin-left: 200px;">
                                    <div data-v-633ac152="" class="el-input">
                                        <input type="text" autocomplete="off" name="email"
                                               placeholder="{{ __('lang.email_prompt') }}"
                                               class="el-input__inner" value="{{ auth()->user()->email }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Phone field -->
                            <div data-v-633ac152="" class="el-form-item" id="mobile-field" style="display: none;">
                                <label for="mobile" class="el-form-item__label" style="width: 200px;">{{ __('lang.mobile') }}</label>
                                <div class="el-form-item__content" style="margin-left: 200px;">
                                    <div data-v-633ac152="" class="el-input">
                                        <input type="text" autocomplete="off" name="mobile"
                                               placeholder="{{ __('lang.so_dien_thoai') }}"
                                               class="el-input__inner">
                                    </div>
                                </div>
                            </div>

                            {{--                        <div data-v-633ac152="" class="el-form-item is-required"><label for="password"--}}
                            {{--                                                                                        class="el-form-item__label" style="width: 200px;">{{ __('lang.login_password') }}</label>--}}
                            {{--                            <div class="el-form-item__content" style="margin-left: 200px;">--}}
                            {{--                                <div data-v-633ac152="" class="password">--}}
                            {{--                                    <div data-v-633ac152="" class="el-input el-input--suffix"><input--}}
                            {{--                                                type="password" autocomplete="off" placeholder="{{ __('lang.password_prompt') }}"--}}
                            {{--                                                onkeyup="" class="el-input__inner"><span--}}
                            {{--                                                class="el-input__suffix"><span class="el-input__suffix-inner">--}}
                            {{--                                                <div data-v-633ac152=""--}}
                            {{--                                                     style="display: flex; align-items: center; height: 100%;"><img--}}
                            {{--                                                            data-v-633ac152=""--}}
                            {{--                                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAcCAYAAAATFf3WAAAACXBIWXMAACE4AAAhOAFFljFgAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAJzSURBVHgBzZfNddpAEMdnIXDgYjqISsAVBFcQXEHQhY/HwXYFUiqIfeDxOEkdmA5MOnAqyJbg3Hh85j+KUNbrlbTCAjzv7dOyGmZ/zM7ODEQfXASdUSaTiVOtVoPNZuMOh0Np0jkbIMNVKpUnTB0Mud1ur0yQZwHU4PYi6/X6peu6L6ruJzpQxuNxC8fzBdMWNmvtdrumuqEQIuz1eq4lHEuow0V2qIAEQdBcLpe3mN5gNNP0DoDz+/3+d6MtspA4mD14qZunWyacFeB0OmWPeZThsWPBZQLuUwC81k5RkRgzjGfozTH+mGLoPXCpgBlGGSCEp2bw1E/KkSJwfOlqtRrnxGs13Qhbo/DkHCM1odraMcFBtwvdIP74KicKC6MvAPMHg8EDWUoROMR4B49HTS/JiRV1FXH0qBmV6/X66lhwLFjjOPa1ZWe1WkXQQvklrOSpcGnlpyw4VbC/p4MiHu+EYvi3+hKeuxyNRs90AjgFMsTjm7oWHXFsOBHE3O2p4VgQd5xzJemA9C+fJYI00qYTw7EsFgsuBs03gCBnQ1JZ78QxeTI4xZYKKCNAvs64EHqJ8rIgy4TjJsSQQfgku0mawW2d43GnfXdHR4ZjW0gpT4j7lm6Lq9WbSgKv/cCDg3WGza6PDNeOK4ijvbqHrchZxlrMOQlx+aAX/7Lg+EjhNR9eu9Hf6R2RdcNaBlzssa+YdsncviWeKwRYtCuB7n5zBx65iNNWm9J7ytR6nwv4jq7ESvK6JHEuOAaDvp/XV2b+q4OBTplwDIXjnpsu4EGAALnHjb6g/12G8UIA7jM2D9U1gDAAD27ZfjUaDWkLpcpfI5uTMo7PgggAAAAASUVORK5CYII="--}}
                            {{--                                                            class="password-icon"></div>--}}
                            {{--                                            </span></span></div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                        <div data-v-633ac152="" class="el-form-item is-required"><label for="re_password"--}}
                            {{--                                                                                        class="el-form-item__label" style="width: 200px;line-height: 20px;">{{ __('lang.confirm_login_password') }}</label>--}}
                            {{--                            <div class="el-form-item__content" style="margin-left: 200px;">--}}
                            {{--                                <div data-v-633ac152="" class="password">--}}
                            {{--                                    <div data-v-633ac152="" class="el-input el-input--suffix"><input--}}
                            {{--                                                type="password" autocomplete="off"--}}
                            {{--                                                placeholder="{{ __('lang.confirm_password_promp') }}" onkeyup=""--}}
                            {{--                                                class="el-input__inner"><span class="el-input__suffix"><span--}}
                            {{--                                                    class="el-input__suffix-inner">--}}
                            {{--                                                <div data-v-633ac152=""--}}
                            {{--                                                     style="display: flex; align-items: center; height: 100%;"><img--}}
                            {{--                                                            data-v-633ac152=""--}}
                            {{--                                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAcCAYAAAATFf3WAAAACXBIWXMAACE4AAAhOAFFljFgAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAJzSURBVHgBzZfNddpAEMdnIXDgYjqISsAVBFcQXEHQhY/HwXYFUiqIfeDxOEkdmA5MOnAqyJbg3Hh85j+KUNbrlbTCAjzv7dOyGmZ/zM7ODEQfXASdUSaTiVOtVoPNZuMOh0Np0jkbIMNVKpUnTB0Mud1ur0yQZwHU4PYi6/X6peu6L6ruJzpQxuNxC8fzBdMWNmvtdrumuqEQIuz1eq4lHEuow0V2qIAEQdBcLpe3mN5gNNP0DoDz+/3+d6MtspA4mD14qZunWyacFeB0OmWPeZThsWPBZQLuUwC81k5RkRgzjGfozTH+mGLoPXCpgBlGGSCEp2bw1E/KkSJwfOlqtRrnxGs13Qhbo/DkHCM1odraMcFBtwvdIP74KicKC6MvAPMHg8EDWUoROMR4B49HTS/JiRV1FXH0qBmV6/X66lhwLFjjOPa1ZWe1WkXQQvklrOSpcGnlpyw4VbC/p4MiHu+EYvi3+hKeuxyNRs90AjgFMsTjm7oWHXFsOBHE3O2p4VgQd5xzJemA9C+fJYI00qYTw7EsFgsuBs03gCBnQ1JZ78QxeTI4xZYKKCNAvs64EHqJ8rIgy4TjJsSQQfgku0mawW2d43GnfXdHR4ZjW0gpT4j7lm6Lq9WbSgKv/cCDg3WGza6PDNeOK4ijvbqHrchZxlrMOQlx+aAX/7Lg+EjhNR9eu9Hf6R2RdcNaBlzssa+YdsncviWeKwRYtCuB7n5zBx65iNNWm9J7ytR6nwv4jq7ESvK6JHEuOAaDvp/XV2b+q4OBTplwDIXjnpsu4EGAALnHjb6g/12G8UIA7jM2D9U1gDAAD27ZfjUaDWkLpcpfI5uTMo7PgggAAAAASUVORK5CYII="--}}
                            {{--                                                            class="password-icon"></div>--}}
                            {{--                                            </span></span></div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                        <div data-v-633ac152="" class="el-form-item is-required"><label for="usercode"--}}
                            {{--                                                                                        class="el-form-item__label" style="width: 200px;">{{ __('lang.invite_code') }}</label>--}}
                            {{--                            <div class="el-form-item__content" style="margin-left: 200px;">--}}
                            {{--                                <div data-v-633ac152="" class="el-input el-input--suffix"><input type="text"--}}
                            {{--                                                                                                        autocomplete="off" placeholder="{{ __('lang.invitation_code_prompt') }}" onkeyup=""--}}
                            {{--                                                                                                        class="el-input__inner"></div>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            <div data-v-633ac152="" class="bottom" style="display: flex; flex-direction: column; gap: 12px;">

                                <!-- Checkbox -->
                                <div data-v-633ac152="" style="display: flex; align-items: center; cursor: pointer;">
                                    <input type="checkbox" id="agreeCheck" class="circle-checkbox" onclick="openAgreement(this)" required>
                                    <label for="agreeCheck" style="margin-left: 6px; cursor: pointer;">
                                        <span class="van-checkbox__label">{{ __('lang.agree_text') }}</span>
                                        <span data-v-633ac152="" class="color-yellow">{{ __('lang.occupancy_agreement') }}</span>
                                    </label>
                                </div>

                                <!-- Custom message -->
                                <div id="customMessage" style="color: red; font-size: 14px; margin-top: 6px; display: none;">
                                    Vui lòng đồng ý với điều khoản trước khi gửi!
                                </div>

                                <!-- Submit button -->
                                <button data-v-633ac152=""
                                        class="button-xy van-button van-button--primary van-button--normal"
                                        style="color: white; background: #EB174F; border-color: #EB174F;"
                                        type="submit"
                                        id="submitBtn">
                                    <div data-v-633ac152="" class="van-button__content">
            <span data-v-633ac152="" class="van-button__text">
                {{ __('lang.submit_application') }}
            </span>
                                    </div>
                                </button>


                            </div>

                        </form>
                        @endif
                    @endauth

                    @guest
                        <h1>{{ __('lang.thong_bao_doi_tac') }}</h1>
                    <br>
                        <a href="/login-form" class="btn-submit">{{ __('lang.dang_nhap') }}</a>
                        <a href="/register-form">{{ __('lang.dang_ky') }}</a>
                    @endguest

                </div>


                <!-- Popup điều khoản -->
                <div id="agreementModal"
                     style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            justify-content: center; align-items: center; z-index: 9999;">
                    <div style="background: #fff; padding: 20px; border-radius: 12px; max-width: 78.125rem;; max-width: 90%; position: relative;">

                        <!-- Nút X góc phải -->
                        <button onclick="closeAgreement(false)"
                                style="position: absolute; top: 10px; right: 10px; background: transparent; border: none; font-size: 20px; cursor: pointer;border: 1px solid #A0A5B0;
    border-radius: 50%;
    color: #A0A5B0;">
                            &times;
                        </button>


                        <p style="margin-bottom: 20px;">
                            {!! nl2br(__('lang.merchant_entry_agreement')) !!}

                        </p>

                        <!-- Chỉ còn nút Đồng ý -->
                        <div style="display: flex; justify-content: center; margin-top: 20px;">
                            <button class="van-button van-button--primary"
                                    style="background: #EB174F; color: #fff; border-radius: 8px; padding: 6px 16px;justify-content: center"
                                    onclick="closeAgreement(true)" >
                               Đồng ý
                            </button>
                        </div>
                    </div>


                </div>
                <script>
                    function switchTab(type) {
                        // reset active tab
                        document.querySelectorAll('.tab-wrap .tab-item').forEach(tab => tab.classList.remove('active'));

                        if (type === 'email') {
                            document.getElementById('email-field').style.display = 'block';
                            document.getElementById('mobile-field').style.display = 'none';
                            document.querySelector('.tab-wrap .tab-item:first-child').classList.add('active');
                        } else {
                            document.getElementById('email-field').style.display = 'none';
                            document.getElementById('mobile-field').style.display = 'block';
                            document.querySelector('.tab-wrap .tab-item:last-child').classList.add('active');
                        }
                    }
                </script>
                <script>
                    const checkbox = document.getElementById('agreeCheck');
                    const form = document.getElementById('myForm');

                    form.addEventListener('submit', function(event) {
                        if (!checkbox.checked) {
                            // Tùy chỉnh thông báo hộp thoại
                            checkbox.setCustomValidity("Vui lòng đồng ý với thỏa thuận trước khi gửi!");
                        } else {
                            checkbox.setCustomValidity(""); // Reset
                        }
                    });
                    checkbox.addEventListener('change', () => checkbox.setCustomValidity("")); // Reset khi check
                </script>
                <script>
                    function openAgreement(checkbox) {
                        // Tắt ngay checkbox khi click, chỉ hiển thị popup
                        checkbox.checked = false;
                        document.getElementById("agreementModal").style.display = "flex";
                    }

                    function closeAgreement(agree) {
                        document.getElementById("agreementModal").style.display = "none";
                        if (agree) {
                            document.getElementById("agreeCheck").checked = true;
                        }
                    }
                </script>

            </div>
    </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const displayInput = document.getElementById("country_display");
        const dropdown = document.getElementById("country_dropdown");
        const hiddenInput = document.getElementById("country_value");
        const items = dropdown.querySelectorAll(".el-select-dropdown__item");

        // Toggle mở/đóng khi click vào input
        displayInput.addEventListener("click", function(e) {
            e.stopPropagation();
            dropdown.classList.toggle("show");
        });

        // Chọn item
        items.forEach(item => {
            item.addEventListener("click", function() {
                const value = this.getAttribute("data-value");
                const text = this.innerText;

                hiddenInput.value = value;
                displayInput.value = text;

                // style text màu đỏ sau khi chọn
                displayInput.classList.add("selected");

                dropdown.classList.remove("show");
            });
        });

        // Click ra ngoài → đóng dropdown
        document.addEventListener("click", function(e) {
            if (!dropdown.contains(e.target) && e.target !== displayInput) {
                dropdown.classList.remove("show");
            }
        });
    });

</script>
<script>
    function initUploader(containerId, inputId, iconId) {
        const uploadInput = document.getElementById(inputId);
        const uploadContainer = document.getElementById(containerId);
        const cameraIcon = document.getElementById(iconId);

        // Khi chọn file
        uploadInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Xóa ảnh cũ
            const oldImg = uploadContainer.querySelector('img');
            if (oldImg) oldImg.remove();

            // Tạo URL tạm để hiển thị ảnh
            const imgURL = URL.createObjectURL(file);

            const img = document.createElement('img');
            img.src = imgURL;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'contain';
            img.style.borderRadius = '8px';
            img.style.position = 'absolute';
            img.style.top = '0';
            img.style.left = '0';
            img.style.zIndex = '1';

            uploadContainer.appendChild(img);

            // Ẩn icon camera
            cameraIcon.style.display = 'none';
        });

        // Khi click vào container thì mở file picker
        uploadContainer.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                uploadInput.click();
            }
        });
    }

    // Gọi hàm cho từng uploader
    initUploader('uploadContainer', 'uploadInput', 'cameraIcon');
    initUploader('uploadContainer2', 'uploadInput2', 'cameraIcon2');
    initUploader('uploadContainer1', 'uploadInput1', 'cameraIcon1');
</script>

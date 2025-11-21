@extends('frontend.layout.layout')
@section('content')
    {{-- CSS cho nút active và ẩn/hiện --}}
    <style>
        .hidden { display: none; }
        .rigister-content-tab button.active {
            background-color: #000000;
            color: #fff;
        }
        .rigister-content-tab button {
            background-color: #f5f5f5;
            color: #333;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            transition: all .18s ease;
            border-radius: 6px;
            margin-right: 8px;
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

    </style>



    <div class="app-container app-center">
        <div class="rigister-content">
            <div class="el-row" style="margin-left: -10px; margin-right: -10px;">
                <!-- Left Banner -->
                <div class="el-col el-col-24 el-col-xs-0 el-col-sm-12 el-col-md-12 el-col-lg-12 el-col-xl-12" style="padding-left: 10px; padding-right: 10px;">
                    <div class="rigister-content-left flex-center">
                        <img src="{{ asset('filemanager/userfiles/loginBg.2e4a973c.png') }}" alt="login" class="rigister-content-banner">
                    </div>
                </div>

                <!-- Right Content -->
                <div class="el-col el-col-24 el-col-xs-24 el-col-sm-12 el-col-md-12 el-col-lg-12 el-col-xl-12" style="padding-left: 10px; padding-right: 10px;">
                    <div class="rigister-content-right">
                        <h1>{{__('lang.register')}}</h1>

                        <!-- Tabs -->
                        <div class="rigister-content-tab">
                            <button type="button" id="btnEmail" class="el-button active">Email</button>
                            <button type="button" id="btnMobile" class="el-button">{{__('lang.mobile_number')}}</button>
                        </div>

                        <!-- Register Form - Email -->
                        <div id="formEmail" class="rigister-content-form">
                            <form id="register-form-email" method="POST" action="{{ route('user.register') }}" class="el-form el-form--label-top">
                                <!-- Email or Phone -->
                                <div class="el-form-item is-required">
                                    <label for="emailDangky" class="el-form-item__label">Email</label>
                                    <div class="el-form-item__content">
                                        <div class="ipt_box">
                                            <div class="el-input email_ipt">
                                                <input type="text" id="emailDangky" name="emailDangky" placeholder="{{__('lang.please_enter_your_mobile_number')}}" value="{{ old('emailDangky') }}" class="el-input__inner" maxlength="64" required>
                                            </div>
                                        </div>
                                        @if($errors->has('emailDangky'))
                                            <div class="text-red-500 text-sm mt-1"> {{$errors->first('emailDangky') }}</div>
                                        @endif
                                    </div>
                                </div> <!-- Password -->
                                <div class="el-form-item is-required">
                                    <label for="password" class="el-form-item__label">{{__('lang.password')}}</label>
                                    <div class="el-form-item__content">
                                        <div class="el-input el-input--suffix">
                                            <input type="password" id="password" name="password" placeholder="{{__('lang.please_enter_your_password')}}" class="el-input__inner" required> <!-- Icon toggle -->
                                            <span class="el-input__suffix" id="togglePassword">
                                                <span class="el-input__suffix-inner">
                                                    <img src="{{ asset('filemanager/userfiles/icons8-closed-eye-50.png') }}" alt="toggle password visibility" class="password-icon">
                                                </span>
                                            </span>
                                        </div>
                                        @if($errors->has('password'))
                                            <div class="text-red-500 text-sm mt-1"> {{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
                                </div> <!-- Confirm Password -->
                                <div class="el-form-item is-required">
                                    <label for="password_confirmation" class="el-form-item__label">{{__('lang.confirm_password')}}</label>
                                    <div class="el-form-item__content">
                                        <div class="el-input el-input--suffix">
                                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{__('lang.confirm_password')}}" class="el-input__inner" required>
                                            <span class="el-input__suffix" id="toggleConfirmPassword">
                                                <span class="el-input__suffix-inner">
                                                    <img src="{{ asset('filemanager/userfiles/icons8-closed-eye-50.png') }}" alt="toggle confirm password visibility" class="password-icon">
                                                </span>
                                            </span>
                                        </div>
                                        @if($errors->has('password_confirmation'))
                                            <div class="text-red-500 text-sm mt-1"> {{ $errors->first('password_confirmation') }} </div>
                                        @endif
                                    </div>
                                </div> <!-- Login Link & Register Button -->
                                <div class="el-form-item">
                                    <div class="el-form-item__content">
                                        <p class="tips"> {{__('lang.already_have_account')}}? <a href="{{route('user.login')}}" class="text-blue-500">{{__('lang.log_in')}}</a> </p>
                                        <button type="submit" class="el-button sing-in-btn el-button--primary"> <span>{{__('lang.register')}}</span> </button>
                                    </div>
                                </div>
                            </form>
                        </div>

{{--                        <!-- Register Form - Mobile -->--}}
                        <div id="formMobile" class="rigister-content-form hidden">
                            <form id="register-form-mobile" method="POST" action="{{route('user.register.mobile')}}" class="el-form el-form--label-top">
                                <div class="el-form-item is-required">
                                    <label for="country_select" class="el-form-item__label">{{__('lang.country_code')}}</label>
                                    <div class="el-form-item__content">
                                        <div class="form-phone">
                                            {{-- Select hiển thị mã vùng --}}
                                            <select id="country_select" name="mobile_country_code" aria-label="Country code">
                                                <option value="">{{__('lang.loading')}}...</option>
                                            </select>

                                            {{-- Input phone VISIBLE để intl-tel-input attach --}}
                                            <input type="tel"
                                                   id="mobile"
                                                   name="mobile_display"
                                                   placeholder="{{__('lang.please_enter_your_mobile_number')}}"
                                                   maxlength="30"
                                                   class="el-input__inner"
                                                   required
                                                   style="flex:1;" />

{{--                                             Hidden: gửi E.164 lên server --}}
                                            <input type="hidden" name="mobile_e164" id="mobile_e164" value="">
                                        </div>

                                        @if($errors->has('mobile'))
                                            <div class="text-red-500 text-sm mt-1">{{ $errors->first('mobile') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="el-form-item is-required">
                                    <label for="mobile_password" class="el-form-item__label">{{__('lang.password')}}</label>
                                    <div class="el-form-item__content">
                                        <div class="el-input el-input--suffix">
                                            <input type="password" id="mobile_password" name="password" autocomplete="off" placeholder="{{__('lang.please_enter_your_password')}}" class="el-input__inner" required>
                                            <span class="el-input__suffix" id="toggleMobilePassword">
                                            <span class="el-input__suffix-inner">
                                                <img src="{{asset('filemanager/userfiles/icons8-closed-eye-50.png')}}" alt="toggle password visibility" class="password-icon" style="width:20px;height:20px;cursor:pointer;">
                                            </span>
                                        </span>
                                        </div>
                                        @if($errors->has('password'))
                                            <div class="text-red-500 text-sm mt-1">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="el-form-item is-required">
                                    <label for="mobile_password_confirmation" class="el-form-item__label">{{__('lang.confirm_password')}}</label>
                                    <div class="el-form-item__content">
                                        <div class="el-input el-input--suffix">
                                            <input type="password" id="mobile_password_confirmation" name="password_confirmation" placeholder="{{__('lang.confirm_password')}}" class="el-input__inner" required>
                                            <span class="el-input__suffix" id="toggleMobileConfirmPassword">
                                            <span class="el-input__suffix-inner">
                                                <img src="{{ asset('filemanager/userfiles/icons8-closed-eye-50.png') }}" alt="toggle confirm password visibility" class="password-icon" style="width:20px;height:20px;cursor:pointer;">
                                            </span>
                                        </span>
                                        </div>
                                        @if($errors->has('password_confirmation'))
                                            <div class="text-red-500 text-sm mt-1"> {{ $errors->first('password_confirmation') }} </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="el-form-item">
                                    <div class="el-form-item__content">
                                        <p class="tips">{{__('lang.already_have_account')}} <a href="{{ route('user.login') }}" class="text-blue-500">{{__('lang.log_in')}}</a></p>
                                        <button type="submit" class="el-button sing-in-btn el-button--primary"><span>{{__('lang.register')}}</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Register Form -->
                    </div>
                </div>
            </div>
        </div>

        <div class="icon-tips">
            <div class="icon-tips-bottom flex-between">
                <div class="icon-tips-bottom-item flex-center">
                    {{--                    <span class="iconfont icon-aixin icon_span"></span>--}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M442.9 144C415.6 144 389.9 157.1 373.9 179.2L339.5 226.8C335 233 327.8 236.7 320.1 236.7C312.4 236.7 305.2 233 300.7 226.8L266.3 179.2C250.3 157.1 224.6 144 197.3 144C150.3 144 112.2 182.1 112.2 229.1C112.2 279 144.2 327.5 180.3 371.4C221.4 421.4 271.7 465.4 306.2 491.7C309.4 494.1 314.1 495.9 320.2 495.9C326.3 495.9 331 494.1 334.2 491.7C368.7 465.4 419 421.3 460.1 371.4C496.3 327.5 528.2 279 528.2 229.1C528.2 182.1 490.1 144 443.1 144zM335 151.1C360 116.5 400.2 96 442.9 96C516.4 96 576 155.6 576 229.1C576 297.7 533.1 358 496.9 401.9C452.8 455.5 399.6 502 363.1 529.8C350.8 539.2 335.6 543.9 320 543.9C304.4 543.9 289.2 539.2 276.9 529.8C240.4 502 187.2 455.5 143.1 402C106.9 358.1 64 297.7 64 229.1C64 155.6 123.6 96 197.1 96C239.8 96 280 116.5 305 151.1L320 171.8L335 151.1z"/></svg>
                    <span>100% {{__('lang.original')}}</span>
                </div>
                <div class="icon-tips-bottom-item flex-center">
                    {{--                    <span class="iconfont icon-control-backward icon_span"></span>--}}
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="11,4 11,20 4,12" stroke="black" stroke-width="2" fill="none"/>
                        <polygon points="19,4 19,20 12,12" stroke="black" stroke-width="2" fill="none"/>
                    </svg>

                    <span>7 {{__('lang.day_return')}}</span>
                </div>
                <div class="icon-tips-bottom-item flex-center">
                    {{--                    <span class="iconfont icon-yunfei icon_span"></span>--}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M64 160C64 124.7 92.7 96 128 96L416 96C451.3 96 480 124.7 480 160L480 192L530.7 192C547.7 192 564 198.7 576 210.7L621.3 256C633.3 268 640 284.3 640 301.3L640 448C640 483.3 611.3 512 576 512L572.7 512C562.3 548.9 528.3 576 488 576C447.7 576 413.8 548.9 403.3 512L300.7 512C290.3 548.9 256.3 576 216 576C175.7 576 141.8 548.9 131.3 512L128 512C92.7 512 64 483.3 64 448L64 400L24 400C10.7 400 0 389.3 0 376C0 362.7 10.7 352 24 352L136 352C149.3 352 160 341.3 160 328C160 314.7 149.3 304 136 304L24 304C10.7 304 0 293.3 0 280C0 266.7 10.7 256 24 256L200 256C213.3 256 224 245.3 224 232C224 218.7 213.3 208 200 208L24 208C10.7 208 0 197.3 0 184C0 170.7 10.7 160 24 160L64 160zM576 352L576 301.3L530.7 256L480 256L480 352L576 352zM256 488C256 465.9 238.1 448 216 448C193.9 448 176 465.9 176 488C176 510.1 193.9 528 216 528C238.1 528 256 510.1 256 488zM488 528C510.1 528 528 510.1 528 488C528 465.9 510.1 448 488 448C465.9 448 448 465.9 448 488C448 510.1 465.9 528 488 528z"/></svg>
                    <span>{{__('lang.freight_discount')}}</span>
                </div>
                <div class="icon-tips-bottom-item flex-center">
                    {{--                    <span class="iconfont icon-qianbao icon_span"></span>--}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M128 96C92.7 96 64 124.7 64 160L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 256C576 220.7 547.3 192 512 192L136 192C122.7 192 112 181.3 112 168C112 154.7 122.7 144 136 144L520 144C533.3 144 544 133.3 544 120C544 106.7 533.3 96 520 96L128 96zM480 320C497.7 320 512 334.3 512 352C512 369.7 497.7 384 480 384C462.3 384 448 369.7 448 352C448 334.3 462.3 320 480 320z"/></svg>
                    <span>{{__('lang.secure_payment')}}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- JS: toggle forms, password toggle, init intl-tel-input + populate select + submit xử lý E.164 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ----------------------------
            // 1) Chuyển tab Email <-> Mobile
            // ----------------------------
            const btnEmail = document.getElementById('btnEmail');
            const btnMobile = document.getElementById('btnMobile');
            const formEmail = document.getElementById('formEmail');
            const formMobile = document.getElementById('formMobile');

            if (btnEmail && btnMobile && formEmail && formMobile) {
                btnEmail.addEventListener('click', () => {
                    formEmail.classList.remove('hidden');
                    formMobile.classList.add('hidden');
                    btnEmail.classList.add('active');
                    btnMobile.classList.remove('active');
                });

                btnMobile.addEventListener('click', () => {
                    formMobile.classList.remove('hidden');
                    formEmail.classList.add('hidden');
                    btnMobile.classList.add('active');
                    btnEmail.classList.remove('active');
                    const m = document.getElementById('mobile');
                    if (m) m.focus();
                });
            }
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

            // ----------------------------
            // 3) Debug helper: log lỗi console nếu có (không bắt buộc, để phát triển)
            // ----------------------------
            window.addEventListener('error', function (e) {
                console.error('Global JS error caught:', e.error || e.message || e);
            });

        });
    </script>
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
@endsection
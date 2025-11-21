<style>
    .custom-alert {
        position: fixed;      /* Cố định trên màn hình */
        top: 20px;            /* Cách đỉnh 20px */
        left: 50%;            /* Căn giữa ngang */
        transform: translateX(-50%);
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 16px;
        color: #fff;
        z-index: 9999;
        opacity: 0;
        animation: fadeInOut 2s forwards; /* 2 giây hiển thị rồi biến mất */
    }

    .custom-alert.success {
        background-color: #28a745; /* xanh lá */
    }

    .custom-alert.error {
        background-color: #dc3545; /* đỏ */
    }

    @keyframes fadeInOut {
        0% { opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { opacity: 0; }
    }
    /* Menu ẩn mặc định */
    .custom-options {
        display: none;
        position: absolute;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 5px 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        min-width: 120px;
        z-index: 999;
    }

    /* Option hover */
    .option-item {
        padding: 5px 15px;
        cursor: pointer; /* Con trỏ thành tay khi hover */
    }

    /* Hover menu option */
    .option-item:hover {
        background-color: #f2f2f2;
    }

    /* Icon khi hover hoặc click */
    .el-popover__reference {
        cursor: pointer; /* Con trỏ thành tay khi hover icon */
    }


</style>
@if(session('success'))
    <div class="custom-alert success">
        <p>{{__('lang.add_address_success')}}</p>
    </div>
@endif

@if(session('error'))
    <div class="custom-alert error">
        {{ session('error') }}
    </div>
@endif

<div data-v-2c463d0c="" class="set-container">
    <div data-v-4a0598a6="" data-v-2c463d0c="" class="setup setup-index" id="setup">
        <div data-v-a8e650fc="" data-v-4a0598a6="" class="setup-index">
            <div data-v-a8e650fc="" class="page-title">{{__('lang.setup')}}</div>
            <div data-v-a8e650fc="" class="setup-list">
                <a data-v-a8e650fc="" href="#" class="item" id="login-pa-link"> {{__('lang.transaction_password')}} </a>
                <a data-v-a8e650fc="" href="#" id="transaction-link" class="item"> {{__('lang.password')}} </a>
                <a data-v-a8e650fc="" href="#" id="address-link" class="item"> {{__('lang.recipient_address')}} </a>
                <a data-v-a8e650fc="" href="#" id="account-link" class="item"> {{__('lang.account_cancellation')}}</a>

            </div>
        </div>
    </div>
</div>
{{--Đổi mật khẩu--}}
<div data-v-4a0598a6="" data-v-2c463d0c="" class="setup transaction-password"   style="display: none;">
    <div data-v-32ecff3c="" data-v-4a0598a6=""  class="transaction-password">
        <div data-v-32ecff3c="" class="page-title" id="back-transaction" style="cursor: pointer;">
            <i data-v-32ecff3c="" class="el-icon-arrow-left"></i>
            {{__('lang.password')}}
        </div>

        <form data-v-32ecff3c="" class="el-form form" action="{{route('changePassword')}}" method="post"><!---->
            <div data-v-32ecff3c="" class="el-form-item is-required">
                <label for="newPassword" class="el-form-item__label">
                    {{__('lang.current_password')}}
                </label>
                <div class="el-form-item__content">
                    <div data-v-32ecff3c="" class="el-input el-input--suffix"><!---->
{{--                        <input type="password" autocomplete="off" maxlength="6" placeholder="Please enter the new password" class="el-input__inner"><!---->--}}
                        <input type="password" id="old_pass" name="old_pass" placeholder="{{__('lang.current_password')}}" class="el-input__inner" required>

                        <span class="el-input__suffix"><span class="el-input__suffix-inner">
                                <span class="el-input__suffix-inner">
                                    <img src="{{asset('filemanager/userfiles/icons8-closed-eye-50.png')}}" alt="toggle password visibility" class="password-icon" style="width:20px;height:20px;cursor:pointer;">
                                </span>
                            </span><!---->
                        </span><!----><!---->
                    </div><!---->
                </div>
            </div>
            <div data-v-32ecff3c="" class="el-form-item is-required">
                <label for="confirmPassword" class="el-form-item__label">
                    {{__('lang.new_password')}}
                </label>
                <div class="el-form-item__content">
                    <div data-v-32ecff3c="" class="el-input el-input--suffix"><!---->

                        <input type="password" id="new_pass" name="new_pass" autocomplete="off" placeholder="{{__('lang.new_password')}}" class="el-input__inner" required>

                        <span class="el-input__suffix">
                           <span class="el-input__suffix-inner">
                               <img src="{{asset('filemanager/userfiles/icons8-closed-eye-50.png')}}" alt="toggle password visibility" class="password-icon" style="width:20px;height:20px;cursor:pointer;">
                           </span>
                        </span><!----><!---->
                    </div><!---->
                </div>
                <div data-v-32ecff3c="" class="el-form-item is-required">
                    <label for="newPassword" class="el-form-item__label">
                        {{__('lang.confirm_password')}}
                    </label>
                    <div class="el-form-item__content">
                        <div data-v-32ecff3c="" class="el-input el-input--suffix"><!---->
                            <input type="password" id="confirm_pass" name="confirm_pass" placeholder="{{__('lang.confirm_password')}}" class="el-input__inner" required>

                            <span class="el-input__suffix">
                            <span class="el-input__suffix-inner">
                                <span class="el-input__suffix-inner">
                                    <img src="{{asset('filemanager/userfiles/icons8-closed-eye-50.png')}}" alt="toggle password visibility" class="password-icon" style="width:20px;height:20px;cursor:pointer;">
                                </span>
                            </span><!---->
                        </span><!----><!---->
                        </div><!---->
                    </div>
                </div>

            </div>
            <div data-v-32ecff3c="" class="el-form-item"><!---->
                <div class="el-form-item__content">
                    <button data-v-32ecff3c="" type="submit" class="el-button form-submit-btn el-button--submit" block=""><!----><!---->
                        <span> {{__('lang.confirm')}}
                        </span>
                    </button><!---->
                </div>
            </div>
        </form>
    </div>
</div>
{{--Địa chỉ--}}
<div data-v-4a0598a6="" data-v-2c463d0c="" class="setup address" style="display: none;">
    <div data-v-03db3c57="" data-v-4a0598a6="" class="shipping-address">
        <div data-v-03db3c57="" class="page-header">
            <div data-v-03db3c57="" class="page-title" style="cursor: pointer;">
                <i data-v-03db3c57="" class="el-icon-arrow-left"></i> {{__('lang.recipient_address')}} </div>
            <div class="el-button el-button--primary el-button--small" id="open-address-popup2"><!---->
                <i class="el-icon-plus"></i>
                <span>{{__('lang.add')}}</span>
            </div>
        </div>
        <div data-v-03db3c57="" class="page-content">
            <div data-v-03db3c57="" class="address_list">
                @foreach($addresses as $address)
                    <div data-v-03db3c57="" class="item"
                         data-id="{{ $address->id }}"
                         data-country-code="{{ $address->quoc_gia_id }}"
                         data-province-code="{{ $address->thanh_pho_id }}"
                         data-district-code="{{ $address->quan_huyen_id }}"
                         data-postal-code="{{ $address->ma_buu_dien }}"
                         data-address="{{ $address->vi_tri_cu_the }}"
                         data-default="{{ $address->mac_dinh }}"
                         data-email="{{ $address->email }}"
                         data-phone="{{ $address->sdt }}">
                    <div data-v-03db3c57="" class="info">
                        <div data-v-03db3c57="" class="name-and-mobile">
                            <span data-v-03db3c57="" class="name">{{ $address->ten_nguoi_nhan}}</span>
                            <span data-v-03db3c57="" class="mobile">&nbsp;&nbsp;&nbsp;+{{$address->ma_quoc_gia}} {{$address->sdt}}</span>
                        </div><br>

                    </div>
                    <div data-v-03db3c57="" class="address">
                        {{ $address->country_name ?? ''}},
                        {{ $address->province_name ?? ''}},
                        {{ $address->district_name ?? ''}},
                        {{ $address->dia_chi_cu_the ?? ''}}
                    </div>
                    <span data-v-03db3c57="">
                            <span class="el-popover__reference-wrapper">
                                <i class="el-icon-more el-popover__reference" tabindex="0"></i>

                                <!-- Menu option ẩn -->
                                <div class="custom-options" style="display:none; position:absolute; background:#fff; border:1px solid #ccc; border-radius:6px; padding:5px 0; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
                                    <div class="option-item" data-action="edit" style="padding:5px 15px; cursor:pointer;">{{__('lang.edit')}}</div>
                                    <div class="option-item" data-action="delete" data-id="{{ $address->id }}"  style="padding:5px 15px; cursor:pointer;">{{__('lang.delete')}}</div>
                                    <div class="option-item" data-action="default" data-id="{{ $address->id }}" data-user-id="{{ Auth::id() }}" style="padding:5px 15px; cursor:pointer;">
                                        {{ __('lang.set_as_default') }}
                                    </div>
                                </div>
                            </span>
                        </span>
                </div>
                @endforeach
            </div><!---->
            <div class="el-loading-mask" style="display: none;">
                <div class="el-loading-spinner"><svg viewBox="25 25 50 50" class="circular">
                        <circle cx="50" cy="50" r="20" fill="none" class="path"></circle>
                    </svg><!---->
                </div>
            </div>
        </div>
    </div>
</div>

<div data-v-4a0598a6="" data-v-2c463d0c="" class="setup account-cancel" style="display:none;">
    <div data-v-69558f8a="" data-v-4a0598a6="" class="transaction-password">
        <div data-v-69558f8a="" class="page-title" style="cursor: pointer;">
            <i data-v-69558f8a="" class="el-icon-arrow-left">

            </i> {{__('lang.account_cancellation')}} </div>
            <div data-v-69558f8a="" class="log-container">
            <div data-v-69558f8a="">
                <div data-v-69558f8a="" class="tit-log">{{__('lang.your_account')}}</div>
                <div data-v-69558f8a="" class="el-input is-disabled"><!---->
                    <input type="text" disabled="disabled" value="{{$user->email ?? $user->tel}}" autocomplete="off" class="el-input__inner"><!----><!----><!----><!---->
                </div>
            </div>
            <div data-v-69558f8a="">
                <div data-v-69558f8a="" class="tit-log">{{__('lang.why_do_you_want_to_leave')}}?</div>
                <div data-v-69558f8a="" class="content el-textarea">
                    <textarea autocomplete="off"
                              placeholder="{{__('lang.please_enter')}}" maxlength="1000" class="el-textarea__inner"
                              style="resize: none; min-height: 32.6px;"></textarea><!---->
                </div>
            </div>
            <div data-v-69558f8a="">
                <button data-v-69558f8a="" type="button" onclick="deleteUser({{$user->id}})"
                        class="el-button el-button--primary el-button--medium"
                        style="width: 412px; height: 44px; margin-top: 22px;"><!----><!---->
                    <span> {{__('lang.confirm')}}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.option-item[data-action="delete"]').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirm('Bạn có chắc muốn xóa địa chỉ này?')) return;

            const addressId = this.dataset.id;

            fetch(`/address/${addressId}`, {
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
                        // Xóa phần tử address khỏi DOM
                        this.closest('.item').remove();
                    }
                });
        });
    });
</script>
<script>
    document.querySelectorAll('.option-item[data-action="edit"]').forEach(button => {
        button.addEventListener('click', function() {
            const item = this.closest('.item');
            const popup = document.getElementById('address-popup');
            const form = popup.querySelector('form');

            // Lấy dữ liệu hiện có
            const recipientName = item.querySelector('.name').textContent.trim();
            const mobile = item.querySelector('.mobile').textContent.trim().replace(/^\+\d+\s/, '');
            const countryCode = item.dataset.countryCode || '';
            const provinceCode = item.dataset.provinceCode || '';
            const districtCode = item.dataset.districtCode || '';
            const postalCode = item.dataset.postalCode || '';
            const address = item.dataset.address || '';
            const isDefault = item.dataset.default == 1;

            // Điền dữ liệu vào form
            form.recipient_name.value = recipientName;
            form.phone.value = mobile;
            form.country_code.value = countryCode;
            form.province_code.value = provinceCode;
            form.district_code.value = districtCode;
            form.postal_code.value = postalCode;
            form.address.value = address;
            form.is_default.checked = isDefault;

            // Thay đổi action form sang update
            form.action = `/address/${item.dataset.id}`;
            form.method = 'POST';

            // Thêm @method('PUT') cho Laravel
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            } else {
                methodInput.value = 'PUT';
            }

            // Mở popup
            popup.style.display = 'block';
        });
    });

</script>
<script>
    $(document).ready(function () {
        // Khi click vào menu option "set as default"
        $(document).on('click', '.option-item[data-action="default"]', function () {
            const addressId = $(this).data('id');
            const userId = $(this).data('user-id');

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
                        // Reset tất cả switch checkbox về unchecked
                        $('.default-address-switch').prop('checked', false);

                        // Nếu muốn đánh dấu checkbox của địa chỉ hiện tại
                        $(`.default-address-switch[data-id="${addressId}"]`).prop('checked', true);

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

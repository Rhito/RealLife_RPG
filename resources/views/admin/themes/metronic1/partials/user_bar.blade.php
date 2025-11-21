<style>
    .item-header {
        position: relative;
    }
    /*.item-header:hover .item-header-popup {
        display: block;
    }*/
    /* Bọc cả avatar và select chung hàng */
    .kt-header__topbar-wrapper,
    .da_ngon_ngu {
        display: flex;
        align-items: center;
    }

    /* Ảnh avatar */
    .kt-header__topbar-user img {
        width: 40px;   /* chỉnh cho vừa mắt */
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    /* Select ngôn ngữ */
    .da_ngon_ngu select {
        height: 40px;         /* cho bằng chiều cao avatar */
        padding: 0 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 14px;
    }
    /* Icon thông báo */
    .kt-header__topbar-notify {
        width: 40px;   /* bằng với avatar */
        height: 40px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px; /* cách avatar một chút */
        cursor: pointer;
        position: relative;
    }

    /* Icon bell */
    .kt-header__topbar-notify i {
        font-size: 18px;
        color: #555;
    }


    /* Khi màn hình nhỏ (điện thoại) thì xếp cạnh nhau */
    @media (max-width: 768px) {
        .kt-header__topbar-wrapper,
        .da_ngon_ngu {
            display: inline-flex;
            vertical-align: middle;
        }

        .da_ngon_ngu {
            margin-left: 10px; /* cách avatar một chút */
        }
    }

    .kt-notification-list {
        max-width: 300px;
        margin-left: auto;
        margin-right: auto;
    }
    .wallet-info {
        display: flex;
        justify-content: space-between; /* giãn cách đều */
        gap: 20px; /* khoảng cách giữa các mục */
        margin-right: 20px;
    }

    .wallet-info p {
        margin: 0;
    }

</style>

{!! Eventy::filter('block.header_topbar', '') !!}

@php
    $user = \Auth::guard('admin')->user();
    $notifications = \App\CRMDV\Models\Notification::where('seller_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    $vi_tien = \App\Models\Admin::where('id', $user->id)->first() ?? 0;
@endphp
<div class="kt-header__topbar d-flex align-items-center">

    <div class="wallet-info">
        @if($vi_tien)
            <p><strong>{{ __('lang.vi_tien') }}</strong> {{ number_format($vi_tien->vi_tien, 0, ',', '.') }} đ</p>
            <p><strong>{{ __('lang.thieu_bao_nhieu') }}</strong> {{ number_format($vi_tien->thieu_bao_nhieu, 0, ',', '.') }} đ</p>
            <p><strong>{{ __('lang.so_tien_co_the_thieu') }}</strong> {{ number_format($vi_tien->so_tien_co_the_thieu, 0, ',', '.') }} đ</p>
        @endif
    </div>
    {{-- Thông báo --}}
    <div class="kt-header__topbar-item kt-header__topbar-item--notify kt-hidden-mobile mr-3"
         id="kt_header_notif_toggle"
         data-toggle="dropdown"
         data-offset="10px,0px">

        <div class="kt-header__topbar-notify position-relative">
            <i class="fas fa-bell"></i>
            <span id="notification-badge"
                  class="kt-badge kt-badge--sm kt-badge--brand kt-badge--notify"
                  style="position: absolute; top: 2px; right: 2px; display: none;">
            </span>
        </div>

        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl"
             id="notification-dropdown"
             style="min-width: 200px; max-height: 350px; overflow-y: auto;">



            @if($notifications->isNotEmpty())
                <div class="kt-notification">
                    @foreach($notifications as $noti)
                        <a href="//{{ ltrim($noti->link, '//') }}"
                           target="_blank"
                           class="kt-notification__item custom-notification"
                           onclick="window.open(this.href, this.target); return false;">
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title"
                                     style="font-size: 14px; color: black;">
                                    {{ $noti->title }}
                                </div>
                                <div class="kt-notification__item-content"
                                     style="font-size: 12px; color: #333333; margin-top: 3px;">
                                    {{ $noti->content }}
                                </div>
                                <div class="kt-notification__item-time custom-time"
                                     style="font-size: 12px; margin-top: 5px;">
                                    {{ \Carbon\Carbon::parse($noti->created_at)->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-3 text-muted">
                    Không có thông báo nào
                </div>
            @endif
        </div>
    </div>


    {{-- Avatar + menu user --}}
    <div class="kt-header__topbar-item kt-header__topbar-item--user item-header mr-3">
        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
            <div class="kt-header__topbar-user">
                @if(@\Auth::guard('admin')->user()->image != null)
                    <img alt="{{ @\Auth::guard('admin')->user()->name }}"
                         class="lazy"
                         data-src="{{ \App\Http\Helpers\CommonHelper::getUrlImageThumb(@\Auth::guard('admin')->user()->image,100,100) }}"/>
                @else
                    <span class="kt-header__topbar-welcome kt-hidden-mobile">{{trans('admin.hello')}}</span>
                    <span class="kt-header__topbar-username kt-hidden-mobile"
                          style="color: #636177">{{ @\Auth::guard('admin')->user()->name }}</span>
                @endif
            </div>
        </div>

        {{-- dropdown user --}}
        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl item-header-popup"
             style="position: absolute; top: 53px; right: 0; left: unset; will-change: transform;">
            <!--begin: Head -->
            <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
                 style="background-image: url({{ asset('/backend/themes/metronic1/media/misc/bg-1.jpg') }})">
                <div class="kt-user-card__avatar">
                    @if(@\Auth::guard('admin')->user()->image != null)
                        <img alt="Pic" class="lazy"
                             data-src="{{ \App\Http\Helpers\CommonHelper::getUrlImageThumb(@\Auth::guard('admin')->user()->image,100,100) }}"/>
                    @else
                        <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">
                            {{ mb_substr(@\Auth::guard('admin')->user()->name, 0, 1) }}
                        </span>
                    @endif
                </div>
                <div class="kt-user-card__name">
                    {{ @\Auth::guard('admin')->user()->name }}
                </div>
            </div>
            <!--end: Head -->

            <!--begin: Navigation -->
            <div class="kt-notification">
                <a href="/admin/profile" class="kt-notification__item" style="margin: 10px">
                    <div class="kt-notification__item-icon">
                        <i class="flaticon2-calendar-3 kt-font-success"></i>
                    </div>
                    <div class="kt-notification__item-details">
                        <div class="kt-notification__item-title kt-font-bold">
                            {{trans('admin.my_info')}}
                        </div>
                        <div class="kt-notification__item-time">
                            {{trans('admin.setting_account')}}
                        </div>
                    </div>
                </a>

                {!! Eventy::filter('user_bar.profile_after', '') !!}

                <div class="theme-color">
                    <label style="padding: 0 1.75rem;margin: 0;">Màu giao diện</label>
                    <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                        <?php
                        $admin_theme_style = Cookie::get('admin_theme_style', 'dark');
                        ?>
                        <li class="kt-nav__item {{ $admin_theme_style == 'dark' ? 'kt-nav__item--active' : '' }}">
                            <a href="/admin/theme/change?style=dark" class="kt-nav__link">
                                <span class="kt-nav__link-text">{{trans('admin.dark')}}</span>
                            </a>
                        </li>
                        <li class="kt-nav__item {{ $admin_theme_style == 'light' ? 'kt-nav__item--active' : '' }}">
                            <a href="/admin/theme/change?style=light" class="kt-nav__link">
                                <span class="kt-nav__link-text">{{trans('admin.light')}}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <?php
                $btn_setting = in_array('setting', $permissions) ? '<a href="/admin/setting"
                       class="btn btn-clean btn-sm btn-bold">'.trans('admin.settings').'</a>' : '';
                ?>
                {!! Eventy::filter('user_bar.footer', '<div class="kt-notification__custom kt-space-between">
                    <a href="/admin/logout"
                       class="btn btn-label btn-label-brand btn-sm btn-bold">'.trans('admin.logout').'</a>

                    '.$btn_setting.'
                </div>') !!}
            </div>
            <!--end: Navigation -->
        </div>
    </div>

    {{-- Đổi ngôn ngữ --}}
    <div class="da_ngon_ngu">
        <form action="{{ route('set-locale') }}" method="POST" class="m-0">
            {{csrf_field()}}
            <select name="locale" onchange="this.form.submit()" class="with-flags">
                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>🇺🇸 {{ __('lang.en') }}</option>
                <option value="vi" {{ app()->getLocale() == 'vi' ? 'selected' : '' }}>🇻🇳 {{ __('lang.vi') }}</option>
                <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>🇫🇷 {{ __('lang.fr') }}</option>
                <option value="de" {{ app()->getLocale() == 'de' ? 'selected' : '' }}>🇩🇪 {{ __('lang.de') }}</option>
                <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>🇪🇸 {{ __('lang.es') }}</option>
                <option value="pt" {{ app()->getLocale() == 'pt' ? 'selected' : '' }}>🇵🇹 {{ __('lang.pt') }}</option>
                <option value="it" {{ app()->getLocale() == 'it' ? 'selected' : '' }}>🇮🇹 {{ __('lang.it') }}</option>
                <option value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>🇷🇺 {{ __('lang.ru') }}</option>
                <option value="ja" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>🇯🇵 {{ __('lang.ja') }}</option>
                <option value="ko" {{ app()->getLocale() == 'ko' ? 'selected' : '' }}>🇰🇷 {{ __('lang.ko') }}</option>
                <option value="zh" {{ app()->getLocale() == 'zh' ? 'selected' : '' }}>🇨🇳 {{ __('lang.zh') }}</option>
                <option value="ar" {{ app()->getLocale() == 'ar' ? 'selected' : '' }}>🇸🇦 {{ __('lang.ar') }}</option>
                <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>🇮🇳 {{ __('lang.hi') }}</option>
            </select>
        </form>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const notifDropdown = document.getElementById("notification-dropdown");
        const notifBadge = document.getElementById("notification-badge");

        fetch("{{ route('admin.notifications.ajax') }}")
            .then(res => res.json())
            .then(data => {
                notifDropdown.innerHTML = ""; // clear "đang tải..."

                if (!data.status || data.notifications.length === 0) {
                    notifDropdown.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        Không có thông báo nào
                    </div>`;
                    notifBadge.style.display = "none";
                    return;
                }

                notifBadge.innerText = data.unreadCount;
                notifBadge.style.display = "inline-block";

                let html = `<div class="kt-notification">`;

                data.notifications.forEach(item => {
                    let link = item.link ?? '#';

                    // 1. Áp dụng logic thêm tiền tố // (giúp link trở thành tuyệt đối)
                    if (link !== '#' && !link.startsWith('http://') && !link.startsWith('https://')) {
                        link = '//' + link.replace(/^\/\//, '');
                    }

                    html += `
                    <a href="${link}" target="_blank" class="kt-notification__item"
                       onclick="window.open(this.href, this.target); return false;">
                        <div class="kt-header__topbar-notify position-relative">
                            <i class="fas fa-bell"></i>

                            <span id="notification-badge"
                                  class="kt-badge kt-badge--sm kt-badge--brand kt-badge--notify"
                                  style="position: absolute; top: 2px; right: 2px;">
                                  5 </span>
                            
                        </div>
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title">
                                ${item.title}
                            </div>
                            <div class="kt-notification__item-time">
                                ${item.created_at}
                            </div>
                            <div class="kt-notification__item-desc">
                                ${item.content ?? ''}
                            </div>
                        </div>
                    </a>`;
                });

                html += `</div>`;
                notifDropdown.innerHTML = html;
            })
            .catch(err => {
                notifDropdown.innerHTML = `
                <div class="text-center py-3 text-danger">
                    Lỗi tải thông báo
                </div>`;
                console.error(err);
            });
    });
</script>


{{--đây là thư viện ảnh --}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/6.6.6/css/flag-icons.min.css"/>

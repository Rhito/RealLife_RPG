@extends(config('core.admin_theme').'.template')
@section('main')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon-calendar-with-a-clock-time-tools"></i>
			</span>
                    <h3 class="kt-portlet__head-title">
{{--                        {{$module['label']}}--}}

                        {{__($module['label'])}}

                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="">
                            <input type="text" name="quick_search" value="{{ @$_GET['quick_search'] }}"
                                   class="form-control" title="Chỉ cần enter để thực hiện tìm kiếm"
                                   placeholder="{{__('lang.Tim_kiem_nhanh')}}">
                        </div>
                        <div class="kt-portlet__head-actions">
                            <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle btn-closed-search"
                                    onclick="$('.form-search').slideToggle(100); $('.kt-portlet-search').toggleClass('no-padding');">
                                <i class="la la-search"></i> {{__('lang.Tim_kiem')}}
                            </button>
                            <div class="dropdown dropdown-inline">
                                <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="la la-download"></i> {{__('lang.Hanh_dong')}}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                     style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(114px, 38px, 0px);">
                                    <ul class="kt-nav">
                                        <li class="kt-nav__section kt-nav__section--first">
                                            <span class="kt-nav__section-text">{{__('lang.Chon_hanh_dong')}}</span>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a class="kt-nav__link export-excel"
                                               title="Xuất các bản ghi đang lọc ra file excel"
                                               onclick="$('input[name=export]').click();">
                                                <i class="kt-nav__link-icon la la-file-excel-o"></i>
                                                <span class="kt-nav__link-text">{{__('lang.Xuat_excel')}}</span>
                                            </a>
                                        </li>
                                        @if(in_array($module['code'] . '_delete', $permissions))
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link" onclick="multiDelete();"
                                                   title="Xóa tất cả các dòng đang được tích chọn">
                                                    <i class="kt-nav__link-icon la la-copy"></i>
                                                    <span class="kt-nav__link-text">{{__('lang.Xoa_nhieu')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @if(in_array($module['code'] . '_add', $permissions))
                                <a href="{{ url('/admin/'.$module['code'].'/add/') }}"
                                   class="btn btn-brand btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    {{__('lang.Tao_moi')}}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-portlet__body kt-portlet-search @if(!isset($_GET['search'])) no-padding @endif">
                <!--begin: Search Form -->
                <form class="kt-form kt-form--fit kt-margin-b-20 form-search" id="form-search" method="GET" action=""
                      @if(!isset($_GET['search'])) style="display: none;" @endif>
                    <input name="search" type="hidden" value="true">
                    <input name="limit" type="hidden" value="{{ $limit }}"><input type="hidden" name="quick_search"
                                                                                  value="{{ @$_GET['quick_search'] }}"
                                                                                  id="quick_search_hidden"
                                                                                  class="form-control"
                                                                                  placeholder="Tìm kiếm nhanh">
                    <div class="row">

                        @foreach($filter as $filter_name => $field)
                            <div class="col-sm-6 col-lg-3 kt-margin-b-10-tablet-and-mobile list-filter-item">
                                <label>{{ __(@$field['label']) }}:</label>
                                @include(config('core.admin_theme').'.list.filter.' . $field['type'], ['name' => $filter_name, 'field'  => $field])
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-primary btn-brand--icon" id="kt_search" type="submit">
						<span>
							<i class="la la-search"></i>
							<span>{{__('lang.Loc')}}</span>
						</span>
                            </button>
                            &nbsp;&nbsp;
                            <a class="btn btn-secondary btn-secondary--icon" id="kt_reset" title="{{__('lang.xoa_bo_bo_loc')}}"
                               href="/admin/{{ $module['code'] }}">
						<span>
							<i class="la la-close"></i>
							<span>{{__('lang.Reset')}}</span>
						</span>
                            </a>
                        </div>
                    </div>
                    <input name="export" type="submit" value="export" style="display: none;">
                    @foreach($module['list'] as $k => $field)
                        <input name="sorts[]" value="{{ @$_GET['sorts'][$k] }}"
                               class="sort sort-{{ $field['name'] }}" type="hidden">
                    @endforeach
                </form>
                <!--end: Search Form -->
            </div>
            <div class="kt-separator kt-separator--md kt-separator--dashed" style="margin: 0;"></div>
            <div class="kt-portlet__body kt-portlet__body--fit">
                <!--begin: Datatable -->
                <div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--scroll kt-datatable--loaded"
                     id="scrolling_vertical" style="">
                    <table class="table table-striped">
                        <thead class="kt-datatable__head">
                        <tr class="kt-datatable__row" style="left: 0px;">
                            <th style="display: none;"></th>
                            <th data-field="id"
                                class="kt-datatable__cell--center kt-datatable__cell kt-datatable__cell--check"><span
                                        style="width: 20px;"><label
                                            class="kt-checkbox kt-checkbox--single kt-checkbox--all kt-checkbox--solid"><input
                                                type="checkbox"
                                                class="checkbox-master">&nbsp;<span></span></label></span></th>
                            @if(@$_GET['view'] == 'all')
                                <th data-field="company_id"
                                    class="kt-datatable__cell kt-datatable__cell--sort">
                                    Công ty
                                </th>
                            @endif
                            @php $count_sort = 0; @endphp
                            @foreach($module['list'] as $field)
                                <th data-field="{{ $field['name'] }}"
                                    class="kt-datatable__cell kt-datatable__cell--sort {{ @$_GET['sorts'][$count_sort] != '' ? 'kt-datatable__cell--sorted' : '' }}"
                                    @if(isset($field['sort']))
                                        onclick="sort('{{ $field['name'] }}')"
                                        @endif
                                >
                                    {{ __($field['label']) }}
                                    @if(isset($field['sort']))
                                        @if(@$_GET['sorts'][$count_sort] == $field['name'].'|asc')
                                            <i class="flaticon2-arrow-up"></i>
                                        @else
                                            <i class="flaticon2-arrow-down"></i>
                                        @endif
                                    @endif

                                </th>
                                @php $count_sort++; @endphp
                            @endforeach
                            <th> {{__('lang.Thao_tac')}}</th>
                        </tr>
                        </thead>
                        <tbody class="kt-datatable__body ps ps--active-y" style="max-height: 496px;">
                        @foreach($listItem as $item)
                            <tr data-row="0" class="kt-datatable__row" style="left: 0px;">
                                <td style="display: none;"
                                    class="id id-{{ $item->id }}">{{ $item->id }}</td>
                                <td class="kt-datatable__cell--center kt-datatable__cell kt-datatable__cell--check"
                                    data-field="ID"><span style="width: 20px;"><label
                                                class="kt-checkbox kt-checkbox--single kt-checkbox--solid"><input
                                                    name="id[]"
                                                    type="checkbox" class="ids"
                                                    value="{{ $item->id }}">&nbsp;<span></span></label></span>
                                </td>
                                @if(@$_GET['view'] == 'all')
                                    <td data-field="company_name"
                                        class="kt-datatable__cell item-company_id">
                                        {{ @$item->company->name }}
                                    </td>
                                @endif
                                @foreach($module['list'] as $field)
                                    <td data-field="{{ @$field['name'] }}"
                                        class="kt-datatable__cell item-{{ @$field['name'] }}">
                                        @if($field['type'] == 'custom')
                                            @include($field['td'], ['field' => $field])
                                        @else
                                            @include(config('core.admin_theme').'.list.td.'.$field['type'])
                                        @endif
                                    </td>
                                @endforeach

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                            {{__('lang.Thao_tac')}}
                                        </button>

                                        <!-- Menu xổ xuống -->
                                        <div class="dropdown-menu">
                                            <!-- Gắn data-target vào để mở modal -->
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#kiemTraDonHangModal-{{ $item->id }}">
                                                {{__('lang.kiem_tra_don_hang')}}
                                            </a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#thongTinVanChuyenModal-{{ $item->id }}">
                                                {{__('lang.thong_tin_van_chuyen')}}
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                @if (in_array(CommonHelper::getRoleName(\Auth::guard('admin')->user()->id, 'name'), ['super_admin']))
                                    <td>
                                        <a href="{{route('bill.edit',$item->id)}}">{{__('lang.edit')}}</a>
                                    </td>
                                @endif



                            </tr>



                            <!-- Modal kiểm tra đơn hàng -->
                            <div class="modal fade" id="kiemTraDonHangModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('lang.so_don_dat') }}: {{$item->ma_don_hang}}</h5>
                                            <button type="button" class="close" data-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Tóm tắt đơn hàng -->
                                            <div class="card mb-3">
                                                <div class="card-header">{{ __('lang.tom_tat_theo_thu_tu') }}</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>{{ __('lang.So_dat_hang') }}:</strong> {{$item->ma_don_hang}}</p>
                                                            <p><strong>{{ __('lang.Payment_Methods') }}:</strong> USD
{{--                                                               @include('HBBill.bill.list.td.trang_thai_thanh_toan_text')--}}

                                                            </p>
                                                            <p><strong>{{ __('lang.Trang_thai_mua') }}:</strong> <span class="badge badge-success"> @include('HBBill.bill.list.td.trang_thai_don_hang')</span></p>
                                                            <p><strong>{{ __('lang.so_tien_mua') }}:</strong> ${{ number_format($item->tien_san_pham) }}</p>
                                                            <p><strong>{{ __('lang.Thoi_gian_mua') }}:</strong> {{$item->created_at}}</p>
                                                            <p><strong>{{ __('lang.Loi_nhuan') }}:</strong> ${{ number_format($item->loi_nhuan) }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>{{ __('lang.Thoi_gian_don_hang') }}:</strong>  {{$item->created_at}}</p>
{{--                                                            <p><strong>{{ __('lang.Tinh_trang_thanh_toan') }}:</strong> {{$item->status_text}}</p>--}}
{{--                                                            <p><strong>{{ __('lang.Tinh_trang_hau_can') }}:</strong>  @include('HBBill.bill.list.td.trang_thai_thanh_toan_text')</p>--}}
                                                            <p><strong>{{ __('lang.Tinh_trang_thanh_toan') }}:</strong>  @include('HBBill.bill.list.td.trang_thai_thanh_toan_text')</p>
                                                            <p><strong>{{ __('lang.Tinh_trang_hau_can') }}:</strong> {{$item->status_text}}</p>
                                                            <p><strong>{{ __('lang.san_luong_ban_ra') }}:</strong> ${{ number_format($item->tong_tien) }}</p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>{{ __('lang.Ten') }}:</strong> <span class="item-user_id">{{$item->address->ten_nguoi_nhan ??''}}</span></p>
                                                            <p><strong>{{ __('lang.dia_chi') }}:</strong> <span class="item-user_id">{{$item->address->sdt ?? ''}}</span></p>

{{--                                                            Tìm ra quốc gia và thành phố --}}

                                                            <?php
                                                                $fips = $item->address->quoc_gia_id ??'';
                                                                $quoc_gia = \App\CRMDV\Models\Country::where('fips',$fips)->first();
                                                                $admin1_code = $item->address->thanh_pho_id ?? '';
                                                                $Province = \App\CRMDV\Models\Province::where('admin1_code',$admin1_code)->where('country_code',$fips)->first();
                                                                $admin2_code = $item->address->quan_huyen_id ?? '';
                                                               $districts=\App\CRMDV\Models\District::where('admin1_code',$admin1_code)->where('country_code',$fips)->where('admin2_code',$admin2_code)->first();
                                                                ?>


                                                            <p><strong>{{ __('lang.quoc_gia') }}: {{$quoc_gia->country ?? ''}}</strong> </p>
                                                            <p><strong>{{ __('lang.thanh_pho') }}:{{$Province->name ?? ''}}</strong> </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>{{ __('lang.Your_email_address') }}:</strong> {{$item->address->email ?? ''}}</p>
                                                            <p><strong>{{ __('lang.di_dong') }}:</strong> ({{$item->address->ma_quoc_gia ?? ''}} ) {{$item->address->sdt ??''}}</p>
                                                            <p><strong>{{ __('lang.tinh_va_quan_huyen') }}:</strong> {{$districts->name ??''}}</p>
                                                            <p><strong>{{ __('lang.ma_buu_dien') }}:</strong> {{$item->address->ma_buu_dien ?? ''}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Chi tiết đặt hàng (Thay thế bảng bằng div) -->
                                            <div class="card mb-3">
                                                <div class="card-header">{{ __('lang.chi_tiet_dat_hang') }}</div>
                                                <div class="card-body">
                                                    <!-- Tiêu đề -->
                                                    <div class="row font-weight-bold border-bottom pb-2 mb-2">
                                                        <div class="col-2">{{ __('lang.xem_hinh_anh') }}</div>
                                                        <div class="col-3">{{ __('lang.so_san_pham') }}</div>
                                                        <div class="col-3">{{ __('lang.Ten_san_pham') }}</div>
{{--                                                        <div class="col-2">{{ __('lang.so_chi_ro') }}</div>--}}
                                                        <div class="col-2">{{ __('lang.so_luong') }}</div>
                                                    </div>
                                                    <?php

//                                                        dd($item->bill);

                                                        $product_detail_ids = $item->items ? $item->items->pluck('product_detail_id')->toArray() : [];


                                                        $attributes = \App\Modules\Product\Models\ProductAttributes::whereIn('id', $product_detail_ids)->get();

//                                                        dump($item->id);
//
                                                        ?>

                                                    @foreach($attributes as $attributesItem)



                                                    <div class="row align-items-center border-bottom py-2">
                                                        <div class="col-2">
                                                            <img src="{{ CommonHelper::getUrlImageThumb($attributesItem->product->image) }}" alt="Ảnh sản phẩm" class="img-fluid">
                                                        </div>
                                                        <div class="col-3">{{$attributesItem->product->ma_san_pham ?? ''}}    </div>
                                                        <div class="col-3">{{$attributesItem->product->name ?? ''}}</div>
{{--                                                        <div class="col-2">---</div>--}}
                                                        <div class="col-2">{{$item->so_luong ?? ''}}</div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Số lượng đơn đặt hàng -->
                                            <div class="card">
                                                <div class="card-header">{{ __('lang.so_luong_dat_hang') }}</div>
                                                <div class="card-body">
                                                    <p><strong>{{ __('lang.tong_phu') }}:</strong> ${{ number_format($item->tong_tien) }}</p>
                                                    <p><strong>{{ __('lang.thue') }}:</strong> ${{ number_format($item->thue) }}</p>
                                                    <p><strong>{{ __('lang.shipping') }}:</strong> ${{ number_format($item->van_chuyen) }}</p>
                                                    <p><strong>{{ __('lang.giam_gia') }}:</strong> ${{ number_format($item->giam_gia) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('lang.dong')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <style>
                                .transport-item {
                                    padding: 10px 0;          /* cách trên dưới */
                                    border-bottom: 1px solid #ddd; /* gạch chân */
                                }

                                .transport-item:last-child {
                                    border-bottom: none; /* bỏ gạch cuối cùng */
                                }
                            </style>

                            <!-- Modal thông tin vận chuyển -->
                            <div class="modal fade" id="thongTinVanChuyenModal-{{ $item->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{__('lang.thong_tin_van_chuyen')}}</h5>
                                            <button type="button" class="close" data-dismiss="modal"></button>
                                        </div>
{{--                                        lấy tất cả các đơn hàng của cái kia ra để đổ vào đây  --}}
                                        <?php
                                          $orderTransport =\App\Modules\HBBill\Models\OrderTransport::where('order_id', $item->id)->get();

                                            ?>

                                        <div class="modal-body">
                                            @foreach($orderTransport as $orderTransportItem)
                                                <div class="transport-item">
                                                    <div>{{ $orderTransportItem->created_at->format('d/m/Y H:i') }}</div>
                                                    <div>
                                                        {{ $orderTransportItem->bill->ma_don_hang }} .
                                                        {{ __('lang.' . $orderTransportItem->trang_thai_don_hang) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>



                        @endforeach
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__rail-y" style="top: 0px; height: 496px; right: 0px;">
                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 207px;"></div>
                        </div>
                        </tbody>
                    </table>
                    <div class="kt-datatable__pager kt-datatable--paging-loaded">
                        {!! $listItem->appends(isset($param_url) ? $param_url : '')->links() != '' ? $listItem->appends(isset($param_url) ? $param_url : '')->links() : '<ul class="pagination page-numbers nav-pagination links text-center"></ul>' !!}
                        <div class="kt-datatable__pager-info">
                            <div class="dropdown bootstrap-select kt-datatable__pager-size"
                                 style="width: 60px;">
                                <select class="selectpicker kt-datatable__pager-size select-page-size"
                                        onchange="$('input[name=limit]').val($(this).val());$('#form-search').submit();"
                                        title="Chọn số bản ghi hiển thị" data-width="60px"
                                        data-selected="20" tabindex="-98">
                                    <option value="20" {{ $limit == 20 ? 'selected' : '' }}>20</option>
                                    <option value="30" {{ $limit == 30 ? 'selected' : '' }}>30</option>
                                    <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                            <span class="kt-datatable__pager-detail">Hiển thị {{ (($page - 1) * $limit) + 1 }} - {{ ($page * $limit) < $record_total ? ($page * $limit) : $record_total }} của {{ @number_format($record_total) }}</span>
                        </div>
                    </div>
                </div>
                <!--end: Datatable -->
            </div>
        </div>
    </div>

    <script>
        function maskName(fullname) {
            if (!fullname) return fullname;
            const firstChar = fullname.trim().charAt(0);
            return firstChar + '****';
        }

        document.querySelectorAll('.item-user_id').forEach(el => {
            const original = el.textContent.trim();
            el.textContent = maskName(original);
        });
    </script>
@endsection

@section('custom_head')
    <link type="text/css" rel="stylesheet" charset="UTF-8"
          href="{{ asset(config('core.admin_asset').'/css/list.css') }}">
    {{--    <link type="text/css" rel="stylesheet" charset="UTF-8" href="{{ asset('Modules\WebService\Resources\assets\css\custom.css') }}">--}}
    {{--    <script src="{{asset('Modules\WebService\Resources\assets\js\custom.js')}}"></script>--}}
@endsection
@section('custom_footer')
    <script src="{{ asset(config('core.admin_asset').'/js/pages/crud/metronic-datatable/advanced/vertical.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset(config('core.admin_asset').'/js/list.js') }}"></script>
    @include(config('core.admin_theme').'.partials.js_common')
@endsection
@push('scripts')
    @include(config('core.admin_theme').'.partials.js_common_list')
@endpush

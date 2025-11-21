@extends(config('core.admin_theme').'.template')
@section('main')
    <style>
        body {
            background: #f7f9fb;
            font-family: "Segoe UI", sans-serif;
        }
        .wallet-box {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
            transition: all 0.3s ease;
        }
        .wallet-box:hover {
            transform: translateY(-5px);
        }
        .wallet-icon {
            font-size: 40px;
            margin-bottom: 10px;
            color: #007bff;
        }
        .wallet-icon.orange {
            color: #ff7b00;
        }
        .wallet-value {
            font-size: 28px;
            font-weight: bold;
        }
        .wallet-label {
            color: #666;
            font-size: 15px;
        }
        table {
            background: #fff;
        }
        .status-success {
            color: #28a745;
            font-weight: bold;
        }
        .btn-action {
            border-radius: 8px;
            margin-right: 5px;
        }
    </style>

<div class="mx-4 py-5">

    <!-- 3 box số dư -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="wallet-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet" viewBox="0 0 16 16">
                    <path d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a2 2 0 0 1-1-.268M1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1"/>
                </svg>
                <div class="wallet-value">${{ number_format($so_du_vi) }}</div>
                <div class="wallet-label">{{__('lang.so_du_vi')}}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="wallet-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet" viewBox="0 0 16 16">
                    <path d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a2 2 0 0 1-1-.268M1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1"/>
                </svg>
                <div class="wallet-value">${{ number_format($vi_quyet_toan) }}</div>
                <div class="wallet-label">{{__('lang.so_du_quyet_toan')}}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="wallet-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                    <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                    <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z"/>
                </svg>

                <?php
                $doanh_thu = $so_du_vi +$vi_quyet_toan;
                ?>

                <div class="wallet-value">${{ number_format($doanh_thu) }}</div>
                <div class="wallet-label">{{__('lang.doanh_thu')}}</div>
{{--                <div class="mt-2">--}}
{{--                    <button class="btn btn-primary btn-sm btn-action">Tiền gửi</button>--}}
{{--                    <button class="btn btn-success btn-sm btn-action">Rút</button>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
    <!-- Bảng giao dịch -->
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
{{--            <div class="mb-3">--}}
{{--                <button class="btn btn-outline-primary btn-sm btn-action">Tiền gửi</button>--}}
{{--                <button class="btn btn-outline-success btn-sm btn-action">Rút</button>--}}
{{--                <button class="btn btn-outline-secondary btn-sm btn-action">Chuyển khoản</button>--}}
{{--            </div>--}}
            @if(\Auth::guard('admin')->user()->super_admin == 1)
                <a class="btn btn-primary" href="{{ route('vi_cua_toi.yeu_cau_add') }}">{{__('lang.Tao_moi')}}</a>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light text-center">
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>{{__('lang.So_dat_hang')}}</th>
                        <th> {{__('lang.So_tien_nap')}}</th>
                        <th>  {{__('lang.Loai_tien_su_dung')}}</th>
                        <th> {{__('lang.Tinh_trang_don_hang')}}</th>
                        <th> {{__('lang.Chung_chi_thanh_toan')}}</th>
                        <th> {{__('lang.Thuc_te_nhan_duoc')}}</th>
                        <th>{{__('lang.Dia_chi_bien_lai')}}</th>
                        <th> {{__('lang.Thoi_gian_phe_duyet')}}</th>
                        <th> {{__('lang.Thoi_gian_tao_don')}}</th>
                        <th> {{__('lang.Nhan_xet')}}</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">


                    @foreach($lich_su_giao_dich_hoan as $lich_su_giao_dich_hoan_item)
{{--                    {{ dd($lich_su_giao_dich_hoan_item) }}--}}
                    <tr>
                        <td><input type="checkbox"></td>
                        <td>{{$lich_su_giao_dich_hoan_item->ma_giao_dich}}</td>
                        <td class="text-success fw-bold">$ {{number_format($lich_su_giao_dich_hoan_item->so_tien) ?? '--'}}</td>
                        <td>{{$lich_su_giao_dich_hoan_item->loai_tien_su_dung ?? 'USD'}}</td>

                        @if( $lich_su_giao_dich_hoan_item->tinh_trang_don_hàng )
                            <td><span class="badge bg-success">  {{ (__('lang.' . $lich_su_giao_dich_hoan_item->tinh_trang_don_hàng)) ?? '--' }}</span></td>
                        @else
                            <td><span>  {{ '--' }}</span></td>
                        @endif

                        @if( $lich_su_giao_dich_hoan_item->chung_chi_thanh_toan )
                        <td><img style="width: 50px" src="{{ asset('filemanager/userfiles/'.$lich_su_giao_dich_hoan_item->chung_chi_thanh_toan) }}"></td>
                        @else
                            <td><span>  {{ '--' }}</span></td>
                        @endif

                        <td class="text-success fw-bold">${{ number_format($lich_su_giao_dich_hoan_item->thuc_te_tien_nhan_duoc) }}</td>
                        <td>{{$lich_su_giao_dich_hoan_item->dia_chi_bien_lai ?? '--'}}</td>
                        <td>{{$lich_su_giao_dich_hoan_item->thoi_gian_phe_duyet ?? '--'}}</td>
                        <td>{{$lich_su_giao_dich_hoan_item->created_at ?? '--'}}</td>
                        <td>{{$lich_su_giao_dich_hoan_item->nhan_xet ?? '--'}}</td>
{{--                        <td>-</td>--}}
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            {{ $lich_su_giao_dich_hoan->links() }}

        </div>
    </div>
</div>
@endsection

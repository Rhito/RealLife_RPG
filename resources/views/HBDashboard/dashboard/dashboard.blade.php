@extends(config('core.admin_theme').'.template')
@section('main')
    <script src="{{ url('libs/chartjs/js/Chart.bundle.js') }}"></script>
    <script src="{{ url('libs/chartjs/js/utils.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <?php
    if (\Auth::guard('admin')->user()->super_admin != 1) {
        $whereCompany = '1 = 1';
    } else {
        $whereCompany = '1 = 1';
    }



//
//    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01 00:00:00');
//    $end_date   = isset($_GET['end_date'])   ? $_GET['end_date']   : date('Y-m-d 23:59:00');
//
//    $where              = "created_at >= '".$start_date." 00:00:00' AND created_at <= '".$end_date." 23:59:59'";
//    $whereRegistration  = "registration_date >= '".$start_date." 00:00:00' AND registration_date <= '".$end_date." 23:59:59'";
//    $whereCreated_at    = "created_at >= '".$start_date." 00:00:00' AND created_at <= '".$end_date." 23:59:59'";
//    $whereDate          = "date >= '".$start_date." 00:00:00' AND date <= '".$end_date." 23:59:59'";
//
//    if (isset($_GET['admin_id']) && $_GET['admin_id'] != '') {
//        $where .= " AND admin_id = ".$_GET['admin_id'];
//        $whereRegistration .= " AND admin_id = ".$_GET['admin_id'];
//    }
//
//    $tong_hd          = @\App\Modules\HBDashboard\Models\Bill::whereRaw($whereRegistration)->count();
//    $tong_khach       = @\App\Modules\HBDashboard\Models\Bill::whereRaw($where)->select('id')->get()->count();
//    $doanh_so         = \App\Modules\HBDashboard\Models\Bill::whereRaw($whereRegistration)->sum('total_price');
//    $doanh_thu_du_an  = \App\Modules\HBDashboard\Models\Bill::whereRaw($whereRegistration)->sum('total_received');
//    $phieu_thu        = \App\Modules\HBDashboard\Models\BillReceipts::where('price', '>', 0)->whereRaw($whereDate)->sum('price');
//    $phieu_chi        = \App\Modules\HBDashboard\Models\BillReceipts::where('price', '<', 0)->whereRaw($whereDate)->sum('price');
    ?>
    <style>
        .section-2{
            justify-content: space-evenly;
        }
    </style>

    <div class="container-fluid p-3 dashboard-container">


        <div class="container">
            {{-- 20/09/2025 - DiepTV modified start --}}
            {{-- Bộ lọc --}}
            {{--            @if(in_array(CommonHelper::getRoleName(\Auth::guard('admin')->user()->id, 'name'), ['super_admin']))--}}
            {{--                @include('HBDashboard.dashboard.partials.bo_loc.bo_loc_chung')--}}
            {{--            @endif--}}

            {{-- 20/09/2025 - DiepTV modified end --}}

            {{--            @include('HBDashboard.dashboard.partials.so_lieu.so_lieu_tong_quan')--}}
            {{--            @include('HBDashboard.dashboard.partials.so_lieu.chi_tiet_chi_phi')--}}
            {{--            @include('CoreERP.dashboard.partials.xep_hang.top_sale_3_thang')--}}
            {{--            @include('CoreERP.dashboard.partials.xep_hang.top_marketing_3_thang')--}}
            {{--            @include('CoreERP.dashboard.partials.xep_hang.top_ky_thuat')--}}
            {{--            @include('HBDashboard.dashboard.partials.thong_bao.hd_moi_thuong_sx')--}}
            {{--            @include('CoreERP.dashboard.partials.thong_bao.hd_moi')--}}
            {{--            @include('CoreERP.dashboard.partials.xep_hang.xep_hang_dich_vu')--}}
            {{--            @include('HBDashboard.dashboard.partials.nhac_nho.hd_no_tien')--}}
            {{--            @include('HBDashboard.dashboard.partials.nhac_nho.hd_cham_tien_do')--}}
            {{--            @include('HBDashboard.dashboard.partials.nhac_nho.hd_sap_het_han')--}}
            {{--            @include('HBDashboard.dashboard.partials.bieu_do.hop_dong_doanh_so_khach_hang')--}}
            {{--            @include('HBDashboard.dashboard.partials.bieu_do.doanh_so_theo_tung_loai_dv')--}}
            {{--            @include('HBDashboard.dashboard.partials.bieu_do.gia_han')--}}


        </div>
        <div class="container px-4">
            <div class="card p-3">
                <h5>{{__('lang.dashboard')}}</h5>
                <div class="row g-5 mt-3">
                    <div class="col-12 col-md-3 text-center">
                        <div class="card bg-danger text-white p-3 custom-card">
                            <h3>{{$shop_product}}</h3>
                            <p>{{__('lang.Tong_san_pham')}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 text-center">
                        <div class="card bg-info text-white p-3 custom-card">
                            <h3>{{CommonHelper::convertPrice($tongtienbanhang)}}</h3>
                            <p>{{__("lang.Tong_so_tien_ban_hang")}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 text-center">
                        <div class="card bg-primary text-white p-3 custom-card">
                            <h3>{{CommonHelper::convertPrice($tongtien_choxacnhan)}}</h3>
                            <p>{{__('lang.Awaiting_collection')}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 text-center">
                        <div class="card bg-success text-white p-3 custom-card">
                            <h3>{{CommonHelper::convertPrice($loinhuan)}}</h3>
                            <p>{{__('lang.Tong_loi_nhuan')}}</p>
                        </div>
                    </div>
{{--                    <div class="col-12 col-md-2 text-center">--}}
{{--                        <div class="card bg-warning text-white p-3 custom-card">--}}
{{--                            <h3>$0.00</h3>--}}
{{--                            <p>earning (newlang9)</p>--}}
{{--                            <p class="text-muted small">(newlang10)</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>

        <style>
            .custom-card {
                width: 200px; /* Chiều rộng cố định */
                height: 150px; /* Chiều cao cố định */
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
        </style>
    </div>

    {{--    Section 2--}}
    <div class="container p-4">
        <div class="row px-3">
            <!-- Shop Overview -->
{{--            <div class="col-md-4">--}}
{{--                <div class="card p-3">--}}
{{--                    <h5>{{__('lang.Shop_Overview')}}</h5>--}}
{{--                    <div class="d-flex section-2 p-4 align-item-center">--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{number_format($overall_rating, 2, '.',',')}}</h3>--}}
{{--                            <p>{{__('lang.Danh_gia_tong_the')}}</p>--}}

{{--                        </div>--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{$seller_credit_score}}</h3>--}}
{{--                            <p>{{__('lang.Seller_Credit_Score')}}</p>--}}

{{--                        </div>--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{$store_follow}}</h3>--}}
{{--                            <p>{{__('lang.Store_Follow')}}</p>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Visitor Statistics -->--}}
{{--            <div class="col-md-4">--}}
{{--                <div class="card p-3">--}}
{{--                    <h5>Visitor Statistics</h5>--}}
{{--                    <div class="d-flex section-2 p-4 align-item-center">--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{$view_count}}</h3>--}}
{{--                            <p>Today's Visitors</p>--}}
{{--                        </div>--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{$view_last_7_days}}</h3>--}}
{{--                            <p>Last 7 days</p>--}}
{{--                        </div>--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{$view_last_30_days}}</h3>--}}
{{--                            <p>Last 30 days</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- Today's Statistics -->
{{--            <div class="col-md-4">--}}

{{--                <div class="card p-3">--}}
{{--                    <h5>{{__("lang.Today's_Statistics")}}</h5>--}}
{{--                    <div class="d-flex section-2 p-4 align-item-center">--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{$today_orders}}</h3>--}}
{{--                            <p>{{__("lang.Today's_Order")}}</p>--}}
{{--                        </div>--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{$today_sale}}</h3>--}}
{{--                            <p>{{__("lang.Today's_Sales")}}</p>--}}
{{--                        </div>--}}
{{--                        <div class="text-center py-4">--}}
{{--                            <h3 class="text-primary">{{$estimated_profit}}</h3>--}}
{{--                            <p>{{__("lang.Loi_nhuan_uoc_tinh")}}</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </div>--}}
{{--            </div>--}}
        </div>

        <!-- Biểu đồ -->
{{--        <div class="container mt-4">--}}
{{--            <div class="card p-3">--}}
{{--                <h5 class="card-title">Biểu Đồ Doanh Số & Lượt Xem</h5>--}}
{{--                <canvas id="salesChart"></canvas>--}}
{{--                <div class="mt-3">--}}
{{--                    <button class="btn btn-primary btn-sm active" onclick="updateChart('today')">Hôm nay</button>--}}
{{--                    <button class="btn btn-outline-secondary btn-sm" onclick="updateChart('last7days')">7 ngày qua</button>--}}
{{--                    <button class="btn btn-outline-secondary btn-sm" onclick="updateChart('last30days')">30 ngày trước</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="container mt-4">
            <div class="card p-3">
                <canvas id="salesChart"></canvas>
                <div class="mt-3">
                    <button id="btn-today" class="btn btn-primary btn-sm" onclick="updateChart('today')">{{__("lang.Hom_nay")}}</button>
                    <button id="btn-7days" class="btn btn-outline-secondary btn-sm" onclick="updateChart('last7days')">{{__("lang.7_ngay_qua")}}</button>
                    <button id="btn-30days" class="btn btn-outline-secondary btn-sm" onclick="updateChart('last30days')">{{__("lang.30_ngay_truoc")}}</button>
                </div>
            </div>
        </div>


{{--        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
{{--        <script>--}}
{{--            document.addEventListener("DOMContentLoaded", function () {--}}
{{--                const ctx = document.getElementById('salesChart').getContext('2d');--}}

{{--                // Khởi tạo biểu đồ rỗng--}}
{{--                let salesChart = new Chart(ctx, {--}}
{{--                    type: 'line',--}}
{{--                    data: {--}}
{{--                        labels: [],--}}
{{--                        datasets: [--}}
{{--                            {--}}
{{--                                label: 'Doanh số ($)',--}}
{{--                                data: [],--}}
{{--                                borderColor: '#ff6384',--}}
{{--                                backgroundColor: 'rgba(255,99,132,0.2)',--}}
{{--                                borderWidth: 2,--}}
{{--                                fill: false,--}}
{{--                                tension: 0.3,--}}
{{--                                pointRadius: 4,--}}
{{--                                pointBackgroundColor: '#ff6384'--}}
{{--                            },--}}
{{--                            {--}}
{{--                                label: 'Lượt truy cập',--}}
{{--                                data: [],--}}
{{--                                borderColor: '#36a2eb',--}}
{{--                                backgroundColor: 'rgba(54,162,235,0.2)',--}}
{{--                                borderWidth: 2,--}}
{{--                                fill: false,--}}
{{--                                tension: 0.3,--}}
{{--                                pointRadius: 4,--}}
{{--                                pointBackgroundColor: '#36a2eb'--}}

{{--                            }--}}
{{--                        ]--}}
{{--                    },--}}
{{--                    options: {--}}
{{--                        responsive: true,--}}
{{--                        interaction: {--}}
{{--                            mode: 'index',--}}
{{--                            intersect: false--}}
{{--                        },--}}
{{--                        plugins: {--}}
{{--                            legend: {--}}
{{--                                position: 'top'--}}
{{--                            },--}}
{{--                            tooltip: {--}}
{{--                                enabled: true,--}}
{{--                                mode: 'nearest',--}}
{{--                                intersect: false--}}
{{--                            }--}}
{{--                        },--}}
{{--                        scales: {--}}
{{--                            y: {--}}
{{--                                beginAtZero: true--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                });--}}

{{--                // Hàm cập nhật dữ liệu từ API--}}
{{--                window.updateChart = function (range) {--}}
{{--                    fetch(`/admin/chart-data/${range}`)--}}
{{--                        .then(response => response.json())--}}
{{--                        .then(data => {--}}
{{--                            salesChart.data.labels = data.labels;--}}
{{--                            salesChart.data.datasets[0].data = data.sales;--}}
{{--                            salesChart.data.datasets[1].data = data.views;--}}
{{--                            salesChart.update();--}}
{{--                        });--}}
{{--                };--}}

{{--                // Tải mặc định là hôm nay--}}
{{--                updateChart('today');--}}
{{--            });--}}
{{--        </script>--}}

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var ctx = document.getElementById('salesChart').getContext('2d');

                var salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [], // sẽ được set sau khi fetch
                        datasets: [
                            {
                                label: '{{ __('lang.doanh_so') . ' ($)' }}',
                                data: [],
                                borderColor: '#ff6384',
                                backgroundColor: 'rgba(255,99,132,0.2)',
                                borderWidth: 2,
                                fill: false,
                                lineTension: 0.3,   // v2 dùng lineTension
                                pointRadius: 4,
                                pointBackgroundColor: '#ff6384',
                                yAxisID: 'ySales'   // trục trái
                            },
                            {
                                label: '{{ __('lang.luot_truy_cap') }}',
                                data: [],
                                borderColor: '#36a2eb',
                                backgroundColor: 'rgba(54,162,235,0.2)',
                                borderWidth: 2,
                                fill: false,
                                lineTension: 0.3,
                                pointRadius: 4,
                                pointBackgroundColor: '#36a2eb',
                                yAxisID: 'yViews'   // trục phải
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function (tooltipItem, data) {
                                    var dsLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                                    var val = tooltipItem.yLabel;
                                    if (dsLabel.indexOf('Doanh số') !== -1) {
                                        return dsLabel + ': ' + new Intl.NumberFormat('vi-VN').format(val) + ' $';
                                    }
                                    return dsLabel + ': ' + val;
                                }
                            }
                        },
                        hover: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            xAxes: [{
                                ticks: { autoSkip: false }
                            }],
                            yAxes: [
                                {
                                    id: 'ySales',
                                    type: 'linear',
                                    position: 'left',
                                    ticks: { beginAtZero: true },
                                    scaleLabel: {
                                        display: true,
                                        labelString: '{{ __('lang.doanh_so') . ' ($)' }}',
                                    }
                                },
                                {
                                    id: 'yViews',
                                    type: 'linear',
                                    position: 'right',
                                    ticks: { beginAtZero: true },
                                    gridLines: { drawOnChartArea: false },
                                    scaleLabel: {
                                        display: true,
                                        labelString: '{{ __('lang.luot_truy_cap') }}',
                                    }
                                }
                            ]
                        }
                    }
                });

                // Hàm cập nhật chart theo range
                window.updateChart = function (range) {
                    fetch('/admin/chart-data/' + range)
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            salesChart.data.labels = data.labels;
                            salesChart.data.datasets[0].data = data.sales; // Doanh số
                            salesChart.data.datasets[1].data = data.views; // Lượt truy cập
                            salesChart.update();
                        });
                };

                // tải mặc định "Hôm nay"
                updateChart('today');
            });
        </script>



        <div class="container mt-4">
            <div class="row">
                <!-- TOP 10 Best-Selling Items -->
                <div class="col-md-8">
                    <div class="card p-3">
                        <h5>{{__("lang.TOP 10 Best-Selling Items")}}</h5>
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__("lang.Ten_san_pham")}}</th>
{{--                                <th>{{__("lang.Price")}}</th>--}}
                                <th>{{__("lang.Khoi_luong_ban_hang")}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProducts as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->product_name }}</td>
{{--                                        <td>${{ number_format($product->price, 2) }}</td>--}}
                                        <td>{{ $product->sales_volume }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">{{__("lang.No_products_found")}}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right column for Order Statistics and Store Setting -->
                <div class="col-md-4">
                    <!-- Order Statistics -->
                    <div class="card p-4 mb-3">
                        <h5>{{__('lang.Thong_ke_dat_hang')}}</h5>
                        <div class="mt-3 row text-center">
                            <div class="col-6 p-3">

                                <h3 class="text-primary">{{$AllOrderToday}}</h3>
                                <p>{{__('lang.Tong_so_don_dat_hang')}}</p>

                            </div>
                            <div class="col-6 p-3">
                                <h3 class="text-primary">{{$AllOrderInProcess}}</h3>
                                <p>{{__("lang.In_Process")}}</p>

                            </div>
                            <div class="col-6 p-3">
                                <h3 class="text-primary">{{$AllOrderCompleted ?? 1}}</h3>
                                <p>{{__("lang.Da_hoan_thanh")}}</p>

                            </div>
                            <div class="col-6 p-3">
                                <h3 class="text-primary">{{$AllOrderCancel}}</h3>
                                <p>{{__("lang.Huy_don_hang")}}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Store Setting -->
                    <div class="card p-4">
                        <h5>{{__("lang.Thiet_Lap_cua_hang")}}</h5>
                        <div class="p-4 mb-3">
                            <p class="text-center mt-5">{{__("lang.Manage_your_shop")}}</p>
                            <div class="text-center">
                                <button class="btn btn-primary">{{__("lang.Set_up_now")}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_head')
    <style>
        .dashboard-container {
            background-color: #f9fafb;
        }

        .card {
            border-radius: 10px;
            transition: 0.3s all ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .card-body {
            padding: 1rem 1.25rem;
        }

        /* Mobile bỏ padding container */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 0 !important;
            }

            .dashboard-container .container {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }

        .container {
            max-width: 100%;
        }
    </style>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

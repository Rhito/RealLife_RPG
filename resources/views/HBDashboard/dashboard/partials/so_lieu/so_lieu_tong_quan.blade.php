<head>
    <!-- Bootstrap 5 CSS -->
    <link
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
            rel="stylesheet"
    />
    <!-- Bootstrap Icons -->
    <link
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
            rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
    />
</head>
@php
// lấy ra người đằng nhập  để where theo nó
$user =\Auth::guard('admin')->user();
// số lượng sản  phẩm của họ
$so_luong_san_pham_don_hang_cua_seller = \App\Modules\Product\Models\ShopProducts::where('shop_id',$user->id)->count();
$so_luong_san_pham_don_hang_cua_admin= \App\Modules\Product\Models\Product::count();
// tổng số tiền bán hàng
@endphp

<div class="dashboard-stats custom-card mb-4 p-4">
   <div class="section-header" style="background: none !important; padding: 0 !important;">
       <h5 class="section-title mb-4">
           <i class="bi bi-bar-chart text-danger"></i>
           <span>{{ __('lang.Thong_ke') }}</span>
       </h5>
   </div>
    <div class="stats-container">
        <!-- Count Product -->
        <div class="stat-card stat-card-purple d-flex align-items-center justify-content-center">
            <div class="stat-content text-center">
                <h2>{{ $shop_product }}</h2>
                <div class="stat-label">{{__('lang.Tong_san_pham')}} </div>
            </div>
        </div>

        <!-- Tổng tiền bán hàng -->
        <div class="stat-card stat-card-orange d-flex align-items-center justify-content-center">
            <div class="stat-content text-center">
                <h2>{{ CommonHelper::convertPrice($tongtienbanhang) }}</h2>
                <div class="stat-label">{{__('lang.Tong_so_tien_ban_hang')}} </div>
            </div>
        </div>

        <!-- Tổng tiền chờ xác nhận -->
        <div class="stat-card stat-card-orange d-flex align-items-center justify-content-center">
            <div class="stat-content text-center">
                <h2>{{ CommonHelper::convertPrice($tongtien_choxacnhan) }}</h2>
                <div class="stat-label">{{__('lang.Tong_so_tien_dang_cho_xac_nhan')}} </div>
            </div>
        </div>

        <!-- Tổng tiền chờ xác nhận -->
        <div class="stat-card stat-card-orange d-flex align-items-center justify-content-center">
            <div class="stat-content text-center">
                <h2>{{ CommonHelper::convertPrice($loinhuan) }}</h2>
                <div class="stat-label">{{__('lang.Loi_nhuan')}} </div>
            </div>
        </div>

        <!-- DS WP/LDP ký mới -->
        <div class="stat-card stat-card-green">
            <div class="stat-icon">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">DS WP/LDP ký mới</div>
                <div class="stat-value"></div>
                <div class="stat-action">
                    <span>View Details</span>
                </div>
            </div>
        </div>

        <!-- Doanh thu dự án -->
        <div class="stat-card stat-card-blue">
            <div class="stat-icon">
                <i class="bi bi-briefcase"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Doanh thu dự án</div>
                <div class="stat-value"></div>
                <div class="stat-action">
                    <span>View Details</span>
                </div>
            </div>
        </div>

        <!-- Tổng phiếu thu -->
        <div class="stat-card stat-card-indigo">
            <div class="stat-icon">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng phiếu thu</div>
                <div class="stat-value"></div>
                <div class="stat-action">
                    <span>View Details</span>
                </div>
            </div>
        </div>

        <!-- Tổng P.chi trong tháng -->
        <div class="stat-card stat-card-red">
            <div class="stat-icon">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng P.chi trong tháng</div>
                <div class="stat-value"></div>
                <div class="stat-action">
                    <span>View Details</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-card {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        transition: all 0.3s ease;
        background: white;
    }

    /* HOVER cho custom-card */
    .custom-card:hover {
        transform: scale(1.01);
        box-shadow: 0 10px 25px -3px rgb(0 0 0 / 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    /* HOVER cho btn-filter - THIẾU ĐOẠN NÀY */
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px 0 rgb(59 130 246 / 0.4);
    }

    .btn-clear {
        border: 2px solid #dc2626;
        color: #dc2626;
        background: transparent;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    /* HOVER cho btn-clear - THIẾU ĐOẠN NÀY */
    .btn-clear:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-2px);
        text-decoration: none;
    }
    .dashboard-stats {
        margin-bottom: 2rem;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 16px 16px 0 0;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        color: white;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 16px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .stat-action {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .stat-action:hover {
        color: #374151;
    }
    .stat-card .stat-content h2 {
        font-size: 30px;
    }

    /* Color variants */
    .stat-card-purple::before {
        background: linear-gradient(135deg, #8b5cf6, #a855f7);
    }
    .stat-card-purple .stat-icon {
        background: linear-gradient(135deg, #8b5cf6, #a855f7);
    }

    .stat-card-orange::before {
        background: linear-gradient(135deg, #f59e0b, #f97316);
    }
    .stat-card-orange .stat-icon {
        background: linear-gradient(135deg, #f59e0b, #f97316);
    }

    .stat-card-cyan::before {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }
    .stat-card-cyan .stat-icon {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .stat-card-pink::before {
        background: linear-gradient(135deg, #ec4899, #db2777);
    }
    .stat-card-pink .stat-icon {
        background: linear-gradient(135deg, #ec4899, #db2777);
    }

    .stat-card-green::before {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    .stat-card-green .stat-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-card-blue::before {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    .stat-card-blue .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .stat-card-indigo::before {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }
    .stat-card-indigo .stat-icon {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }

    .stat-card-red::before {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    .stat-card-red .stat-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    @media (max-width: 1200px) {
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>
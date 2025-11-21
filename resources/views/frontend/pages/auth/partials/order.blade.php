<style>
    .page-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
    }

    .tabs {
        display: flex;
        flex-wrap: wrap; /* Cho phép xuống hàng nếu không đủ chỗ */
        border-bottom: 1px solid #e5e5e5;
        margin-bottom: 20px;
        padding-bottom: 5px;
        gap: 10px; /* Khoảng cách giữa các nút */
        overflow-x: auto; /* Cuộn ngang nếu vẫn bị tràn */
        scrollbar-width: thin;
        scrollbar-color: #ccc transparent;
    }

    .tabs::-webkit-scrollbar {
        height: 6px;
    }

    .tabs::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }

    .tab-item {
        padding: 10px 18px;
        cursor: pointer;
        font-size: 14px;
        color: #666;
        white-space: nowrap;
        transition: all 0.3s ease;
        border-bottom: 2px solid transparent;
        background-color: #f9f9f9; /* nền nhẹ để dễ nhìn */
        border-radius: 6px 6px 0 0;
        flex-shrink: 0; /* Không bị co lại khi cuộn ngang */
    }

    .tab-item.active {
        color: #ff5000; /* màu chủ đạo */
        font-weight: 600;
        border-bottom: 2px solid #ff5000;
        background-color: #fff; /* làm nổi bật tab */
    }

    .tab-item:hover {
        color: #ff5000;
    }

</style>
<div class="my-order">
    <div class="page-title" style="margin-left: 20px;">{{__('lang.my_orders')}}</div>

    <!-- Tabs trạng thái -->
    <div class="tabs" style="margin-left: 20px;">
        <div class="tab-item active" data-status="all">{{ __('lang.view_all') }}</div>
        <div class="tab-item" data-status="dat_don_hang_thanh_cong">{{ __('lang.order_success') }}</div>
        <div class="tab-item" data-status="cho_xac_nhan">{{ __('lang.pending_payment') }}</div>
        <div class="tab-item" data-status="thanh_toan_thanh_cong">{{ __('lang.payment_success') }}</div>
        <div class="tab-item" data-status="don_hang_dang_duoc_dong_goi">{{ __('lang.pending_shipment') }}</div>
        <div class="tab-item" data-status="don_hang_dang_roi_kho">{{ __('lang.to_be_received') }}</div>
        <div class="tab-item" data-status="dang_van_chuyen">{{ __('lang.shipping') }}</div>
        <div class="tab-item" data-status="da_nhan_hang_thanh_cong">{{ __('lang.to_be_reviewed') }}</div>
        <div class="tab-item" data-status="huy_don_hang">{{ __('lang.cancel_order') }}</div>
        <div class="tab-item" data-status="hoan_tra">{{ __('lang.refund') }}</div>
    </div>

    <!-- Danh sách đơn hàng -->
    <div id="order-list-container" style="width: 872px;">
        @include('frontend.pages.auth.statusOrder.orderListByStatus', ['products' => $products])
    </div>
</div>

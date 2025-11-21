<style>
    /* Container */
    .container-shp {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: space-between;
    }

    /* Mỗi shop item */
    .shop-item {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        width: calc(50% - 10px); /* 2 item trên 1 dòng */
        box-sizing: border-box;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .shop-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Avatar */
    .shop-item .avatar {
        flex-shrink: 0;
        width: 80px;
        height: 80px;
        border-radius: 10px;
        overflow: hidden;
        margin-right: 15px;
        border: 1px solid #f0f0f0;
    }

    .shop-item .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Info */
    .shop-item .info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Tên shop và nút hủy */
    .shop-item .name {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .shop-item .name p {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin: 0;
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .btn-unfollow {
        background: #fff;
        border: 1px solid #000000;
        color: #000000;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-unfollow:hover {
        background: #000000;
        color: #ffffff;
    }

    /* Thống kê */
    .shop-item .census {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .shop-item .census .number {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #666;
    }

    .shop-item .census .number p {
        margin: 0;

    }

    /* Phần bottom */
    .shop-item .census .bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .shop-item .census .bottom p {
        font-size: 13px;
        color: #666;
        margin: 0;
    }

    .btn-visit {
        background: linear-gradient(to right, #000000, grey);
        border: none;
        color: #fff;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap; /* Giữ chữ trên cùng 1 dòng */
        display: inline-block; /* Đảm bảo hiển thị inline */
    }

    .btn-visit:hover {
        opacity: 0.9;
    }

    /* Responsive cho màn nhỏ */
    @media (max-width: 768px) {
        .shop-item {
            width: 100%; /* 1 cột trên màn hình nhỏ */
        }
    }

</style>
<div data-v-2c463d0c="" class="set-container">
    <div data-v-7b24b1fe="" data-v-2c463d0c="" class="collect-content">
        <div data-v-7b24b1fe="" class="page-title" style="cursor: pointer;">
            <i data-v-7b24b1fe="" class="el-icon-arrow-left"></i>
            {{__('lang.follow_the_store')}}
        </div>
        <div data-v-7b24b1fe="" class="content"><!---->
            @if($flShop->isEmpty())
                <div data-v-7b24b1fe="" class="el-empty">
                    <div class="el-empty__image">
                        <!-- SVG hiển thị khi không có dữ liệu -->
                    </div>
                    <div class="el-empty__description">
                        <p>{{__('lang.no_data_found')}}</p>
                    </div>
                </div>
            @else
                <div class="container-shp">
                    @foreach($flShop as $fl)
                        <div class="shop-item">
                            <!-- Avatar -->
                            <div class="avatar">
                                <img src="{{ asset('filemanager/userfiles/' . optional($fl->shop)->logo_cua_hang) }}"
                                     alt="{{ optional($fl->shop)->ten_cua_hang ?? 'Shop đã xóa' }}">
                            </div>

                            <!-- Info -->
                            <div class="info">
                                <!-- Tên + nút hủy -->
                                <div class="name">
                                    <p title="{{ optional($fl->shop)->ten_cua_hang ?? 'Shop đã xóa' }}">
                                        {{ optional($fl->shop)->ten_cua_hang ?? 'Shop đã xóa' }}
                                    </p>
                                    @if($fl->shop)
                                        <button class="btn-unfollow" id="btn-follow" data-store-id="{{ $fl->shop->id }}">
                                            Hủy theo dõi
                                        </button>
                                    @else
                                        <button class="btn-unfollow" disabled>Không còn shop</button>
                                    @endif
                                </div>

                                <!-- Thống kê -->
                                <div class="census">
                                    <div class="number">
                                        <p>Đã bán：{{ optional($fl->shop)->sold_count ?? 0 }}</p>
                                        <p>Số lượt xem：{{ optional($fl->shop)->views ?? 0 }}</p>
                                    </div>
                                    <div class="bottom">
                                        <p>Tỷ lệ đánh giá tốt：{{ optional($fl->shop)->rating_percent ?? 0 }}%</p>
                                        @if($fl->shop)
                                            <button class="btn-visit"
                                                    onclick="window.location.href='{{ url('/store?storeId=' . $fl->shop->id) }}'">
                                                Ghé thăm cửa hàng
                                            </button>
                                        @else
                                            <button class="btn-visit" disabled>Shop đã xóa</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="el-loading-mask" style="display: none;">
                <div class="el-loading-spinner"><svg viewBox="25 25 50 50" class="circular">
                        <circle cx="50" cy="50" r="20" fill="none" class="path"></circle>
                    </svg><!----></div>
            </div>
        </div><!---->
    </div>
</div>
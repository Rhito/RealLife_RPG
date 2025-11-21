@extends('frontend.layout.layout')

@section('content')
    <style>
        .order-list {
            max-width: 950px;
            margin: 40px auto;
            font-family: "Inter", sans-serif;
        }

        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .filter-btn {
            padding: 6px 14px;
            border: 1px solid #FD2953;
            background: #fff;
            color: #FD2953;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: #FD2953;
            color: #fff;
        }

        .order-card {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            transition: box-shadow 0.2s;
        }

        .order-card:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.07);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .order-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .order-id {
            font-weight: 600;
            font-size: 16px;
            color: #222;
        }

        .order-date {
            font-size: 14px;
            color: #777;
        }

        .order-shop {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-shop img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        .order-shop span {
            font-size: 14px;
            font-weight: 600;
            color: #130c0c;
        }

        .product-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 12px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .product-name {
            font-size: 14px;
            color: #333;
        }

        .product-qty {
            font-size: 13px;
            color: #666;
        }

        .order-info {
            font-size: 15px;
            color: #333;
            margin-bottom: 10px;
        }

        .order-status {
            font-size: 13px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
        }

        .status-pending {
            background: #fff8e1;
            color: #fbc02d;
        }

        .status-completed {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-cancelled {
            background: #ffe6eb;
            color: #FD2953;
        }

        .order-footer {
            text-align: right;
            margin-top: 12px;
        }

        .order-btn {
            padding: 6px 14px;
            border: 1px solid #FD2953;
            background: #fff;
            color: #FD2953;
            border-radius: 4px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
            margin-left: 6px;
        }

        .order-btn:hover {
            background: #FD2953;
            color: #fff;
        }

        .order-btn.secondary {
            border-color: #666;
            color: #666;
        }

        .order-btn.secondary:hover {
            background: #666;
            color: #fff;
        }

        .order-btn.black {
            background: #000;
            color: #fff;
            border-color: #000;
        }

        .order-btn.black:hover {
            border-color: #000;
            background: #fff;
            color: #000;
        }

        .toggle-products {
            font-size: 14px;
            color: #FD2953;
            cursor: pointer;
            margin-top: 8px;
            display: inline-block;
        }

        /* Modal đánh giá */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            width: 400px;
            border-radius: 8px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 12px;
            top: 10px;
            cursor: pointer;
            font-size: 18px;
        }

        .rating {
            display: flex;
            gap: 5px;
            margin: 10px 0;
            font-size: 24px;
            cursor: pointer;
        }

        .rating span {
            color: #ccc;
        }

        .rating span.active {
            color: #F1B90A;
        }

        textarea {
            width: 100%;
            height: 80px;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            resize: none;
        }

        .btn-submit {
            margin-top: 10px;
            background: #FD2953;
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: #d81b47;
        }
    </style>

    <div class="order-list">
        <h1 style="margin-bottom: 24px; color:#222;">Đơn hàng của tôi</h1>

        <!-- Bộ lọc trạng thái -->
        <div class="filter-bar">
            <a href="#" class="filter-btn active">Tất cả</a>
            <a href="#" class="filter-btn">Đang xử lý</a>
            <a href="#" class="filter-btn">Hoàn tất</a>
            <a href="#" class="filter-btn">Đã hủy</a>
        </div>

         <!-- Đơn Đang xử lý -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-left">
                    <div class="order-id">Đơn #DH2001</div>
                    <div class="order-date">23/09/2025 14:35</div>
                </div>
                <div class="order-shop">
                    <img src="https://via.placeholder.com/35" alt="shop">
                    <span>Shop Hồng Đỏ</span>
                </div>
            </div>
            <div class="product-list">
                <div class="product-item">
                    <img src="https://via.placeholder.com/70" alt="sp1">
                    <div>
                        <div class="product-name">Sản phẩm A</div>
                        <div class="product-qty">x2</div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="https://via.placeholder.com/70" alt="sp2">
                    <div>
                        <div class="product-name">Sản phẩm B</div>
                        <div class="product-qty">x1</div>
                    </div>
                </div>
                <div class="product-item extra-product" style="display:none;">
                    <img src="https://via.placeholder.com/70" alt="sp3">
                    <div>
                        <div class="product-name">Sản phẩm C</div>
                        <div class="product-qty">x3</div>
                    </div>
                </div>
                <span class="toggle-products" onclick="toggleProducts(this)">Xem thêm sản phẩm</span>
            </div>
            <div class="order-info"><strong>Tổng tiền:</strong> 1.250.000 đ</div>
            <div class="order-status status-pending">Đang xử lý</div>
            <div class="order-footer">
                <a href="#" class="order-btn">Xem chi tiết</a>
            </div>

        </div>
        <!-- Đơn Đang xử lý -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-left">
                    <div class="order-id">Đơn #DH2001</div>
                    <div class="order-date">23/09/2025 14:35</div>
                </div>
                <div class="order-shop"><img src="https://via.placeholder.com/35" alt="shop"> <span>Shop Hồng Đỏ</span>
                </div>
            </div>
            <div class="product-list">
                <div class="product-item">
                    <img src="https://via.placeholder.com/70" alt="sp1">
                    <div>
                        <div class="product-name">Sản phẩm A</div>
                        <div class="product-qty">x2</div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="https://via.placeholder.com/70" alt="sp2">
                    <div>
                        <div class="product-name">Sản phẩm B</div>
                        <div class="product-qty">x1</div>
                    </div>
                </div>
                <div class="product-item extra-product" style="display:none;">
                    <img src="https://via.placeholder.com/70" alt="sp3">
                    <div>
                        <div class="product-name">Sản phẩm C</div>
                        <div class="product-qty">x3</div>
                    </div>
                </div>
                <span class="toggle-products" onclick="toggleProducts(this)">Xem thêm sản phẩm</span>
            </div>
            <div class="order-info"><strong>Tổng tiền:</strong> 1.250.000 đ</div>
            <div class="order-status status-pending">Đang xử lý</div>
            <div class="order-footer"><a href="#" class="order-btn">Xem chi tiết</a></div>


        </div> <!-- Đơn Hoàn tất -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-left">
                    <div class="order-id">Đơn #DH2002</div>
                    <div class="order-date">22/09/2025 09:20</div>
                </div>
                <div class="order-shop"><img src="https://via.placeholder.com/35" alt="shop"> <span>Shop Xanh Lá</span>
                </div>
            </div>
            <div class="product-list">
                <div class="product-item">
                    <img src="https://via.placeholder.com/70" alt="sp1">
                    <div>
                        <div class="product-name">Sản phẩm A</div>
                        <div class="product-qty">x2</div>
                    </div>
                </div>
                <div class="product-item">
                    <img src="https://via.placeholder.com/70" alt="sp2">
                    <div>
                        <div class="product-name">Sản phẩm B</div>
                        <div class="product-qty">x1</div>
                    </div>
                </div>
                <div class="product-item extra-product" style="display:none;">
                    <img src="https://via.placeholder.com/70" alt="sp3">
                    <div>
                        <div class="product-name">Sản phẩm C</div>
                        <div class="product-qty">x3</div>
                    </div>
                </div>
                <span class="toggle-products" onclick="toggleProducts(this)">Xem thêm sản phẩm</span>
            </div>
            <div class="order-info"><strong>Tổng tiền:</strong> 560.000 đ</div>
            <div class="order-status status-completed">Hoàn tất</div>
            <div class="order-footer"><a href="#" class="order-btn">Xem chi tiết</a> <a href="#"
                                                                                        class="order-btn black">Mua
                    lại</a> <a href="javascript:void(0)" class="order-btn secondary" onclick="openModal()">Đánh giá</a>
            </div>
        </div> <!-- Đơn Đã hủy -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-left">
                    <div class="order-id">Đơn #DH2003</div>
                    <div class="order-date">21/09/2025 19:45</div>
                </div>
                <div class="order-shop"><img src="https://via.placeholder.com/35" alt="shop"> <span>Shop Đỏ</span></div>
            </div>
            <div class="product-list">
                <div class="product-item">
                    <img src="https://via.placeholder.com/70" alt="sp1">
                    <div>
                        <div class="product-name">Sản phẩm A</div>
                        <div class="product-qty">x2</div>
                    </div>
                </div>
            </div>
            <div class="order-info"><strong>Tổng tiền:</strong> 2.300.000 đ</div>
            <div class="order-status status-cancelled">Đã hủy</div>
            <div class="order-footer"><a href="#" class="order-btn">Xem chi tiết</a>
                <a href="#" class="order-btn black">Mua lại</a>
            </div>
        </div>
    </div>

    <!-- Modal đánh giá -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Đánh giá sản phẩm</h3>
            <div class="rating" id="ratingStars">
                <span data-value="1">&#9733;</span>
                <span data-value="2">&#9733;</span>
                <span data-value="3">&#9733;</span>
                <span data-value="4">&#9733;</span>
                <span data-value="5">&#9733;</span>
            </div>
            <textarea placeholder="Nhập nhận xét của bạn..."></textarea>
            <button class="btn-submit">Gửi đánh giá</button>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('reviewModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('reviewModal').style.display = 'none';
        }

        // Rating sao
        const stars = document.querySelectorAll('#ratingStars span');
        stars.forEach(star => {
            star.addEventListener('click', function () {
                let value = this.getAttribute('data-value');
                stars.forEach(s => {
                    s.classList.toggle('active', s.getAttribute('data-value') <= value);
                });
            });
        });

        // Toggle hiển thị thêm sản phẩm trong đơn
        function toggleProducts(el) {
            let parent = el.closest('.product-list');
            let extras = parent.querySelectorAll('.extra-product');
            let isHidden = extras[0].style.display === 'none';
            extras.forEach(item => item.style.display = isHidden ? 'flex' : 'none');
            el.textContent = isHidden ? 'Thu gọn sản phẩm' : 'Xem thêm sản phẩm';
        }
    </script>
@endsection

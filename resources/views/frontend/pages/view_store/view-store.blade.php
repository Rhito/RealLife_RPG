@extends('frontend.layout.layout')
<style>
    :root {
        --color-main: #f89900;
        --color-price: #f89900;
        --color-submain: #f89900;
        --color-black: #000;
        --color-white: #fff;
        --color-icon: #646464;
        --color-title: #333;
        --color-subtitle: #999;
        --color-tips: #ababab;
        --color-grey: #4d4d4d;
        --color-border: #eee;
        --color-footer-bg: #212121;
        --color-little-grey: #fafafa;
        --color-submain1: #fff;
        --color-success: #32aa15;
        --color-fail: #e10015;
        --color-footer: #f89900;
        --background: #fef5e6;
        --bg-border-color: #fcd699;
        --hover-color: #f9ad33;
        --active-button: #f9ad33;
    }
    .header-fiexd
    {
        padding-top: 22px;
        position: fixed;
        top: 0;
        z-index: 999;
        padding-bottom: 0px !important;
        left: 0;
        width: 100%;
        height: auto !important;
        transition: padding .5s
        linear 0s;
        background: #fff;
    }

    .wrap .head-wrap .box .container-left img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        -o-object-fit: cover;
        object-fit: cover;
        margin-right: 20px;
    }
    .wrap .head-wrap {
        width: 100%;
        height: 300px;
        background-size: cover;
        background-position: 50%;
        background-repeat: no-repeat;
        position: relative;
        overflow: hidden;
        transition: all 1s ease;
    }
    .wrap .head-wrap .box {
        max-width: 1010px;
        height: 135px;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        background: hsla(0, 0%, 100%, .2);
        border-radius: 4px;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: absolute;
        z-index: 11;
    }
    .wrap .head-wrap .box .container-left {
        display: flex;
        justify-content: flex-start;
        padding-left: 86px;
    }
    .wrap .nav ul {
        padding: 0 60px;
        width: 100%;
        max-width: 1160px;
        list-style: none;
        margin: 0 auto;
        height: 100%;
        height: 40px;
    }
    .wrap .nav ul li {
        float: left;
        color: #fff;
        margin-right: 59px;
        font-weight: 500;
        cursor: pointer;
        height: 100%;
        line-height: 35px;
    }
    .wrap .nav {
        width: 100%;
        padding: 8px 0;
        background: #212121;
        display: flex
    ;
        align-items: center;
    }
    .wrap .nav .active {
        border-bottom: 2px solid #000;;
    }
    .wrap .nav {
        width: 100%;
        padding: 8px 0;
        background: #212121;
        display: flex;
        align-items: center;
    }
    .wrap .head-wrap .box .container-left p {
        font-family: Roboto;
        font-style: normal;
        font-weight: 600;
        font-size: 18px;
        line-height: 19px;
        margin-bottom: 0;
        color: #f5f5f7;
        -webkit-text-stroke: 0 #333;
        filter: drop-shadow(2px 3px 0 rgba(51, 51, 51, .4));
    }
    .wrap .head-wrap .box .btn {
        color: #f5f5f7;
        background: #000;
        border-radius: 25px;
        width: 95px;
        height: 24px;
        line-height: 24px;
        border: none;
        margin-right: 34px;
        display: flex;
        justify-content: center;
        align-items: center;
        will-change: filter;
        transition: filter .8s;
    }
    .wrap .head-wrap .box .btn:hover {
        filter: drop-shadow(0 0 4px #000);
    }
    .app-container {
        max-width: 1200px;
        margin: 0 auto;
        padding-top: 14px;
    }
     .wrap .store-content {
        padding: 27px 20px;
    }
     .wrap .el-pagination.is-background .el-pager li:not(.disabled).active {
        background-color: #000 !important;
        color: #fff!important;
    }
     .wrap .pro-container:hover {
        transform: scale(1.10);

    }
     .wrap .pro-container {
        transform: scale(1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

     .wrap .product {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        cursor: pointer;
    }

     .wrap .commodity-wrap {
        align-items: flex-start;
        margin-top: 24px;
    }
     .wrap .commodity-filter {
        width: 170px;
        border-right: 1px solid #eee;
    }
     .wrap .commodity-filter h2 {
        width: 100%;
        text-align: center;
        font-weight: 600;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #eee;
        padding: 20px 0;
        margin-bottom: 20px;
    }
     .wrap .el-pagination.is-background .el-pager li:hover {
        color: #000!important;
    }
     .wrap .full-page-loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5); /* Màu nền mờ */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Đảm bảo overlay nằm trên tất cả các phần tử khác */
    }

     .wrap .full-page-loading .el-loading-spinner {
        text-align: center;
    }

     .wrap .full-page-loading .circular {
        width: 50px;
        height: 50px;
    }

     .wrap .full-page-loading .circular .path {
        stroke: #fff; /* Màu của spinner, có thể tùy chỉnh */
    }
     .wrap .sort-options {
        display: flex;
        gap: 10px;
        align-items: center;
    }

     .wrap .sort-item {
        cursor: pointer;
        padding: 5px 10px;
        user-select: none;
    }

     .wrap .commodity-content-pagination {
         width: 100%;
         text-align: center;
         padding: 20px 0;
     }

     .wrap .sort-item.active {
        color: #ff0000; /* Đổi màu text thành đỏ khi active */
    }

     .wrap .sort-item.active span {
        color: #ff0000; /* Đảm bảo text "Giá" cũng đổi màu */
    }

     .wrap .sort-icon {
        margin-left: 5px;
        cursor: pointer;
    }

     .wrap .el-icon-caret-tb .active {
        color: #000 !important; /* Đổi màu icon khi active */
    }

    .sort-icon i {
        margin-left: 2px;
    }
    .commodity-content-item {
        margin-top: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(184px, 165px));
        grid-column-gap: 14px;
        align-content: center;
    }
    .wrap .commodity-content-list__t {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(184px, 165px));
        grid-column-gap: 12px;
        align-content: center;
        margin: 0px 20px;
    }
    .wrap .commodity-content-title li span {
        font-weight: 500;
        font-size: 14px;
        margin-right: 5px;
    }

    .wrap .sort-item {
        cursor: pointer;
        user-select: none;
        font-weight: 500;
        font-size: 14px;
        color: var(--color-black);
    }
    .wrap .commodity-filter ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .wrap .show-more-btn {
        margin-top: 10px;
        padding: 6px 12px;
        background: #f2f2f2;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
    }
    .wrap .show-more-btn:hover {
        scale: 1.05;
        color: #f89900;
    }
    .wrap .el-dialog__wrapper {

        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: auto;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2027;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .wrap .el-dialog {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        max-width: 800px;
        width: 90%;
    }

    .wrap .el-dialog__header {
        height: auto!important;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .wrap .el-dialog__headerbtn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .wrap .el-dialog__body {
        padding: 20px;
    }

    .wrap .product-info-content {
        display: flex;
        gap: 20px;
    }

    .wrap .product-info-left {
        flex: 1;
    }

    .wrap .product-info-right {
        flex: 1;
    }

    .wrap .product-info-right-title{
        margin: 0;
        font-weight: 600;
        font-size: 20px;
        line-height: 30px;
        color: var(--color-black);
        word-break: break-all;
    }

    .wrap .product-info-right-info-top {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    .wrap .product-info-right-info-des {
        font-weight: 400;
        font-size: 12px;
        /*color: var(--color-subtitle);*/
    }

    .wrap .product-info-right-info-price h2 {
        font-size: 14px;
        margin-right: 10px;
    }

    /*.wrap .product-info-right-info-price .price {*/
    /*    color: #ff0000;*/
    /*    font-size: 18px;*/
    /*    font-weight: 600;*/
    /*}*/

    .wrap .product-info-right-info-tool-item {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-left: 15px;
    }

    .wrap .product-info-right-info-des-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .wrap .label-title {
        font-weight: 500;
        margin-right: 10px;
        min-width: 100px;
    }

    .wrap .el-input-number {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .wrap .el-input-number__decrease,
    .wrap .el-input-number__increase {
        width: 30px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        cursor: pointer;
    }

    .wrap .el-input-number__decrease.is-disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }

    .wrap .el-input-number .el-buy-input__inner {
        width: 100%;
        text-align: center;
        align-items: center;
    }

    .wrap .product-info-right-info-buy {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    .wrap .product .poster,
    .wrap .product img {
        width: 100%;
        height: 100%;
        max-width: 100%!important;
        height: 165px;
    }

    .wrap .el-button--primary {
        background: #ff6600;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    .wrap .addcart {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
    }

    .wrap .attr-container {
        display: flex;
        gap: 10px;
    }

    .wrap .attr-item {
        border: 1px solid #ddd;
        padding: 5px;
        cursor: pointer;
    }

    .wrap .attr-item.active {
        border-color: #ff6600;
    }

    .wrap .attr-img img {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }

    .wrap .swiper-container {
        width: 100%;
        max-width: 376px;
    }

    .wrap .swiper-slide img {
        width: 100%;
        height: auto;
    }

    .wrap .gallery-thumbs .swiper-slide {
        opacity: 1;
    }

    .wrap .gallery-thumbs .swiper-slide-active {
        opacity: 1;
    }
    .wrap .variants-block {
        display: block;
    }

    .wrap .btn-purchase-now {
        background: #ff6600;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wrap #buy-now-form {
        width: 45%;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .commodity-filter ul {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            gap: 8px;
            padding: 10px 0;
        }

        .commodity-filter ul::-webkit-scrollbar {
            display: none; /* Ẩn scrollbar cho gọn */
        }

        .commodity-filter-item {
            margin-left: 0px!important;
        }

        .commodity-filter-item-active {
            background: #ff6600;
            color: #fff;
            border-color: #ff6600;
        }
        .commodity-filter {
            display: none;
        }
        .commodity-content-title {
            display: flex;
            flex-direction: column;
        }
        .wrap .head-wrap .box .container-left {
            padding-left: 34px;
        }
        .wrap .head-wrap .box .container-left p {
            font-family: Roboto;
            font-style: normal;
            font-weight: 600;
            font-size: 16px;
            line-height: 19px;
            margin-bottom: 0;
            color: #f5f5f7;
            -webkit-text-stroke: 0 #333;
            filter: drop-shadow(2px 3px 0 rgba(51, 51, 51, .4));
        }
        .commodity-content-item {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(2, minmax(184px, 165px));
            grid-column-gap: 14px;
            grid-row-gap: 0px;
            align-content: center;
        }
        .wrap .commodity-content-list__t {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, minmax(184px, 165px));
            grid-column-gap: 12px;
            align-content: center;
            margin: 0px 20px;
        }
        .commodity-content-title .flex-start {
            display: flex;
        }

        .sort-options {
            display: flex;
            gap: 10px;
            flex-direction: column;
            align-items: start!important;
        }
        .sort-options .sort-item {
            border-bottom: 1px solid #eee;
        }
        .wrap .sort .commodity-content-title {
            padding: 0;
        }
        .wrap .commodity-content-list__t {
            margin: 10px 0;
        }
        .wrap .commodity-content-pagination {
            width: 384px;
        }
        .wrap .store-content {
            padding: 20px 18px;
        }
    }
    /*  Modal box  */
    .wrap .product-info-left {
        align-items: flex-start;
    }
    .product-info-right-info-price h2 {
        min-width: 10px;
        text-align: left;
        font-weight: 400;
        font-size: 12px;
        /*color: var(--color-subtitle);*/
        padding-right: 8px;
    }
    /* Tablet (768px - 1024px) */
    @media (min-width: 768px) and (max-width: 1024px) {
        .wrap .head-wrap {
            height: 250px;
        }

        .wrap .head-wrap .box .container-left {
            padding-left: 40px;
        }

        .wrap .head-wrap .box .container-left img {
            width: 50px;
            height: 50px;
            margin-right: 15px;
        }

        .wrap .head-wrap .box .container-left p {
            font-size: 16px;
            line-height: 18px;
        }

        .wrap .nav ul {
            padding: 0 30px;
        }

        .wrap .nav ul li {
            margin-right: 40px;
            font-size: 15px;
        }

        .wrap .store-content {
            padding: 20px 15px;
        }

        .wrap .commodity-filter {
            width: 150px;
        }

        .commodity-content-item {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
        }

        .wrap .commodity-content-list__t {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin: 0 15px;
        }

        .wrap .sort-options {
            flex-wrap: wrap;
            gap: 8px;
        }

        .wrap .sort-item {
            font-size: 13px;
        }

        .wrap .el-dialog {
            width: 80%;
            max-width: 600px;
        }

        .wrap .product-info-content {
            flex-direction: row;
            gap: 15px;
        }

        .wrap .product-info-right-title {
            font-size: 18px;
            line-height: 26px;
        }

        .wrap .product .poster,
        .product img {
            height: 150px;
        }

        .wrap .swiper-container {
            max-width: 300px;
        }
    }

    /* Large screens (above 1024px) */
    @media (min-width: 1025px) {
        .wrap .head-wrap {
            height: 300px;
        }

        .wrap .commodity-filter {
            width: 170px;
        }

        .commodity-content-item {
            grid-template-columns: repeat(auto-fit, minmax(184px, 165px));
            gap: 14px;
        }

        .wrap .commodity-content-list__t {
            grid-template-columns: repeat(auto-fit, minmax(184px, 165px));
            gap: 12px;
        }

        .wrap .el-dialog {
            max-width: 800px;
        }
        .wrap .nav ul {
            display: flex;
            justify-content: center;
        }
    }
    .wrap .btn-buy-now {
        width: 100%!important;
    }

</style>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.addEventListener('cart:updated', async () => {
            await loadCart();
        });

        const tabs = document.querySelectorAll(".nav li");
        const container = document.querySelector(".app-container.store-content");
        const storeId = "{{ request('storeId') }}"; // Fallback storeId
        let categories = []; // Store categories
        let currentSort = null; // Theo dõi trạng thái sort hiện tại

        // Load categories from API
        async function loadCategories() {
            if (localStorage.getItem("categories")) {
                categories = JSON.parse(localStorage.getItem("categories"));
                return categories;
            }
            try {
                const res = await fetch(`https://banhang.khoweb.top/store/api/get_product_categories?storeId=${storeId}`);
                categories = await res.json();
                localStorage.setItem("categories", JSON.stringify(categories));
                return categories;
            } catch (error) {
                console.error("Error loading categories:", error);
                return [];
            }
        }

        // Render categories and integrated sort HTML
        function renderCategories(selectedCategoryId = null) {
            const params = new URLSearchParams(window.location.search);
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("show-more-btn")) {
                    const hidden = document.querySelector(".hidden-categories");
                    if (hidden) {
                        hidden.style.display = "block";
                        e.target.style.display = "none"; // Ẩn nút sau khi bấm
                    }
                }
            });

            if (!selectedCategoryId && params.has('category_id')) {
                selectedCategoryId = params.get('category_id');
            }

            // Hiển thị tối đa 30 category đầu tiên
            const maxVisible = 25;
            const visibleCategories = categories.slice(0, maxVisible);
            const hiddenCategories = categories.slice(maxVisible);

            return `
                    <div class="commodity-filter">
                        <h2>{{ __('lang.Category') }}</h2>
                        <ul>
                            <li class="first-item commodity-filter-item ${!selectedCategoryId || selectedCategoryId === "all" ? 'commodity-filter-item-active' : ''}" data-category-id="all">
                                {{ __('lang.All_products') }}
                            </li>
                            ${visibleCategories.map(category => `
                                <li class="commodity-filter-item ${String(selectedCategoryId) === String(category.id) ? 'commodity-filter-item-active' : ''}" data-category-id="${category.id}">
                                    <div class="list">${category.name} </div>
                                </li>
                            `).join('')}
                            <div class="hidden-categories" style="display:none;">
                                ${hiddenCategories.map(category => `
                                    <li class="commodity-filter-item ${String(selectedCategoryId) === String(category.id) ? 'commodity-filter-item-active' : ''}" data-category-id="${category.id}">
                                        <div class="list">${category.name}</div>
                                    </li>
                                `).join('')}
                            </div>
                        </ul>
                        ${hiddenCategories.length > 0 ? `<button class="show-more-btn">{{ __('lang.View_more') }}</button>` : ""}
                    </div>
                `;
        }
        const imageBase = "{{ rtrim(CommonHelper::getUrlImageThumb(''), '/') }}/";
        // Render products HTML
        function renderProducts(products) {
            return products.map(product => `
        <div class="commodity-content-item">
            <div class="item">
                <div class="pro-container">
                    <div class="product">
                        <div class="poster">
                            <img src="${imageBase + product.image || 'https://via.placeholder.com/150'}" alt="${product.name || '{{ __('lang.No_name_product') }}'}">
                        </div>
                        <h2>${product.price ? `$ ${product.price}` : '{{ __('lang.Contact') }}'}</h2>
                        <div class="product-res">{{ __('lang.Sold') }} ${product.sold || 0}</div>
                        <p>${product.name || '{{ __('lang.No_name_product') }}'}</p>
                        <div class="product-footer">
                            <div><i class="el-icon-shopping-cart-full"></i><span class="buy-btn" data-product-id="${product.id}" data-product-slug="${product.slug+'.html' || ''}">{{ __('lang.Purchase_Now') }}</span></div>
                            <div data-product-id="${ product.id }" class="favorite"><i class="el-icon-star-off"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
        }

        // Render pagination HTML
        function renderPagination(data) {
            const { current_page, last_page } = data;
            let paginationHtml = `
            <div class="commodity-content-pagination">
                <div class="es-pagination el-pagination is-background">
                    <button type="button" class="btn-prev" ${current_page === 1 ? 'disabled' : ''}>
                        <i class="el-icon el-icon-arrow-left"></i>
                    </button>
                    <ul class="el-pager">
        `;

            for (let i = 1; i <= Math.min(last_page, 5); i++) {
                paginationHtml += `<li class="number ${i === current_page ? 'active' : ''}">${i}</li>`;
            }

            if (last_page > 5) {
                paginationHtml += `<li class="el-icon more btn-quicknext el-icon-more"></li>`;
                paginationHtml += `<li class="number">${last_page}</li>`;
            }

            paginationHtml += `
                    </ul>
                    <button type="button" class="btn-next" ${current_page === last_page ? 'disabled' : ''}>
                        <i class="el-icon el-icon-arrow-right"></i>
                    </button>
                </div>
            </div>
        `;
            return paginationHtml;
        }

        // Show full-page loading overlay
        function showFullPageLoading() {
            const loadingOverlay = document.createElement("div");
            loadingOverlay.className = "full-page-loading";
            loadingOverlay.innerHTML = `
            <div class="el-loading-spinner">
                <svg viewBox="25 25 50 50" class="circular">
                    <circle cx="50" cy="50" r="20" fill="none" class="path"></circle>
                </svg>
            </div>
        `;
            document.body.appendChild(loadingOverlay);
        }

        // Hide full-page loading overlay
        function hideFullPageLoading() {
            const loadingOverlay = document.querySelector(".full-page-loading");
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
        }
        function renderProductList(data, isSuggested = false, categoryId = null, sortBy = null, search = null) {
            if (!data.data || data.data.length === 0) {
                return `${renderNoData()}`;
            }

            if (!isSuggested) {
                return `
        <div class="commodity-wrap flex-start">
            ${renderCategories(categoryId)}
            <div class="commodity-content">
                <div class="sort commodity-content-title flex-start">
                    <div class="sort-options">
                        <li class="flex-start sort-item ${!sortBy || sortBy === 'default' ? 'active' : ''}" data-sort-by="default">
                            <span>{{ __('lang.Sum_up') }}</span>
                        </li>

                        <li class="flex-start sort-item ${sortBy === 'price_asc' || sortBy === 'price_desc' ? 'active' : ''}" data-sort-by="price">
                            <span>{{ __('lang.Gia') }}</span>
                            <div class="flex-start el-icon-caret-tb">
                                <div class="sort-icon sort-icon-up"><i class="el-icon-caret-top ${sortBy === 'price_asc' ? 'active' : ''}"></i></div>
                                <div class="sort-icon sort-icon-down"><i class="el-icon-caret-bottom ${sortBy === 'price_desc' ? 'active' : ''}"></i></div>
                            </div>
                        </li>
                        <li class="flex-start sort-item ${sortBy === 'name_asc' || sortBy === 'name_desc' ? 'active' : ''}" data-sort-by="name">
                            <span>{{ __('lang.Ten') }}</span>
                            <div class="flex-start el-icon-caret-tb">
                                <div class="sort-icon sort-icon-up"><i class="el-icon-caret-top ${sortBy === 'name_asc' ? 'active' : ''}"></i></div>
                                <div class="sort-icon sort-icon-down"><i class="el-icon-caret-bottom ${sortBy === 'name_desc' ? 'active' : ''}"></i></div>
                            </div>
                        </li>
                        <span class="sort-item ${sortBy === 'newest' ? 'active' : ''}" data-sort-by="newest">{{ __('lang.New_products') }}<i class="el-icon-caret-bottom"></i></span>
                        <div class="search-content">
                            <div class="el-input el-input--prefix">
                                <input type="text" autocomplete="off" placeholder="{{ __('lang.Search_for_product_in_this_store') }}" class="el-input__inner" value="${search || ''}">
                                <span class="el-input__prefix"><i class="el-input__icon el-icon-search"></i></span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="${data?.data && data?.data.length ? 'commodity-content-list__t' : ''}">
                       ${data?.data && data?.data.length ? renderProducts(data.data) :  renderNoData()}
                </div>
              ${data?.data && data?.data.length ? renderPagination(data): ''}
            </div>
        </div>
        `;
            } else {
                return `
                        <div class="commodity recommend">
                            <div class="app-container">
                                <div class="commodity-wrap flex-start">
                                    <div class="commodity-content">

                                        <div class="commodity-content-item">
                                               ${data?.data && data?.data.length ? renderProducts(data.data) :  renderNoData()}
                                        </div>
                                        ${data?.data && data?.data.length ? renderPagination(data): ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
            }

        }
        function attachProductNavigationEvents() {
            // Use event delegation to handle clicks on product image or name
            container.addEventListener("click", (e) => {
                const element = e.target.closest(".product .poster, .product p");
                if (element) {
                    const productSlug = element.closest(".product").querySelector(".buy-btn").dataset.productSlug;
                    if (productSlug) {
                        window.location.href = `https://banhang.khoweb.top/${productSlug}`;
                    } else {
                        console.error("Product slug not found");
                    }
                }
            });
        }
        // Load products from API
        async function loadProducts(url, isSuggested = false, categoryId = null, sortBy = null, search = null, page = 1) {
            !search && !categoryId && !sortBy && showFullPageLoading();
            try {
                let queryParams = new URLSearchParams({ storeId, page });
                if (categoryId && categoryId !== "all") queryParams.append("category_id", categoryId);
                if (sortBy) queryParams.append("sort_by", sortBy);
                if (search) queryParams.append("search", search);

                const finalUrl = isSuggested
                    ? `https://banhang.khoweb.top/store/api/suggested_products?${queryParams.toString()}`
                    : `https://banhang.khoweb.top/store/api/get_all_products?${queryParams.toString()}`;

                const res = await fetch(finalUrl);
                const data = await res.json();
                hideFullPageLoading();
                attachProductNavigationEvents();
                if (!isSuggested) {
                    container.innerHTML = `
                <div class="commodity-wrap flex-start">
                    ${renderCategories(categoryId)}
                    <div class="commodity-content">
                        <div class="sort commodity-content-title">
                            <div class="sort-options">
                                <span class="sort-item ${!sortBy || sortBy === 'default' ? 'active' : ''}" data-sort-by="default">{{ __('lang.All_products') }}</span>
                                <li class="flex-start sort-item ${sortBy === 'price_asc' || sortBy === 'price_desc' ? 'active' : ''}" data-sort-by="price">
                                    <span>{{ __('lang.Price') }}</span>
                                    <div class="flex-start el-icon-caret-tb">
                                        <div class="sort-icon sort-icon-up"><i class="el-icon-caret-top ${sortBy === 'price_asc' ? 'active' : ''}"></i></div>
                                        <div class="sort-icon sort-icon-down"><i class="el-icon-caret-bottom ${sortBy === 'price_desc' ? 'active' : ''}"></i></div>
                                    </div>
                                </li>
                                <li class="flex-start sort-item ${sortBy === 'name_asc' || sortBy === 'name_desc' ? 'active' : ''}" data-sort-by="name">
                                    <span>{{ __('lang.Ten') }}</span>
                                    <div class="flex-start el-icon-caret-tb">
                                        <div class="sort-icon sort-icon-up"><i class="el-icon-caret-top ${sortBy === 'name_asc' ? 'active' : ''}"></i></div>
                                        <div class="sort-icon sort-icon-down"><i class="el-icon-caret-bottom ${sortBy === 'name_desc' ? 'active' : ''}"></i></div>
                                    </div>
                                </li>
                                <span class="sort-item ${sortBy === 'newest' ? 'active' : ''}" data-sort-by="newest">{{ __('lang.New_products') }}<i class="el-icon-caret-bottom"></i></span>
                                <div class="search-content">
                                    <div class="el-input el-input--prefix">
                                        <input type="text" autocomplete="off" placeholder="{{ __('lang.Search_for_product_in_this_store') }}" class="el-input__inner" value="${search || ''}">
                                        <span class="el-input__prefix"><i class="el-input__icon el-icon-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="${data?.data && data?.data.length ? 'commodity-content-list__t' : ''}">
                            ${data?.data && data?.data.length ? renderProducts(data.data) : renderNoData()}
                        </div>
                        ${data?.data && data?.data.length ? renderPagination(data) : ''}
                    </div>
                </div>
            `;
                    attachCategoryEvents(finalUrl, sortBy, search);
                    attachSortEvents(categoryId, search);
                    attachSearchEvent(categoryId, sortBy);
                    attachBuyNowEvents();
                    // Add product image/name click event listeners
                    document.querySelectorAll(".product .poster, .product p").forEach(element => {
                        element.addEventListener("click", () => {
                            const productSlug = element.closest(".product").querySelector(".buy-btn").dataset.productSlug;
                            if (productSlug) {
                                window.location.href = `https://banhang.khoweb.top/${productSlug}`;
                            } else {
                                console.error("Product slug not found");
                            }
                        });
                    });
                } else {
                    container.innerHTML = `
                        <div class="commodity recommend">
                            <div class="app-container">
                                <div class="commodity-wrap flex-start">
                                    <div class="commodity-content">
                                        <div class="commodity-content-item">
                                            ${data?.data && data?.data.length ? renderProducts(data.data) : renderNoData()}
                                        </div>
                                        ${data?.data && data?.data.length ? renderPagination(data) : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    attachBuyNowEvents();
                    // Add product image/name click event listeners for suggested products
                    document.querySelectorAll(".product .poster, .product p").forEach(element => {
                        element.addEventListener("click", () => {
                            const productSlug = element.closest(".product").querySelector(".buy-btn").dataset.productSlug;
                            if (productSlug) {
                                window.location.href = `https://banhang.khoweb.top/${productSlug}`;
                            } else {
                                console.error("Product slug not found");
                            }
                        });
                    });
                }

                attachPaginationEvents(isSuggested, categoryId, sortBy, search, data.current_page, data.last_page);
            } catch (error) {
                console.error("Error loading data:", error);
                container.innerHTML = `<div class="error">Failed to load products. Please try again later.</div>`;
                hideFullPageLoading();
            }
            toggleFavorites();
        }
        function  renderNoData() {
            return `<div class="no-data">
                       <div class="el-empty">
                          <div class="el-empty__image">
                             <svg viewBox="0 0 79 86" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs>
                                   <linearGradient id="linearGradient-1-3" x1="38.8503086%" y1="0%" x2="61.1496914%" y2="100%">
                                      <stop stop-color="#FCFCFD" offset="0%"></stop>
                                      <stop stop-color="#EEEFF3" offset="100%"></stop>
                                   </linearGradient>
                                   <linearGradient id="linearGradient-2-3" x1="0%" y1="9.5%" x2="100%" y2="90.5%">
                                      <stop stop-color="#FCFCFD" offset="0%"></stop>
                                      <stop stop-color="#E9EBEF" offset="100%"></stop>
                                   </linearGradient>
                                   <rect id="path-3-3" x="0" y="0" width="17" height="36"></rect>
                                </defs>
                                <g id="Illustrations" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                   <g id="B-type" transform="translate(-1268.000000, -535.000000)">
                                      <g id="Group-2" transform="translate(1268.000000, 535.000000)">
                                         <path id="Oval-Copy-2" d="M39.5,86 C61.3152476,86 79,83.9106622 79,81.3333333 C79,78.7560045 57.3152476,78 35.5,78 C13.6847524,78 0,78.7560045 0,81.3333333 C0,83.9106622 17.6847524,86 39.5,86 Z" fill="#F7F8FC"></path>
                                         <polygon id="Rectangle-Copy-14" fill="#E5E7E9" transform="translate(27.500000, 51.500000) scale(1, -1) translate(-27.500000, -51.500000) " points="13 58 53 58 42 45 2 45"></polygon>
                                         <g id="Group-Copy" transform="translate(34.500000, 31.500000) scale(-1, 1) rotate(-25.000000) translate(-34.500000, -31.500000) translate(7.000000, 10.000000)">
                                            <polygon id="Rectangle-Copy-10" fill="#E5E7E9" transform="translate(11.500000, 5.000000) scale(1, -1) translate(-11.500000, -5.000000) " points="2.84078316e-14 3 18 3 23 7 5 7"></polygon>
                                            <polygon id="Rectangle-Copy-11" fill="#EDEEF2" points="-3.69149156e-15 7 38 7 38 43 -3.69149156e-15 43"></polygon>
                                            <rect id="Rectangle-Copy-12" fill="url(#linearGradient-1-3)" transform="translate(46.500000, 25.000000) scale(-1, 1) translate(-46.500000, -25.000000) " x="38" y="7" width="17" height="36"></rect>
                                            <polygon id="Rectangle-Copy-13" fill="#F8F9FB" transform="translate(39.500000, 3.500000) scale(-1, 1) translate(-39.500000, -3.500000) " points="24 7 41 7 55 -3.63806207e-12 38 -3.63806207e-12"></polygon>
                                         </g>
                                         <rect id="Rectangle-Copy-15" fill="url(#linearGradient-2-3)" x="13" y="45" width="40" height="36"></rect>
                                         <g id="Rectangle-Copy-17" transform="translate(53.000000, 45.000000)">
                                            <mask id="mask-4-3" fill="white">
                                               <use xlink:href="#path-3-3"></use>
                                            </mask>
                                            <use id="Mask" fill="#E0E3E9" transform="translate(8.500000, 18.000000) scale(-1, 1) translate(-8.500000, -18.000000) " xlink:href="#path-3-3"></use>
                                            <polygon id="Rectangle-Copy" fill="#D5D7DE" mask="url(#mask-4-3)" transform="translate(12.000000, 9.000000) scale(-1, 1) translate(-12.000000, -9.000000) " points="7 0 24 0 20 18 -1.70530257e-13 16"></polygon>
                                         </g>
                                         <polygon id="Rectangle-Copy-18" fill="#F8F9FB" transform="translate(66.000000, 51.500000) scale(-1, 1) translate(-66.000000, -51.500000) " points="62 45 79 45 70 58 53 58"></polygon>
                                      </g>
                                   </g>
                                </g>
                             </svg>
                          </div>
                          <div class="el-empty__description">
                             <p>{{ __('lang.No_data_found') }}</p>
                          </div>

                       </div>
                    </div>`
        }

        // Hàm dùng chung
        function goToPage(page, isSuggested, categoryId, sortBy, search) {
            let queryParams = new URLSearchParams({ storeId, page });
            if (categoryId && categoryId !== "all")
                queryParams.append("category_id", categoryId);
            if (sortBy) queryParams.append("sort_by", sortBy);
            if (search) queryParams.append("search", search);

            const url = isSuggested
                ? `https://banhang.khoweb.top/store/api/suggested_products?${queryParams.toString()}`
                : `https://banhang.khoweb.top/store/api/get_all_products?${queryParams.toString()}`;

            loadProducts(url, isSuggested, categoryId, sortBy, search, page);
        }
        function attachBuyNowEvents() {
            // Prevent duplicate event listeners by using a single event listener
            // Lắng nghe click trên toàn trang
            document.addEventListener("click", async (e) => {
                hideFullPageLoading();
                // Khi bấm Buy button
                if (e.target.classList.contains("buy-btn")) {
                    const productId = e.target.getAttribute("data-product-id");

                    try {
                        // Gọi API lấy dữ liệu sản phẩm
                        const res = await fetch(`https://banhang.khoweb.top/store/api/get_product?storeId=${storeId}&productId=${productId}`);
                        const product = await res.json();

                        // Render modal
                        renderProductModal(product);
                        initializeQuantityControls(product.product);

                        // Hiển thị modal
                        const modal = document.querySelector(".el-dialog__wrapper.es-dialog");
                        modal.style.display = "flex";
                        document.body.style.overflow = "hidden";

                        // Khởi tạo Swiper
                        initializeSwiper(modal);

                    } catch (error) {
                        alert('error: ' + error.message);
                    }
                }

                // Đóng modal
                if (e.target.closest(".el-dialog__headerbtn")) {
                    const modal = e.target.closest(".el-dialog__wrapper");
                    if (modal) {
                        modal.style.display = "none";
                        document.body.style.overflow = "";
                    }
                }
            });

            // Handle ESC key to close modal
            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape") {
                    const openModal = document.querySelector('.el-dialog__wrapper[style*="block"]');
                    if (openModal) {
                        openModal.style.display = "none";
                        document.body.style.overflow = "";
                    }
                }
            })

        }

        function initializeQuantityControls(product) {
            const modal = document.querySelector(".el-dialog__wrapper.es-dialog");
            if (!modal) return;

            modal.addEventListener('click', (e) => {
                // nếu click đúng vào wrapper (phần nền) thì mới đóng
                if (e.target === modal) {
                    modal.style.display = 'none';   // hoặc class ẩn modal của bạn
                }
            });

            const input = modal.querySelector(".el-buy-input__inner");
            const decreaseBtn = modal.querySelector(".el-input-number__decrease");
            const increaseBtn = modal.querySelector(".el-input-number__increase");
            const totalPriceEl = modal.querySelector(".product-info-right-info-des-item p .price");

            let quantity = parseInt(input.value) || 1;
            const price = product.price || 0;

            function updateTotal() {
                if (price > 0) {
                    totalPriceEl.textContent = `${(price * quantity)} $`;
                } else {
                    totalPriceEl.textContent = "{{ __('lang.Contact') }}";
                }
                input.value = quantity;
                input.setAttribute("aria-valuenow", quantity);
            }

            // Tăng số lượng
            increaseBtn.addEventListener("click", () => {
                if (quantity < 9999) {
                    quantity++;
                    updateTotal();
                }
            });

            // Giảm số lượng
            decreaseBtn.addEventListener("click", () => {
                if (quantity > 1) {
                    quantity--;
                    updateTotal();
                }
            });

            // Nhập tay
            input.addEventListener("input", () => {
                let val = parseInt(input.value);
                if (isNaN(val) || val < 1) val = 1;
                if (val > 9999) val = 9999;
                quantity = val;
                updateTotal();
            });

            // Lần đầu load
            updateTotal();
        }

        // Render dữ liệu vào modal
        function renderProductModal(resp) {
            const payload = resp || {};
            const product = payload.product ?? payload;
            const rawVariants = payload.attributes ?? payload.variants ?? product.variants ?? [];

            // Translations (giữ nguyên blade nếu file .blade.php)
            const tAddCart      = "{{ __('lang.add_cart') }}";
            const tPurchaseNow  = "{{ __('lang.Purchase_Now') }}";
            const tRetailPrice  = "{{ __('lang.Retail_Price') }}";
            const tQuantity     = "{{ __('lang.so_luong') }}";
            const tSold         = "{{ __('lang.Sold') }}";
            const tFreeShip     = "{{ __('lang.Free') }}";
            const tFavorites    = "{{ __('lang.Favorites') }}";
            const tCustomerSvc  = "{{ __('lang.Customer_service') }}";
            const tShipment     = "{{ __('lang.After_placing_an_order_it_will_be_shipped_within_24_hours_The_shipping_time_depends_on_logistics_and_may_be_prolonged_Please_pay_attention_to_the_logistics_information_or_contact_Customer_Service') }}";

            const imageBase = "{{ rtrim(CommonHelper::getUrlImageThumb(''), '/') }}/";

            // --- GALLERY: luôn khai báo trước khi sử dụng ---
            const extraImgs = (product.image_extra || '')
                .split('|').map(s => s.trim()).filter(Boolean);

            const gallerySlides = [
                `<div class="swiper-slide"><img src="${imageBase + (product.image || 'no-image.png')}" class="img-region"></div>`,
                ...extraImgs.map(img => `<div class="swiper-slide"><img src="${imageBase + img}" class="img-region"></div>`)
            ].join('');

            const galleryThumbs = [
                `<div class="swiper-slide"><img src="${imageBase + (product.image || 'no-image.png')}"></div>`,
                ...extraImgs.map(img => `<div class="swiper-slide"><img src="${imageBase + img}"></div>`)
            ].join('');

            // --- Normalise variants: nếu payload.attributes đã grouped, dùng thẳng, else group raw flat variants ---
            let attributes = [];
            if (Array.isArray(payload.attributes) && payload.attributes.length) {
                // already grouped: expected shape [{ id, name, variants: [...] }]
                attributes = payload.attributes;
            } else if (Array.isArray(rawVariants) && rawVariants.length) {
                const groups = {};
                rawVariants.forEach(v => {
                    // hỗ trợ nhiều tên trường từ các response khác nhau
                    const groupId   = v.group_id ?? v.parent_id ?? v.parentId ?? v.parent_id ?? v.parentId ?? 'group_' + (v.attributes_id || '0');
                    const groupName = v.group_name ?? v.parent_name ?? v.parentName ?? v.groupName ?? 'Option';

                    if (!groups[groupId]) {
                        groups[groupId] = { id: groupId, name: groupName, variants: [] };
                    }

                    groups[groupId].variants.push({
                        id: v.id ?? v.child_id ?? null,
                        attributes_id: v.attributes_id ?? v.attributesId ?? v.attributes_id,
                        attribute_name: v.attribute_name ?? v.child_name ?? v.name ?? '',
                        image_extral: v.image_extral ?? v.image ?? null,
                        so_luong: v.so_luong ?? v.stock ?? 0
                    });
                });
                attributes = Object.values(groups);
            }

            // --- build html cho từng nhóm attribute ---
            function buildVariantList(group, showStock = false) {
                if (!group?.variants?.length) return '';
                return `
        <div class="variant-group" style="margin-top:8px;">
            <div style="font-weight:600;margin-bottom:6px;">${escapeHtml(group.name)}</div>
            <div class="variant-list" style="display:flex;flex-wrap:wrap;gap:8px;">
                ${group.variants.map(v => {
                    const vImgs = (v.image_extral || '').split('|').map(s => s.trim()).filter(Boolean);
                    const vThumb = imageBase + (vImgs[0] || product.image || 'no-image.png');
                    return `
                    <label class="variant-option" style="display:flex;align-items:center;gap:8px;border:1px solid #eee;padding:6px;cursor:pointer;border-radius:6px;">
                        <input type="radio" name="variant_${group.id}" value="${v.attributes_id}"
                               data-variant-id="${v.id}"
                               data-attribute-id="${v.attributes_id}"
                               data-variant-image="${vThumb}"
                               data-variant-stock="${v.so_luong || 0}">
                        ${ (group.name || '').toLowerCase().includes('{{__('lang.color')}}') || (group.name || '').toLowerCase().includes('color')
                        ? `<img src="${vThumb}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">`
                        : ''
                    }
                        <div style="display:flex;flex-direction:column;">
                            <span style="font-size:13px;">${escapeHtml(v.attribute_name)}</span>
                            ${ showStock ? `<small style="color:#666;font-size:12px;">${tQuantity}: ${v.so_luong || 0}</small>` : '' }
                        </div>
                    </label>`;
                }).join('')}
            </div>
        </div>`;
            }

            const variantsHtml = attributes.length
                ? `<div class="product-info-right-info-des-item variants-block">
               <span class="label-title">{{__('lang.Attributes')}}</span>
               ${attributes.map(g => buildVariantList(g, true)).join('')}
           </div>`
                : '';

            // --- SELECT modal ---
            const modal = document.querySelector('.el-dialog__wrapper.es-dialog');
            if (!modal) return console.error('Modal wrapper not found');

            // --- Insert modal HTML (gallerySlides và variantsHtml đã được khai báo ở trên) ---
            modal.innerHTML = `
    <div role="dialog" aria-modal="true" class="el-dialog el-dialog--center" style="margin-top:15vh;">
      <div class="el-dialog__header">
        <div class="dialog-title"><span>${tAddCart}</span></div>
        <button type="button" aria-label="Close" class="el-dialog__headerbtn">
            <i class="el-dialog__close el-icon el-icon-close"></i>
        </button>
      </div>
      <div class="el-dialog__body">
        <div class="dialog-content">
          <div class="product-info" style="display:flex;gap:20px;">
            <!-- LEFT -->
            <div class="product-info-left" style="min-width:320px;">
              <div class="swiper-container gallery-top"><div class="swiper-wrapper">${gallerySlides}</div></div>
              <div class="swiper-container gallery-thumbs" style="margin-top:8px;"><div class="swiper-wrapper">${galleryThumbs}</div></div>
            </div>
            <!-- RIGHT -->
            <div class="product-info-right" style="flex:1;">
              <h1 class="product-info-right-title">${escapeHtml(product.name)}</h1>
              <div class="product-info-right-info">
                <div class="product-info-right-info-top">
                  <div class="product-info-right-info-price flex-start" style="display:flex;align-items:center;justify-content:space-between;">
                    <h2>{{__('lang.price')}}: <span class="price">${formatPrice(product.price)}</span></h2>
                    <div style="display:flex;gap:10px;align-items:center;">
                      <div class="product-info-right-info-tool-item flex-start">
                        <i class="el-icon-service" style="color:var(--color-main);font-size:20px;"></i>
                        <span style="margin-left:6px;">${tCustomerSvc}</span>
                      </div>
                      <div data-product-id="${product.id}" class="product-info-right-info-tool-item flex-start favorite" style="cursor:pointer;">
                        <i class="el-icon-star-off"></i><span style="margin-left:6px;">${tFavorites}</span>
                      </div>
                    </div>
                  </div>
                  <div class="product-info-right-info-des-item flex-start" style="margin-top:8px;">
                      <div><span style="margin-right:5px;">${tSold}: </span><span>${product.sold||0}</span></div>
                  </div>
                  <div class="product-info-right-info-des-item flex-start" style="margin-top:8px;"><span class="label-title">${tShipment}</span></div>
                  <div class="product-info-right-info-des-item flex-start" style="margin-top:8px;"><span class="label-title">{{__('lang.Shipping')}}: ${tFreeShip}</span></div>
                </div>
                <!-- Quantity -->
                <div class="product-info-right-info-des" style="margin-top:12px;">
                  <div class="product-info-right-info-des-item" style="display:flex;align-items:center;">
                    <span class="label-title">${tQuantity}</span>
                    <div class="el-input-number" style="display:flex;align-items:center;gap:8px;">
                      <button type="button" class="el-input-number__decrease btn-decrease"><i class="el-icon-minus"></i></button>
                      <input type="text" name="qty" class="el-buy-input__inner qty-input" value="1"  data-product-id="${product.id}" data-product-attribute="">
                      <button type="button" class="el-input-number__increase btn-increase"><i class="el-icon-plus"></i></button>
                    </div>
                  </div>
                  ${variantsHtml}
                  <div class="product-info-right-info-des-item" style="margin-top:10px;">
                    <span class="label-title">${tRetailPrice}</span>
                    <p><span class="price">${formatPrice(product.price)}</span></p>
                  </div>
                </div>
                <!-- BUY / ADD -->
                <div class="product-info-right-info-buy" style="margin-top:16px;">
                  @auth
            <form id="buy-now-form" action="{{ route('settlement') }}" method="POST">
                          <input type="hidden" name="id" id="buy-now-id" value="${product.id}">
                          <input type="hidden" name="qty" id="buy-now-qty" value="1">
                          <input type="hidden" name="variant_id" id="buy-now-variant-id" value="">
                           <button type="submit" class="el-button el-button--primary btn-buy-now btn-purchase-now">
                                <span>${ tPurchaseNow }</span>
                           </button>
                    </form>
                    <div class="addcart flex-start addcartauth" data-product-id="${product.id}" data-user-id="{{ auth()->id() }}" data-variant-id=""><span>${tAddCart}</span></div>
                  @else
            <a href="/" class="el-button el-button--primary btn-purchase-now"><span>${tPurchaseNow}</span></a>
                    <div class="addcart flex-start addcartnotlogin"><span>${tAddCart}</span></div>
                  @endauth
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>`;

            // --- helpers (function declarations are hoisted) ---
            function formatPrice(v){ return '$'+(Number(v)||0).toLocaleString(); }
            function escapeHtml(s){ return (s||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); }

            // --- set qty ---
            const qtyInput = modal.querySelector('.qty-input');
            const btnInc   = modal.querySelector('.btn-increase');
            const btnDec   = modal.querySelector('.btn-decrease');
            const addcart  = modal.querySelector('.addcartauth');
            const buyQty   = modal.querySelector('#buy-now-qty');
            function setQty(v){
                const n = Math.max(1,parseInt(v)||1);
                if(qtyInput) qtyInput.value = n;
                if(buyQty) buyQty.value = n;
                if(addcart) addcart.dataset.quantity = n;
            }
            [btnInc,btnDec].forEach(btn=>{
                if(!btn) return;
                btn.addEventListener('click',()=>{
                    setQty(btn===btnInc? Number(qtyInput.value)+1 : Math.max(1,Number(qtyInput.value)-1));
                });
            });
            if(qtyInput) qtyInput.addEventListener('change',()=>setQty(qtyInput.value));
            setQty(1);

            // --- chọn variant => ghi vào dataset ---
            modal.querySelectorAll('.variant-option input[type="radio"]').forEach(r=>{
                r.addEventListener('change',()=>{
                    const chosen = Array.from(modal.querySelectorAll('.variant-option input[type="radio"]:checked')).map(i=>i.dataset.variantId || i.value);
                    const chosenId = chosen.join(',');
                    if(addcart) addcart.dataset.variantId = chosenId;
                    const buyVar = modal.querySelector('#buy-now-variant-id');
                    if(buyVar) buyVar.value = chosenId;
                });
            });

            // --- close ---
            modal.querySelector('.el-dialog__headerbtn')?.addEventListener('click',()=> modal.style.display='none');

            // --- re-init favorite ---
            toggleFavorites();
        }




        // Swiper
        function initializeSwiper(modal) {
            const galleryTop = modal.querySelector(".gallery-top");
            const galleryThumbs = modal.querySelector(".gallery-thumbs");


            if (galleryTop && galleryThumbs) {
                const galleryThumbsSwiper = new Swiper(galleryThumbs, {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesProgress: true,
                });

                new Swiper(galleryTop, {
                    spaceBetween: 10,
                    thumbs: {
                        swiper: galleryThumbsSwiper,
                    },
                });
            }
        }

        attachBuyNowEvents();

        // Gắn sự kiện phân trang
        function attachPaginationEvents(isSuggested, categoryId, sortBy, search, currentPage, lastPage) {
            const prevBtn = container.querySelector(".btn-prev");
            const nextBtn = container.querySelector(".btn-next");
            const pageNumbers = container.querySelectorAll(".el-pager .number");

            // Prev
            prevBtn?.addEventListener("click", () => {
                if (currentPage > 1) {
                    goToPage(currentPage - 1, isSuggested, categoryId, sortBy, search);
                }
            });

            // Next
            nextBtn?.addEventListener("click", () => {
                if (currentPage < lastPage) {
                    goToPage(currentPage + 1, isSuggested, categoryId, sortBy, search);
                }
            });

            // Number buttons
            pageNumbers.forEach(page => {
                page.addEventListener("click", () => {
                    const pageNum = parseInt(page.textContent);
                    goToPage(pageNum, isSuggested, categoryId, sortBy, search);
                });
            });
        }


        // Attach events for categories
        function attachCategoryEvents(baseUrl, sortBy, search) {
            const categoryItems = container.querySelectorAll(".commodity-filter-item");
            categoryItems.forEach(item => {
                item.addEventListener("click", () => {
                    const categoryId = item.getAttribute("data-category-id");
                    categoryItems.forEach(i => i.classList.remove("commodity-filter-item-active"));
                    item.classList.add("commodity-filter-item-active");

                    let queryParams = new URLSearchParams({ storeId, page: 1 });
                    if (categoryId !== "all") queryParams.append("category_id", categoryId);
                    if (sortBy) queryParams.append("sort_by", sortBy);
                    if (search) queryParams.append("search", search);
                    const url = `https://banhang.khoweb.top/store/api/get_all_products?${queryParams.toString()}`;
                    loadProducts(url, false, categoryId, sortBy, search);
                });
            });
        }

        window.currentSortField = window.currentSortField || null;
        window.currentSortOrder = window.currentSortOrder || null;

        function attachSortEvents(categoryId = null, search = "") {
            const container = document.querySelector(".app-container.store-content");
            if (!container) return;

            const sortItems = container.querySelectorAll(".sort-item");

            // helper: build URL
            function buildUrl(page = 1) {
                const params = new URLSearchParams();
                if (typeof storeId === "undefined") {
                    console.warn("storeId is not defined. Set storeId variable before calling attachSortEvents.");
                } else {
                    params.set("storeId", storeId);
                }
                params.set("page", page);

                if (window.currentSortField && window.currentSortOrder) {
                    params.set("sort_by", `${window.currentSortField}_${window.currentSortOrder}`);
                }
                if (categoryId && categoryId !== "all") params.set("category_id", categoryId);
                if (search) params.set("search", search);
                return `/store/api/get_all_products?${params.toString()}`;
            }

            // helper: update UI icons
            function updateSortIcons() {
                sortItems.forEach(item => {
                    const field = item.getAttribute("data-sort-by");
                    const up = item.querySelector(".sort-icon-up");
                    const down = item.querySelector(".sort-icon-down");

                    // reset
                    item.classList.remove("active");
                    up?.classList.remove("active");
                    down?.classList.remove("active");

                    if (field === window.currentSortField) {
                        item.classList.add("active");
                        if (window.currentSortOrder === "asc") {
                            up?.classList.add("active");
                        } else if (window.currentSortOrder === "desc") {
                            down?.classList.add("active");
                        }
                    }
                });
            }

            // attach click event cho từng sort-item
            sortItems.forEach(item => {
                item.addEventListener("click", () => {
                    const field = item.getAttribute("data-sort-by");

                    if (field === "default") {
                        window.currentSortField = null;
                        window.currentSortOrder = null;
                    } else {
                        if (window.currentSortField === field) {
                            // toggle asc <-> desc
                            window.currentSortOrder = window.currentSortOrder === "asc" ? "desc" : "asc";
                        } else {
                            // chọn field khác thì mặc định asc
                            window.currentSortField = field;
                            window.currentSortOrder = "asc";
                        }
                    }

                    updateSortIcons();
                    loadProducts(buildUrl(1), false, categoryId, `${window.currentSortField}_${window.currentSortOrder}`, search);
                });
            });

            // khởi tạo icon state
            updateSortIcons();
        }


        // Attach event for search input
        function attachSearchEvent(categoryId, sortBy) {
            const searchInput = container.querySelector(".el-input__inner");
            let debounceTimer;

            searchInput.addEventListener("input", () => {
                clearTimeout(debounceTimer); // reset timer cũ

                debounceTimer = setTimeout(() => {
                    const search = searchInput.value.trim();

                    // Nếu không có search, category, sort thì show loading full page
                    if (!search && (!categoryId || categoryId === "all") && (!sortBy || sortBy === "default")) {
                        showFullPageLoading();
                        return;
                    }

                    let queryParams = new URLSearchParams({ storeId, page: 1 });
                    if (categoryId && categoryId !== "all") queryParams.append("category_id", categoryId);
                    if (sortBy && sortBy !== "default") queryParams.append("sort_by", sortBy);
                    if (search) queryParams.append("search", search);

                    const url = `https://banhang.khoweb.top/store/api/get_all_products?${queryParams.toString()}`;
                    loadProducts(url, false, categoryId, sortBy, search);
                }, 500); // 500ms sau khi ngừng gõ mới gọi API
            });
        }


        // Attach events for tabs
        tabs.forEach(tab => {
            tab.addEventListener("click", async () => {
                if (tab.classList.contains("active")) return;

                tabs.forEach(t => t.classList.remove("active"));
                tab.classList.add("active");

                const baseUrl = "https://banhang.khoweb.top";
                if (tab.textContent.trim() === "{{__('lang.All_products')}}") {
                    await loadCategories();
                    loadProducts(`${baseUrl}/store/api/get_all_products?storeId=${storeId}`, false);
                } else if (tab.textContent.trim() === "{{__('lang.Recommend')}}") {
                    loadProducts(`${baseUrl}/store/api/suggested_products?storeId=${storeId}`, true);
                }
            });
        });

        // Initial load: suggested products
        loadProducts(`https://banhang.khoweb.top/store/api/suggested_products?storeId=${storeId}`, true);

        // Xu ly toggle follow theo api
       const btnFollow = document.getElementById("btn-follow");
        if (!btnFollow) return;

        // Hàm cập nhật giao diện nút
        function updateFollowUI(followed) {
            if (followed) {
                btnFollow.innerHTML = `<span><i class="el-icon-star-on"></i> {{__('lang.Followed')}} </span>`;
                btnFollow.classList.add("is-followed");
            } else {
                btnFollow.innerHTML = `<span><i class="el-icon-star-off"></i> {{__('lang.Follow')}} </span>`;
                btnFollow.classList.remove("is-followed");
            }
        }

        // ✅ 1. Check trạng thái follow khi load trang
        async function checkFollowStatus() {
            try {
                const response = await fetch(`/store/api/check_follow?storeId=${storeId}`, {
                    method: "GET",
                    headers: { "Accept": "application/json" }
                });

                if (!response.ok) throw new Error("API check_follow lỗi!");

                const data = await response.json();   // { followed: true/false }
                updateFollowUI(data.followed);
            } catch (err) {
                console.error("Error when check follow:", err);
            }
        }

        // ✅ 2. Toggle follow khi click nút
        btnFollow.addEventListener("click", async () => {
            try {
                const response = await fetch(`/store/api/toggle_follow?storeId=${storeId}`, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                });
                if (response.status === 401 || response.status === 403) {
                    elementMessage("error", "{{__('lang.Ban_can_dang_nhap_de_thao_tac')}}");
                    return; // dừng lại, không xử lý tiếp
                }

                if (!response.ok) throw new Error("API toggle_follow error!");

                const data = await response.json();  // { followed: true/false, message: "..."}
                updateFollowUI(data.followed);

                // Hiển thị alert
                elementMessage(data.followed ? "success" : "info", data.message );

            } catch (err) {
                console.error("Lỗi khi toggle follow:", err);
                if (err.status === 401 || err.status === 403) {
                    elementMessage("error", "{{__('lang.can_dang_nhap_de_them_san_pham_vao_gio_hang')}}");
                } else {
                    elementMessage("error", "{{__('lang.da_xay_ra_loi_vui_long_thu_lai')}}");
                }
            }
        });

        // Gọi hàm check khi load trang
        checkFollowStatus();


        //  them san pham vao gio hang
        // --- Add to cart (robust) ---
        (function () {
            // Lấy CSRF token (meta hoặc window.App nếu bạn đã inject)
            function getCsrfToken() {
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta && meta.getAttribute('content')) return meta.getAttribute('content');
                if (window.App && window.App.csrfToken) return window.App.csrfToken;
                return null;
            }

            // Lấy quantity: ưu tiên input#quantity, nếu không thì tìm input có class .el-buy-input__inner trong cùng container
            function getQuantityFrom(element) {
                // tìm input#quantity trong document (global)
                const globalQty = document.getElementById('quantity');
                if (globalQty) {
                    const v = parseInt(globalQty.value, 10);
                    return Number.isFinite(v) && v > 0 ? v : 1;
                }

                // tìm input trong cùng block (ví dụ modal) - đi lên tìm container cha có class product-info-right
                const container = element.closest('.product-info-right') || element.closest('.dialog-content') || document;
                if (container) {
                    const localInput = container.querySelector('.el-buy-input__inner, input[name="qty"], input.qty, input[type="number"]');
                    if (localInput) {
                        const v = parseInt(localInput.value, 10);
                        return Number.isFinite(v) && v > 0 ? v : 1;
                    }
                }

                return 1; // fallback
            }

            // An toàn hiển thị thông báo (dùng elementMessage nếu có, else alert)
            function showMessage(type, msg) {
                if (typeof elementMessage === 'function') {
                    elementMessage(type, msg);
                } else {
                    // fallback nhẹ
                    if (type === 'error') alert('Error: ' + msg);
                    else if (type === 'warning') alert('Warning: ' + msg);
                    else alert(msg);
                }
            }

            // Event delegation: bắt click trên .addcartauth (dùng delegation để hỗ trợ element động/modal)
            document.addEventListener('click', async function (ev) {
                const btn = ev.target.closest('.addcartauth');
                if (!btn) return;

                ev.preventDefault();

                const productId = btn.getAttribute('data-product-id') || btn.dataset.productId;
                const userId = btn.getAttribute('data-user-id') || btn.dataset.userId;

                if (!productId) {
                    showMessage('error', '{{__("lang.khong_tim_thay_product_id")}}');
                    return;
                }

                if (!userId) {
                    showMessage('warning', '{{__("lang.can_dang_nhap_de_them_san_pham_vao_gio_hang")}}');
                    return;
                }

                const quantity = getQuantityFrom(btn);

                // ✅ Lấy tất cả thuộc tính đã chọn
                const selectedAttrs = Array.from(
                    document.querySelectorAll('input[name^="variant_"]:checked')
                ).map(el => parseInt(el.value, 10)); // parse về int


                console.log(selectedAttrs);

                const prevDisabled = btn.disabled;
                btn.disabled = true;
                btn.classList.add('is-loading');

                const payload = {
                    id: Number(productId),
                    qty: Number(quantity),
                    attr: JSON.stringify(selectedAttrs)
                };

                const csrf = getCsrfToken();

                try {
                    const res = await fetch('/ajax/add-to-cart', {
                        method: 'POST',
                        credentials: 'same-origin',
                        dataType: 'json',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!res.ok) {
                        let text = `network error: ${res.status}`;
                        try {
                            const t = await res.json();
                            text = t.message || t.error || text;
                        } catch (_) {}
                        showMessage('error', text);
                        return;
                    }

                    const data = await res.json();
                    const ok = data.success === true || data.code === 10 || data.code === 11;

                    document.dispatchEvent(new Event('cart:updated'));

                    if (ok) {
                        showMessage('success', data.message || '{{__("lang.san_pham_da_duoc_them_vao_gio_hang")}}');
                        if (typeof data.cart_count !== 'undefined') {
                            const elCount = document.querySelector('.shop-cart-float-count');
                            if (elCount) elCount.textContent = data.cart_count;
                        }
                    } else {
                        showMessage('warning', data.message || '{{__("lang.khong_the_them_san_pham_vao_gio")}}.');
                    }

                } catch (err) {
                    console.error('{{__("lang.loi_khi_them_gio_hang")}}:', err);
                    showMessage('error', '{{__("lang.da_xay_ra_loi_vui_long_thu_lai")}}!');
                } finally {
                    btn.disabled = prevDisabled;
                    btn.classList.remove('is-loading');
                }
            });
        })();

        // Khi thay đổi số lượng
        document.addEventListener('input', function(e) {
            if (e.target.matches('#visible-qty')) {
                document.getElementById('buy-now-qty').value = e.target.value;
            }
        });

// Khi chọn variant
        document.addEventListener('change', function(e) {
            if (e.target.matches('input[type="radio"][name^="variant_"]')) {
                document.getElementById('buy-now-variant-id').value = e.target.value;
            }
        });

// Khi bấm nút "Mua ngay"
        const buyNowBtn = document.querySelector('.btn-buy-now');
        const buyNowForm = document.getElementById('buy-now-form');

        buyNowBtn.addEventListener('click', function (e) {
            buyNowForm.submit(); // submit form ẩn
        });


    });
</script>

@section('content')
    <div class="wrap app-center">
        <div class="head-wrap">
            {{--         !Xu ly khi ma banner bi null           --}}
            <div class="banner-box {{ $storeData['banner'] ? 'has-banner' : 'no-banner' }}">
                @if($storeData['banner'])
                    <img class="banner"
                         src="{{ CommonHelper::getUrlImageThumb($storeData['banner']) }}"
                         alt="Banner {{ $storeData['ten_cua_hang'] ?? 'TikTok-Wholesale' }}">
                @else
                    <div class="fallback">
                        <img class="store-logo"
                             src="{{ CommonHelper::getUrlImageThumb($storeData['logo_cua_hang'] ?? '') }}"
                             alt="{{ $storeData['ten_cua_hang'] ?? 'TikTok-Wholesale' }}">
                        <p class="store-name">{{ $storeData['ten_cua_hang'] ?? 'TikTok-Wholesale' }}</p>
                    </div>
                @endif
            </div>

            <div class="box">
                <div class="container-left">
                    {{--         !Xu ly khi ma logo bi null           --}}
                    <img src="{{CommonHelper::getUrlImageThumb($storeData['logo_cua_hang'] ??'')}}" alt="{{$storeData['ten_cua_hang'] ?? 'TikTok-Wholesale'}}">
                    <div class="desc" style="display: flex; align-items: center">
                        {{--         !Xu ly khi ma ten cua hang bi null           --}}
                        <p>{{$storeData['ten_cua_hang'] ?? 'TikTok-Wholesale'}}</p>
                        <span>  </span>
                        <div class="img-list">
                        </div>
                    </div>
                </div>
                {{--        XU ly toggle follow        --}}
                <button type="button" class="el-button btn el-button--default" id="btn-follow"   data-store-id="{{ $storeData['id'] }}">
                    <span><i class="el-icon-star-off"></i> {{ __('lang.Follow') }} </span>
                </button>
            </div>
        </div>
        <div class="nav">
            <ul>
                {{--        Trigger goi AJAX Load data (Cache duoc data sau khi load thi cang tot)       --}}
                <li class="active"> {{ __('lang.Recommend') }} </li>
                <li class=""> {{ __('lang.All_products') }} </li>
            </ul>
        </div>
        {{--   nen goi AJAX cho phan nay  --}}
        <div class="app-container store-content"></div>
        {{-- Modal for Buy Now --}}
        <div class="el-dialog__wrapper es-dialog" style="display: none; z-index: 2027;">

        </div>
    </div>
    <script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/product.js') }}"></script>
@endsection
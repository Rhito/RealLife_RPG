@extends('frontend.layout.layout')
<style>
    .list {
        color: black;
    }

    .list-item {
        max-height: 0;
        overflow: hidden;
        color: black;
        transition: max-height 0.3s ease-out;
    }

    .list-item.active {
        max-height: var(--real-height);
        transition: max-height 0.3s ease-in;
    }

    .el-icon-arrow-down {
        transition: transform 0.3s ease;
    }

    .el-icon-arrow-down.rotate {
        transform: rotate(180deg);
    }

    /* ----- PHÓNG TO SẢN PHẨM ----- */
    .product {
        transition: transform 0.25s ease, box-shadow 0.25s ease, border 0.25s ease;
    }

    .product:hover {
        transform: scale(1.05);
        border: 1.5px solid black !important;
    }

    /* ----- MENU DANH MỤC ----- */
    .commodity-filter ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .commodity-filter > ul > li {
        margin: 0;
        padding: 0;
    }

    .commodity-filter > ul > li.commodity-filter-item + li.commodity-filter-item {
        margin-top: 2px;
    }

    /* Tất cả sản phẩm */
    .commodity-filter-item.first-item a {
        display: block;
        padding: 6px 28px 6px 12px;
        font-size: 14px;
        line-height: 1.35;
        color: #333;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .commodity-filter-item.first-item a:hover {
        background: #f5f5f5;
    }

    /* Danh mục cha */
    .commodity-filter-item .list {
        position: relative;
        display: block;
        cursor: pointer;
        padding: 6px 28px 6px 12px;
        font-size: 14px;
        line-height: 1.35;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .commodity-filter-item .list-item {
        margin-top: 0; /* Nếu có margin mặc định */
        padding: 0;
    }


    /* Chỉ cho long-text được xuống dòng */
    .commodity-filter-item.long-text .list {
        white-space: normal;
        word-break: break-word;
        overflow: visible;
        text-overflow: unset;
    }

    /* Danh mục con */
    .commodity-filter-item .list-item ul {
        margin: 0;
        padding: 0;
    }

    .commodity-filter-item .list-item ul li:first-child {
        margin-top: 0;
    }

    .commodity-filter-item .list-item ul li a {
        display: block;
        padding: 1px 28px 1px 20px;
        font-size: 13px;
        line-height: 1.35;
        color: #555;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /*.commodity-filter-item .list + .list-item {*/
    /*    margin-top: -4px;*/
    /*}*/
    /* Hover */
    .commodity-filter-item .list:hover,
    .commodity-filter-item .list-item ul li:hover {
        background: #f5f5f5;
    }

    /* Icon mũi tên */
    .commodity-filter .el-icon-arrow-down {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        margin: 0;
        pointer-events: none;
    }

    .commodity-filter .el-icon-arrow-down::before {
        content: "›";
        font-size: 13px;
        color: #666;
        display: inline-block;
    }

    .commodity-filter .el-icon-arrow-down.rotate {
        transform: translateY(-50%) rotate(90deg);
    }

    /* GRID SẢN PHẨM */
    .commodity-content-list {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }

    .pro-container {
        width: 100%;
    }

    /*phân trang*/
    .common-pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center; /* căn giữa theo chiều ngang */
    }

    .common-pagination .es-pagination {
        display: flex;
        align-items: center; /* căn icon prev/next theo giữa */
        gap: 6px; /* khoảng cách giữa các nút */
    }

    .common-pagination .el-pager {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 6px;
    }

    .sort-btn.active i {
        color: #409EFF; /* xanh kiểu Element UI */
        font-weight: bold;
    }

    .sort-btn.active {
        color: #FF0000; /* #FF0000 kiểu Element UI */
    }

    .commodity-filter-item .list-item ul li a.sub-active {
        color: #FF0000;
    }

    .commodity-filter-item-active, .commodity-filter-item:hover {
        color: #000000 !important;
        border-right: 2px solid #000000 !important;
    }

    @media (max-width: 1200px) {
        .commodity-content-list {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 992px) {
        .commodity-content-list {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .commodity-content-list {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>


@section('content')
    {{--    @include('chat.Frontend.ChatMessage')--}}
    <div class="app-container app-center">
        <div class="commodity-wrap flex-start">
            <div class="filter commodity-filter">
                <h2>{{__('lang.Category')}}</h2>
                <ul>
                    <li class="first-item commodity-filter-item {{ request('category') ? '' : 'commodity-filter-item-active' }}">
                        <a href="{{ route('products') }}">{{ __('lang.All_products') }}</a>
                    </li>
                    @foreach($categories as $category)
{{--                        {{dd($category)}}--}}
                        @php
                            $isLong = in_array($category->name, ['Recreational Fishing Gear','Epidemic Prevention Supplies']);
                        @endphp
                        <li class="commodity-filter-item {{ $isLong ? 'long-text' : '' }}">
                            <div class="list" data-url="{{ route('products', ['category' => $category->id]) }}">
                                {{ $category->name }}
                                @if($category->childs->count())
                                    <i class="el-icon-arrow-down" style="margin-left: 5px;"></i>
                                @endif
                            </div>
                            @if($category->childs->count())
                                <div class="list-item">
                                    <ul>
                                        @foreach($category->childs as $child)
                                            <li>
                                                <a href="{{ route('products', ['category' => $child->id]) }}"
                                                   class="{{ request('category') == $child->id ? 'sub-active' : '' }}">
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="commodity-content">
                <div class="sort commodity-content-title flex-start">
                    <h2 class="sort-btn" data-sort="total"> {{__('lang.Sum_up')}} </h2>
                    <ul class="flex-start">
                        <li class="flex-start">
                            <span class="sort-btn" data-sort="totals">{{__('lang.Total_sales')}}</span>
                            <div class="flex-start">
                                <div class="sort-btn" data-sort="totals" data-type="asc"><i
                                            class="el-icon-caret-top"></i></div>
                                <div class="sort-btn" data-sort="totals" data-type="desc"><i
                                            class="el-icon-caret-bottom"></i></div>
                            </div>
                        </li>
                        <li class="flex-start">
                            <span class="sort-btn" data-sort="price">{{ __('lang.price') }}</span>
                            <div class="flex-start">
                                <div class="sort-btn" data-sort="price" data-type="asc"><i
                                            class="el-icon-caret-top"></i></div>
                                <div class="sort-btn" data-sort="price" data-type="desc"><i
                                            class="el-icon-caret-bottom"></i></div>
                            </div>
                        </li>
                        <li class="flex-start">
                            <span class="sort-btn" data-sort="new">{{ __('lang.New_products') }}</span>
                            <div class="flex-start">
                                <div class="sort-btn" data-sort="new" data-type="asc"><i class="el-icon-caret-top"></i>
                                </div>
                                <div class="sort-btn" data-sort="new" data-type="desc"><i
                                            class="el-icon-caret-bottom"></i></div>
                            </div>
                        </li>
                    </ul><!---->
                </div>

                {{--                --}}
                <div class="">
                    <!---->
                    <div id="product-list">
                        @include('frontend.pages.products.partials.list', ['products' => $products])
                    </div>

                    <!---->
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = document.querySelectorAll('.commodity-filter-item');

            items.forEach(item => {
                const list = item.querySelector('.list');         // text danh mục cha
                const listItem = item.querySelector('.list-item');// submenu
                const icon = item.querySelector('.el-icon-arrow-down');
                const links = item.querySelectorAll('a');         // All products + con

                // --- 1. Click icon chỉ toggle submenu ---
                if (icon && listItem) {
                    icon.addEventListener('click', function (e) {
                        e.stopPropagation();
                        listItem.classList.toggle('active');
                        icon.classList.toggle('rotate');
                    });
                }

                // --- 2. Click cha (text) ---
                if (list && list.dataset.url) {
                    list.addEventListener('click', function (e) {
                        if (e.target.closest('.el-icon-arrow-down')) return;

                        if (listItem) {
                            listItem.classList.toggle('active');
                            if (icon) icon.classList.toggle('rotate');
                        }

                        document.querySelectorAll('.commodity-filter-item')
                            .forEach(el => el.classList.remove('commodity-filter-item-active'));

                        item.classList.add('commodity-filter-item-active');

                        window.history.pushState({}, "", list.dataset.url);
                        loadProducts(list.dataset.url);
                    });
                }

                // --- 3. Click con (hoặc All products) ---
                links.forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Xóa highlight cũ
                        document.querySelectorAll('.commodity-filter-item .list-item ul li a')
                            .forEach(a => a.classList.remove('sub-active'));

                        // Highlight link hiện tại
                        this.classList.add('sub-active');

                        // Reset + set active cha
                        document.querySelectorAll('.commodity-filter-item')
                            .forEach(el => el.classList.remove('commodity-filter-item-active'));

                        item.classList.add('commodity-filter-item-active');

                        // Đổi URL và load
                        window.history.pushState({}, "", link.getAttribute('href'));
                        loadProducts(link.getAttribute('href'));
                    });
                });
            });
        });

    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = Array.from(document.querySelectorAll('.commodity-filter-item'));
            if (!items.length) return;

            // Tìm item đã active trong HTML (nếu có). Nếu có nhiều, giữ item đầu tiên, xóa active ở các item khác.
            let selected = items.find((el) => el.classList.contains('commodity-filter-item-active')) || null;
            if (selected) {
                items.forEach(el => {
                    if (el !== selected) el.classList.remove('commodity-filter-item-active');
                });
                selected.classList.add('commodity-filter-item-active');
            } else {
                // Nếu muốn mặc định chọn item đầu (All Products), bật dòng sau, hoặc để null để không chọn trước
                // selected = items[0];
                // selected.classList.add('commodity-filter-item-active');
            }

            items.forEach(item => {
                // giúp keyboard accessibility (optional)
                if (!item.hasAttribute('tabindex')) item.setAttribute('tabindex', '0');

                // Hover: chỉ thêm class nếu item chưa được chọn (selected)
                item.addEventListener('mouseenter', () => {
                    if (item !== selected) item.classList.add('commodity-filter-item-active');
                });

                // Khi rời chuột: bỏ class nếu item không phải selected
                item.addEventListener('mouseleave', () => {
                    if (item !== selected) item.classList.remove('commodity-filter-item-active');
                });

                // Click: chọn item hiện tại, bỏ chọn item trước đó
                item.addEventListener('click', (e) => {
                    // Nếu click vào link A -> cho phép chạy bình thường
                    if (e.target.tagName === 'A') {
                        return; // KHÔNG preventDefault
                    }

                    if (selected && selected !== item) {
                        selected.classList.remove('commodity-filter-item-active');
                    }
                    selected = item;
                    selected.classList.add('commodity-filter-item-active');
                });

                // Hỗ trợ phím Enter / Space để chọn bằng bàn phím
                item.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        item.click();
                    }
                });
            });
        });

    </script>

    <script>
        // Xử lý SORT
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.sort-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    let sortBy = this.dataset.sort;
                    let sortType = this.dataset.type;

                    // Reset tất cả
                    document.querySelectorAll('.sort-btn').forEach(el => el.classList.remove('active', 'asc', 'desc'));

                    if (!sortType) {
                        // Nếu click chữ
                        if (!this.dataset.current || this.dataset.current === 'desc') {
                            this.dataset.current = 'asc';
                        } else {
                            this.dataset.current = 'desc';
                        }
                        sortType = this.dataset.current;

                        this.classList.add('active');

                        // Tìm icon trong cùng nhóm (li hoặc h2)
                        let wrapper = this.closest('li') || this.closest('h2');
                        let iconBtn = wrapper.querySelector(`.sort-btn[data-type="${sortType}"]`);
                        if (iconBtn) iconBtn.classList.add('active', sortType);
                    } else {
                        // Nếu click icon
                        this.classList.add('active', sortType);

                        let wrapper = this.closest('li');
                        let label = wrapper?.querySelector(`.sort-btn[data-sort="${sortBy}"]:not([data-type])`);
                        if (label) {
                            label.classList.add('active');
                            label.dataset.current = sortType;
                        }
                    }

                    loadProducts(`{{ route('product.filter') }}?sort_by=${sortBy}&sort_type=${sortType}`);
                });
            });
        });


        // // Xử lý PAGINATION (chỉ trong vùng phân trang)
        // document.querySelector('.common-pagination')?.addEventListener('click', function (e) {
        //     const link = e.target.closest('a');
        //     if (!link) return;
        //
        //     e.preventDefault(); // chặn reload
        //     let url = link.getAttribute('href');
        //     loadProducts(url);
        // });

        // Hàm load sản phẩm qua AJAX
        function loadProducts(url) {
            let list = document.getElementById('product-list');
            list.innerHTML = ""; // clear sản phẩm cũ

            // Tạo spinner
            let spinner = new Spinner({
                lines: 12, length: 7, width: 4, radius: 10, color: '#409EFF'
            }).spin(list);

            fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(res => res.text())
                .then(html => {
                    spinner.stop();
                    list.innerHTML = html;
                })
                .catch(() => {
                    spinner.stop();
                    list.innerHTML = '<p style="color:red; text-align:center;">Error loading products</p>';
                });
        }


    </script>

@endsection
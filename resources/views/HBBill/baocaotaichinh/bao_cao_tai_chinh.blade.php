@extends(config('core.admin_theme').'.template')
@section('main')
    <style>
        /* Card thống kê */
        .card-stat {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .card-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }
        .card-stat h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: #111827;
        }
        .card-stat p {
            margin: 0;
            font-size: 0.9rem;
            color: #6b7280;
        }

        /* Thanh màu nhấn trên card */
        .card-stat::before {
            content: "";
            display: block;
            height: 4px;
            border-radius: 12px 12px 0 0;
            margin: -20px -20px 15px -20px;
        }
        .card-blue::before   { background: #3b82f6; }
        .card-lightblue::before { background: #06b6d4; }
        .card-green::before  { background: #10b981; }
        .card-gray::before   { background: #6b7280; }
        .card-orange::before { background: #f59e0b; }
        .card-red::before    { background: #ef4444; }

        /* Table block */
        .table-wrapper {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            padding: 20px;
            margin-top: 25px;
            border: 1px solid #e5e7eb;
        }

        /* Table */
        .table-custom {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            font-size: 0.9rem;
        }
        .table-custom th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            text-align: center;
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        .table-custom td {
            padding: 14px;
            text-align: center;
            border-top: 1px solid #f3f4f6;
            color: #374151;
        }
        .table-custom tbody tr:hover {
            background: #f9fafb;
            transition: 0.2s;
        }

        /* Bo góc header & footer */
        .table-custom thead tr:first-child th:first-child {
            border-top-left-radius: 12px;
        }
        .table-custom thead tr:first-child th:last-child {
            border-top-right-radius: 12px;
        }
        .table-custom tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }
        .table-custom tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        /* Styling cho số liệu */
        .table-custom .text-success { color: #10b981 !important; font-weight: 600; }
        .table-custom .text-danger { color: #ef4444 !important; font-weight: 600; }
        .table-custom .badge {
            font-size: 0.8rem;
            border-radius: 999px;
            padding: 6px 12px;
            background: #e5e7eb;
            color: #374151;
        }
        .table-custom .badge.badge-red {
            background: #fee2e2;
            color: #ef4444;
        }
        .table-custom .badge.badge-green {
            background: #d1fae5;
            color: #10b981;
        }


    </style>

    <div class="mx-5 my-4">
        <!-- Filter -->
        <ul class="nav nav-pills mb-3" id="filterTabs" style="
    background: white;
    border: 5px solid white;
" >
            <li class="nav-item"><a class="nav-link" data-period="yesterday" href="#">{{__('lang.Hom_qua')}}</a></li>
            <li class="nav-item"><a class="nav-link active" data-period="today" href="#">{{__('lang.Hom_nay')}}</a></li>
            <li class="nav-item"><a class="nav-link" data-period="week" href="#">{{__('lang.Tuan_nay')}}</a></li>
            <li class="nav-item"><a class="nav-link" data-period="month" href="#">{{__('lang.Thang_nay')}}</a></li>
            <li class="nav-item"><a class="nav-link" data-period="all" href="#">{{__('lang.Tat_ca')}}</a></li>
        </ul>

        <!-- Stat Cards -->
        <div class="row mb-4" id="statCards">
            <div class="col-md-2"><div class="card-stat card-blue"><h3>$0.00</h3><p>{{__('lang.So_tien_cho_xac_nhan')}}</p></div></div>
            <div class="col-md-2"><div class="card-stat card-lightblue"><h3>$0.00</h3><p>{{__('lang.Total_sales')}}</p></div></div>
            <div class="col-md-2"><div class="card-stat card-green"><h3>$0.00</h3><p>{{__('lang.Tong_loi_nhuan')}}</p></div></div>
            <div class="col-md-2"><div class="card-stat card-gray"><h3>0</h3><p>{{__('lang.Tong_so_don_dat_hang')}}</p></div></div>
            <div class="col-md-2"><div class="card-stat card-orange"><h3>0</h3><p>{{__('lang.Huy_don_hang')}}</p></div></div>
            <div class="col-md-2"><div class="card-stat card-red"><h3>0</h3><p>{{__('lang.So_don_huy')}}</p></div></div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table-custom" id="statTable">
                <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>{{__('lang.Ngay')}}</th>
                    <th>{{__('lang.Tong_so_don_dat_hang')}}</th>
                    <th>{{__('lang.Loi_nhuan_dat_hang')}}</th>
                    <th>{{__('lang.Huy_don_hang')}}</th>
                    <th>{{__('lang.So_don_huy')}}</th>
                </tr>
                </thead>
                <tbody style="background:white">
                <tr><td colspan="6" class="text-center"></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            // Load mặc định "Hôm nay"
            loadStats('today');

            // Click tab
            $('#filterTabs a').click(function(e){
                e.preventDefault();
                $('#filterTabs a').removeClass('active');
                $(this).addClass('active');
                loadStats($(this).data('period'));
            });

            let currentPage = 1;

            function loadStats(period, page = 1) {
                $.ajax({
                    url: "{{ route('bill.bao_cao_tai_chinh') }}",
                    method: 'GET',
                    data: { period: period, page: page },
                    success: function(res) {
                        // Update card
                        $('#statCards .card-blue h3').text('$' + res.pending);
                        $('#statCards .card-lightblue h3').text('$' + res.total);
                        $('#statCards .card-green h3').text('$' + res.profit);
                        $('#statCards .card-gray h3').text(res.total_orders);
                        $('#statCards .card-orange h3').text('$' + res.cancel_orders);
                        $('#statCards .card-red h3').text(res.cancel_count);

                        // Update table
                        let tbody = '';
                        if(res.data.length > 0){
                            res.data.forEach(row => {
                                tbody += `<tr>
    <td><input type="checkbox"></td>
    <td>${row.date}</td>
    <td><span class="fw-semibold">${row.total_orders}</span></td>
    <td><span class="text-success">$${parseFloat(row.profit).toFixed(2)}</span></td>
    <td><span class="text-danger">$${parseFloat(row.cancel_orders).toFixed(2)}</span></td>
    <td><span class="badge bg-secondary">${row.cancel_count}</span></td>
</tr>`;
                            });
                        } else {
                            tbody = '<tr><td colspan="6" class="text-center"></td></tr>';
                        }
                        $('#statTable tbody').html(tbody);

                        // Pagination
                        let pagHtml = '';
                        for(let i=1;i<=res.last_page;i++){
                            pagHtml += `<li class="page-item ${i===res.current_page?'active':''}">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                            </li>`;
                        }
                        $('.pagination').html(pagHtml);
                    }
                });
            }

// Click phân trang
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).data('page');
                currentPage = page;
                let period = $('#filterTabs a.active').data('period');
                loadStats(period, page);
            });

        });
    </script>
@endsection

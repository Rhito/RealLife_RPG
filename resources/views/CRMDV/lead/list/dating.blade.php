@if($item->{$field['name']} != null)
    @php
        $date1 = date_create(@$item->dating);
        $date2 = date_create(date('Y-m-d'));
        $diff = date_diff($date1, $date2);
    @endphp

    <div class="table-date-cell">
        <input type="date"
               name="dating_change"
               class="td-field-{{ $item->id }}"
               value="{{ date('Y-m-d', strtotime($item->dating)) }}">
        @if(strtotime(date('Y-m-d')) > strtotime($item->{$field['name']}))
            <span style="color: red; font-weight: bold;">Trễ {{ $diff->format('%a') }} ngày</span>
        @elseif(strtotime(date('Y-m-d')) == strtotime($item->{$field['name']}))
            <span style="color: green; font-weight: bold;">Đến ngày TT</span>
        @else
            <i style="font-size: 11px;">{!! date('d/m/Y', strtotime($item->{$field['name']})) !!}</i>
        @endif


    </div>
@endif
<style>
    .table-date-cell {
        display: flex;
        flex-direction: column; /* xếp input trên, text dưới */
        align-items: flex-start;
        gap: 4px; /* khoảng cách giữa input và text */
        padding: 2px 0;
    }

    .table-date-cell input[type="date"] {
        width: 110px;
        font-size: 12px;
        padding: 3px 6px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #fff;
    }

    .table-date-cell .status {
        font-size: 12px;
        line-height: 1.3;
    }

    .table-date-cell .status.overdue {
        color: red;
        font-weight: bold;
    }

    .table-date-cell .status.today {
        color: green;
        font-weight: bold;
    }

    .table-date-cell .status.future {
        color: #555;
        font-style: italic;
    }
</style>
<script type="text/javascript">
	$('.td-field-{{ $item->id }}').change(function() {
		var dating = $(this).val();
		console.log(dating, '{{ $item->id }}');

		$.ajax({
			url: '/admin/lead/ajax-update',
			type: 'POST',
			data: {
				data: {
					dating: dating
				},
				id: '{{ $item->id }}'
			},
			success: function() {
				location.reload();
			},
			error: function() {
				console.log('Có lỗi xảy ra, vui lòng load lại trang và thử lại!');
			}
		});
	});

	
</script>
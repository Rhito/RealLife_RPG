@if(@$item->{$field['name']} == 'Đang tìm hiểu')
    <span class="status-badge status-dang-tim-hieu">
        {{ $item->{$field['name']} }}
    </span>
@elseif(@$item->{$field['name']} == 'Quan tâm cao')
    <span class="status-badge status-quan-tam-cao">
        {{ $item->{$field['name']} }}
    </span>
@elseif(@$item->{$field['name']} == 'Không có nhu cầu')
    <span class="status-badge status-khong-nhu-cau">
        {{ $item->{$field['name']} }}
    </span>
@else
    <span class="status-default">
        {!! $item->{$field['name']} !!}
    </span>
@endif
<style>
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        color: #fff;
    }
    .status-default{
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        color: #000;
    }

    /* Giữ nguyên màu nền như code ban đầu */
    .status-dang-tim-hieu {
        background: #28a745; /* xanh */
    }
    .status-quan-tam-cao {
        background: #c82333; /* đỏ */
    }
    .status-khong-nhu-cau {
        background: #6c757d; /* xám */
    }
</style>
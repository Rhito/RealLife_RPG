@if($item->tinh_trang_thanh_toan == 'chua_thanh_toan')
    {{ __('lang.' . $item->tinh_trang_thanh_toan) }}
@elseif($item->tinh_trang_thanh_toan == 'da_thanh_toan')
    {{ __('lang.' . $item->tinh_trang_thanh_toan) }}
@else
    hum
@endif




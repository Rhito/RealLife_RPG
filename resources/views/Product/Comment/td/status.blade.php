

@if($item->danh_gia == 'danh_gia_tot')
    {{ __('lang.' . $item->danh_gia) }}
@elseif($item->danh_gia == 'chua_tot')
    {{ __('lang.' . $item->danh_gia) }}
@else
    hum
@endif




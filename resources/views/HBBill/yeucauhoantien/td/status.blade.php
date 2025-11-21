@if($item->status == 'chua_duyet')
    {{ __('lang.' . $item->status) }}
@elseif($item->status == 'duyet')
    {{ __('lang.' . $item->status) }}
@elseif($item->status == 'tu_choi')
    {{ __('lang.' . $item->status) }}
@else
    hum
@endif




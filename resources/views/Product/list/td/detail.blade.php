
<?php
$orderDetail = \App\Modules\Alcohol\Models\OrderDetail::with('product')->where('order_id', $item->id)->get();
?>

{{--@foreach($orderDetail as $item)--}}
{{--    <div style="white-space: nowrap">{{ $item->product->name }} - x{{ $item->quantity }}</div>--}}
{{--@endforeach--}}
@foreach($orderDetail as $item)
    @if($item->product)  {{-- Kiểm tra sản phẩm tồn tại --}}
    <div style="white-space: nowrap">{{ $item->product->name }} - x{{ $item->quantity }}</div>
    @else
        <div style="white-space: nowrap">{{__('lang.No_data_found')}} - x{{ $item->quantity }}</div>
    @endif
@endforeach


@if(!empty($item->product))
    <image src="{{ CommonHelper::getUrlImageThumb($item->product->image) ??'' }}" style="width:80px"></image>
    
@endif


@if($item->product && $item->product->category)
    @php $cate = $item->product->category; @endphp

    @if($cate->parent)
        {{ $cate->parent->name }} 
    @else
        {{ $cate->name }}
    @endif
@else
    <span class="text-muted">{{__('lang.No_category')}}</span>
@endif
@php  $totalPrice = 0; @endphp
<ul style="padding: 0; margin: 0;">
    @foreach(\Modules\ThemeSTBD\Models\Product::whereIn('id', explode('|', $item->gift_list))->pluck('name') as $product_name)
        <li>
            {{ $product_name }}

        </li>
    @endforeach
</ul>
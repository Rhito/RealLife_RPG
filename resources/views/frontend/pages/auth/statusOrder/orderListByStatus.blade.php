@if($products->isEmpty())
    <div class="el-empty">
        <div class="el-empty__image">
            <svg viewBox="0 0 79 86" xmlns="http://www.w3.org/2000/svg">
                <!-- SVG không có dữ liệu -->
            </svg>
        </div>
        <div class="el-empty__description">
            <p>{{__('lang.no_data_found')}}</p>
        </div>
    </div>
@else
    @foreach($products as $bill)
        <div class="bg-white shadow-md rounded-lg mb-6 p-5">
            <div class="mb-4 border-b pb-3 flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-lg">
                        {{__('lang.order_code')}}: <span>{{ $bill->ma_don_hang }}</span>
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{__('lang.purchase_date')}}: {{ $bill->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-sm">
                        {{__('lang.status')}}:
                        <span class="font-semibold
                            @if($bill->trang_thai_mua_hang === 'da_nhan_hang_thanh_cong') text-green-600
                            @elseif($bill->trang_thai_mua_hang === 'huy_don_hang') text-red-600
                            @else text-yellow-600 @endif">
                            {{ $bill->trang_thai_mua_hang }}
                        </span>
                    </p>
                    <p class="text-sm">
                        {{__('lang.total_amount')}}: <span class="font-bold">{{ number_format($bill->tong_tien, 0, ',', '.') }} đ</span>
                    </p>
                </div>

                <div>
                    <button onclick="showBillDetail({{ $bill->id }}); loadChatMessages({{ $bill->id }});" class="px-4 py-2 rounded-lg"
                            style="color: black; background-color: white; border: 1px solid black;"
                            onmouseover="this.style.backgroundColor='black'; this.style.color='white';"
                            onmouseout="this.style.backgroundColor='white'; this.style.color='black';">
                        {{__('lang.view_details_contact')}}
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-3 border">{{__('lang.image')}}</th>
                        <th class="py-2 px-3 border">{{__('lang.product')}}</th>
                        <th class="py-2 px-3 border">{{__('lang.color')}}</th>
                        <th class="py-2 px-3 border">{{__('lang.size')}}</th>
                        <th class="py-2 px-3 border">{{__('lang.quantity')}}</th>
                        <th class="py-2 px-3 border">{{__('lang.price')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bill->billItems as $item)
                        @php
                            $product = optional($item->productDetail)->product;
                            $image = optional($product)->image;

                            $productDetail = $item->productDetail;
                            $attribute = $productDetail->attribute ?? '';
                            $parentName = strtolower($attribute->parent->name ?? '');

                            $color = '-';
                            $size = '-';

                            if ($parentName !== '') {
                                if (strpos($parentName, 'màu') !== false) {
                                    $color = $attribute->name ?? '-';
                                } elseif (strpos($parentName, 'size') !== false || strpos($parentName, 'kích') !== false) {
                                    $size = $attribute->name ?? '-';
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-3 border text-center">
                                @if($image)
                                    <img src="{{ asset('filemanager/userfiles/' . $image) }}"
                                         alt="Ảnh sản phẩm" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 flex items-center justify-center bg-gray-100 text-gray-400 rounded">
                                       {{__('lang.no_image')}}
                                    </div>
                                @endif
                            </td>
                            <td class="py-2 px-3 border">{{ $productDetail->product->name ?? __('lang.undefined') }}</td>
                            <td class="py-2 px-3 border text-center">{{ $color }}</td>
                            <td class="py-2 px-3 border text-center">{{ $size }}</td>
                            <td class="py-2 px-3 border text-center">{{ $item->quantity }}</td>
                            <td class="py-2 px-3 border text-right">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

@endif

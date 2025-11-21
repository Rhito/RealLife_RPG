@extends('frontend.layout.layout')
@section('content')
<div data-v-6684c942="" class="main-daily app-container" style="    margin-top: 26px;">
    <div class="main-daily-content" style="
                  translate: none;
                  rotate: none;
                  scale: none;
                  opacity: 1;
                  visibility: inherit;
                  transform: translate(0px, 0px);
                  ">
        @forelse($products as $product)
            @php
                $url = CommonHelper::writeUrl($product->slug, true, true);
            @endphp

            <div class="pro-container">
                <div class="product">
                    <div>
                        <a href="{{ $url }}" style="text-decoration: none; color: inherit;">
                            <div class="poster">
                                <img src="{{ asset('filemanager/userfiles/'.$product->image) }}"
                                     alt="{{ $product->name }}">
                            </div>
                            <h2>${{ $product->price  }}</h2>
                            <div class="product-res">
                                {{ __('lang.Sold') }} {{ $product->total_sold ?? 0 }}
                            </div>
                            <p>{{ $product->name}}</p>
                        </a>
                    </div>
                    <div class="product-footer">
                        <div class="buy-btn-wrapper"
                             data-name="{{ $product->name }}"
                             data-price="{{ number_format($product->price, 2) }}"
                             data-img="{{ asset('filemanager/userfiles/'.$product->image) }}"
                             data-album="{{ $product->image_extra }}"
                             style="cursor:pointer; display:flex; align-items:center; gap:8px;">
                            <i class="el-icon-shopping-cart-full"></i>
                            <span class="buy-btn">{{ __('lang.Purchase_Now') }}</span>
                        </div>


                        <div class="favorite" data-product-id="{{ $product->id }}"><i class="el-icon-star-off"></i></div>
                    </div>
                </div>
                {{-- giữ nguyên modal --}}
                <div class="el-dialog__wrapper es-dialog" independent-modal="true" style="display: none">
                    <div role="dialog" aria-modal="true" aria-label="dialog" class="el-dialog el-dialog--center"
                         style="margin-top: 15vh">
                        <div class="el-dialog__header">
                            <div class="dialog-title">
                                <span>Add to the shopping cart</span>
                            </div>
                            <button type="button" aria-label="Close" class="el-dialog__headerbtn">
                                <i class="el-dialog__close el-icon el-icon-close"></i>
                            </button>
                        </div>
                        <div class="el-dialog__footer"><span></span></div>
                    </div>
                </div>
            </div>
        @empty

            <div class="pro-container">
                <div class="product">
                    <div>
                        <div class="poster">
                            <img src="https://mall-test.s3.amazonaws.com/test/2023-03-28/f97b477d-9c6f-4495-bc3d-54d30a88d4ed.jpg"/>
                        </div>
                        <h2>$57.86</h2>
                        <div class="product-res">Sold 4,961</div>
                        <p>
                            Blue Yeti Nano Premium USB Microphone for PC， Mac， Gaming...
                        </p>
                    </div>
                    <div class="product-footer">
                        <div>
                            <i class="el-icon-shopping-cart-full"></i><span
                                    class="buy-btn"> Purchase Now </span>
                        </div>
                        <div class="favorite" data-product-id="{{ $product->id }}"><i class="el-icon-star-off"></i></div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    <div class="pagination-wrapper" style="display:flex; justify-content:center; margin-top:20px;">
        {{ $products->links('vendor.pagination.custom') }}
    </div>

</div>
@endsection
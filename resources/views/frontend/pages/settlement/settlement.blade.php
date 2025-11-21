@extends('frontend.layout.layout')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        .el-input-number--mini .el-input-number__decrease, .el-input-number--mini .el-input-number__increase {
            height: 26px;
        }
        .el-checkbox__input.is-checked .el-checkbox__inner, .el-checkbox__input.is-indeterminate .el-checkbox__inner {
            background-color: #000;
            border-color: #000;
        }
        .el-checkbox__input.is-checked+.el-checkbox__label {
            color: #000;
        }
        span.settlement-old-price {
            font-size: 11px;
            font-weight: 400;
            color: #646464;
            text-decoration: line-through;
        }
        .select2-dropdown {
            z-index: 2045;
        }
        .select2-container .select2-selection--single {
            height: 40px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50%;
            transform: translateY(-50%);
        }
        .check_address .el-form-item {
            flex: 1; /* mỗi item sẽ tự động chiếm diện tích bằng nhau */
        }
        .check_address .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="settlement">
        <div class="settlement-content app-container">
            <h1 class="title">{{__('lang.Dat_don_hang')}}</h1>
            <h2 class="subtitle">{{__('lang.recipient_address')}}</h2>
            <div class="settlement-receiver flex-between">
                <div>
                    <div class="userinfo"><span>  {{__('lang.add_new_address')}} + </span></div>
                    <p class="address"></p>
                </div>
                <div><i class="el-icon-arrow-right"></i></div>
            </div>
            @php
                $productAmount = 0;
                $discountSum = 0;
                $taxation = 0;
                $shipping = 0;
                $sum = 0;
            @endphp
            <div class="settlement">
                <input type="hidden" name="settlement_card" class="settlement-card" value="{{ $card ?? 0 }}" />
                @if($products && isset($products->items)) {{-- case: lay san pham tu gio hang --}}
                @foreach($products->items as $item)
                    @php
                        $sum++;
                        $productAmount = floatval($productAmount) + (floatval($item->quantity) * floatval($item->price));

                        $discount = floatval($item->gia_giam_gia);
                        $discountSum += $discount;

                    @endphp
                <div class="settlement-commodity">
                    <label class="el-checkbox settlement-commodity-title is-checked">
                        <span class="el-checkbox__input is-checked"><span class="el-checkbox__inner"></span><input type="checkbox" aria-hidden="false" class="el-checkbox__original" value=""></span>
                        <span class="el-checkbox__label">
                              Micro Palace（{{__('lang.Tong')}} <span class="toggle-item-settlement">1</span> {{__('lang.Item')}}） <!---->
                        </span>
                    </label>
                    <div class="settlement-commodity-group">
                        <div role="group" aria-label="checkbox-group" class="el-checkbox-group">
                            <div class="settlement-commodity-wrap">
                                <label class="el-checkbox settlement-commodity-checkbox flex-between is-checked">
                                    <span class="el-checkbox__input is-checked"><span class="el-checkbox__inner"></span><input type="checkbox" aria-hidden="false" class="el-checkbox__original" value="1758611437920"></span>
                                    <span class="el-checkbox__label">
                           <div class="settlement-commodity-item flex-start">
                              <img src="{{ CommonHelper::getUrlImageThumb($item->product->image) }}" alt="">
                              <div class="flex-start settlement-commodity-info" style="flex-direction: column; align-items: flex-start;">
                                 <div>
                                    <h2 title="{{ $item->product->name }}">{{ $item->product->name }}</h2>
                                    <p class="settlement-price"> {{ CommonHelper::convertPrice($item->price) }} @if($item->old_price)<span class="settlement-old-price">{{ CommonHelper::convertPrice($item->old_price) }}</span>@endif</p>
                                     <input type="hidden" name="settlement_product_price[{{ $item->product->id }}][]" class="settlement-product-price" value="{{ $item->product->price }}" />
                                     <input type="hidden" name="settlement_product_shop_price[{{ $item->product->id }}][]" class="settlement-product-shop-price" value="{{ $item->price ?? 0 }}" />
                                     <input type="hidden" name="settlement_product_item_discount[{{ $item->product->id }}][]" class="settlement-product-item-discount" value="{{ $item->gia_giam_gia ?? 0 }}" />

                                 </div>
                                 @if(!empty($item->selected_attributes))
                                 <div class="settlement-commodity-info-attr" data-attr="12"><span>{{__('lang.so_chi_ro')}}:</span><span> {{ $item->selected_attributes }} </span></div>
                                 @endif
                              </div>
                           </div>
                        
                        </span>
                        </label>
                        <div class="el-input-number el-input-number--mini">
                            <span role="button" class="el-input-number__decrease"><i class="el-icon-minus"></i></span><span role="button" class="el-input-number__increase"><i class="el-icon-plus"></i></span>
                            <div class="el-input el-input--mini">
                                <!----><input type="text" value="{{ $item->quantity }}" autocomplete="off" max="9999" min="1" class="el-input__inner productNumber productSettlementNumber" name="settlement_product_qty[{{ $item->product->id }}][]" data-product-id="{{ $item->product->id }}" data-product-detail-id="{{ $item->product_detail_id ?? '' }}" data-cart-id="{{ $item->cart_id }}" role="spinbutton" aria-valuemax="9999" aria-valuemin="1" aria-valuenow="3" aria-disabled="false"><!----><!----><!----><!---->
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else {{-- case: mua ngay ko save vao gio hang use submit form --}}
                    @foreach($products as $id => $item)
                        @php
                            $sum++;
                            $productAmount = floatval($productAmount) + (floatval($item['qty']) * floatval($item['price']));
                            $discount = floatval($item['discount']);
                            $discountSum += $discount;

                        @endphp
                        <div class="settlement-commodity">
                            <label class="el-checkbox settlement-commodity-title is-checked">
                                <span class="el-checkbox__input is-checked"><span class="el-checkbox__inner"></span><input type="checkbox" aria-hidden="false" class="el-checkbox__original" value=""></span>
                                <span class="el-checkbox__label">
                              Micro Palace（{{__('lang.Tong')}} <span class="toggle-item-settlement">1</span> {{__('lang.Item')}}） <!---->
                        </span>
                            </label>
                            <div class="settlement-commodity-group">
                                <div role="group" aria-label="checkbox-group" class="el-checkbox-group">
                                    <div class="settlement-commodity-wrap">
                                        <label class="el-checkbox settlement-commodity-checkbox flex-between is-checked">
                                            <span class="el-checkbox__input is-checked"><span class="el-checkbox__inner"></span><input type="checkbox" aria-hidden="false" class="el-checkbox__original" value="1758611437920"></span>
                                            <span class="el-checkbox__label">
                           <div class="settlement-commodity-item flex-start">
                              <img src="{{ CommonHelper::getUrlImageThumb($item['image']) }}" alt="">
                              <div class="flex-start settlement-commodity-info" style="flex-direction: column; align-items: flex-start;">
                                 <div>
                                    <h2 title="{{ $item['name'] }}">{{ $item['name'] }}</h2>
                                    <p class="settlement-price"> {{ CommonHelper::convertPrice($item['price']) }} @if($item['old_price'])<span class="settlement-old-price">{{ CommonHelper::convertPrice($item['old_price']) }}</span>@endif</p>
                                     <input type="hidden" name="settlement_product_price[{{ $item['id'] }}][]" class="settlement-product-price"  value="{{ $item['price'] }}" />
                                     <input type="hidden" name="settlement_product_item_discount[{{ $item['id'] }}][]" class="settlement-product-item-discount" value="{{ $discount }}" />

                                 </div>
                                 @if(count($item['attributes']))
                                      <div class="settlement-commodity-info-attr" data-attr="12">
                                          <span>{{__('lang.so_chi_ro')}}:</span>
                                          <span>
                                              @php
                                                  $attr = '' ;
                                                  foreach($item['attributes'] as $key => $it){
                                                      $attr .= $it['parent_name'];
                                                      if(isset($it['children'][0])){
                                                          $attr .= ': '.$it['children'][0]['name'];
                                                      }
                                                      if($key < count($item['attributes']) - 1){
                                                          $attr .= ' - ';
                                                      }
                                                  }
                                              @endphp
                                              {{ $attr }}
                                          </span>
                                      </div>
                                 @endif
                              </div>
                           </div>

                        </span>
                                        </label>
                                        <div class="el-input-number el-input-number--mini">
                                            <span role="button" class="el-input-number__decrease"><i class="el-icon-minus"></i></span><span role="button" class="el-input-number__increase"><i class="el-icon-plus"></i></span>
                                            <div class="el-input el-input--mini">
                                                <!----><input type="text" value="{{ $item['qty'] }}" autocomplete="off" max="9999" min="1" class="el-input__inner productNumber productSettlementNumber" name="settlement_product_qty[{{ $item['id'] }}][]" data-product-id="{{ $item['id'] }}" data-product-detail-id="{{$item['id_detail']}}" role="spinbutton" aria-valuemax="9999" aria-valuemin="1" aria-valuenow="3" aria-disabled="false"><!----><!----><!----><!---->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="order-sum">
                <h1>{{__('lang.Tong_so_don_dat_hang')}}</h1>
                <ul class="order-sum-content">
                    @php                    
                        $total = floatval($productAmount) - floatval($discountSum) + floatval($taxation) + floatval($shipping);
                    @endphp
                    <li class="flex-between">
                        <span>{{__('lang.So_tien_san_pham')}}</span>
                        <span class="settlement-product-amount"> {{ CommonHelper::convertPrice($productAmount) }} </span>
                        <input type="hidden" name="settlement_product_amount" value="{{ $productAmount }}" />
                    </li>
                    <li class="flex-between">
                        <span>{{__('lang.giam_gia')}}</span>
                        <span class="settlement-product-discount"> -{{ CommonHelper::convertPrice($discountSum) }} </span>
                        <input type="hidden" name="settlement_product_discount" value="{{ $discountSum }}" />
                    </li>
                    <li class="flex-between">
                        <span>{{__('lang.thue')}}</span>
                        <span class="settlement-product-taxation"> +{{ CommonHelper::convertPrice($taxation) }} </span>
                        <input type="hidden" name="settlement_product_taxation" value="{{ $taxation }}" />
                    </li>
                    <li class="flex-between">
                        <span>{{__('lang.Freight')}}</span>
                        <span class="settlement-product-shipping"> +{{ CommonHelper::convertPrice($shipping) }} </span>
                        <input type="hidden" name="settlement_product_shipping" value="{{ $shipping }}" />
                    </li>
                    <li class="total flex-between">
                        <span>{{__('lang.Tong')}}</span>
                        <span class="settlement-product-total"> {{ CommonHelper::convertPrice($total) }} </span>
                        <input type="hidden" name="settlement_product_total" value="{{ $total }}" />
                    </li>
                </ul>
            </div>
            <div class="pay-method">
                <h1>{{__('lang.Payment_Methods')}}</h1>
                <ul class="pay-method-content">
                    <li class="pay-method-item flex-between">
                        <div class="pay-method-item-left flex-start">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFgAAABYCAMAAABGS8AGAAAAY1BMVEVHcEz3lwD/nwD0lQD4mAD5mgD3lwD3lwD4mQD3lwD4mQD4mgD3lwD5mwD2mAD4mQD4mAD2mAD2lgD4mQD5mgD4mAD6mgD5mQD4mQD4mQDvnwD5mQD4mgD5mgD6mwD5mQD4mQAlW4PwAAAAIHRSTlMAQBAw35+gIN+A779gf5Bwv7Bwr8+QMO/PjxCvb6+PUJwq9wsAAAFvSURBVFjD7ZjJcoMwEEQbsBgJHO97tv7/r8xBCgaRygENrrJLfeepC82opgfIysp6ORVflko6GOmw5Z6q+kWLpbKsAEDZcRfJ6sglgGvwX6hcVu1pV0D8EZVWHYQfW2J3/ymaZIeWJEWzdiuSbNGQNLpd4UieoW4YOJEkSFrtRrYBvNEGfwaw0QbXTws+OGVdAngWZfBjwFtR1nvuvAzO4AzO4OcHL100SspuqQF+C0FjMG+7dHBBkoN4YXrT5XSw2Hj6Xfp8kAo2sWFp+idNBn/07XWjdiOp4KG9cJXkOrnchvYQsqJJruPIXgjN/ag4DVwx/sSQ5Hdq50lsb1hp08GGJIt/a3oSuBjZW40j6BTw6U97TuF1W8WrArGsH/0eb7XB7dwhnZUuV0gSzfhSU2VInv3qplI3vIGbZ9m0xo265MqvxyS0pNpCz3jaEcCtW0HaZA1WkLMtTQGpdbn7+yQj9UWLujgWyMrKejn9AKvyu+IsWHpNAAAAAElFTkSuQmCC" alt="">
                            <p class="name"><span>{{__('lang.account_balance_usdt')}}</span> ( <span class="amount">$0.00</span> ) </p>
                        </div>
                        <label role="radio" aria-checked="true" tabindex="0" class="el-radio is-checked"><span class="el-radio__input is-checked"><span class="el-radio__inner"></span><input type="radio" aria-hidden="true" tabindex="-1" autocomplete="off" class="el-radio__original" value="1"></span><span class="el-radio__label">1</span></label>
                    </li>
                    <li class="pay-method-item flex-between">
                        <div class="pay-method-item-left flex-start">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAgCAMAAAA7dZg3AAABxVBMVEVHcEwEo+cDoOQQpeYFnuT///8FpOcFMo0HMYwHMY2ZzMwDoeUHpOUbQ5V///9/398CoOQGo+UIpeYLpOYAp+UMNo8+we8HpOYIoOQGoeUCouRAv+oJN48etPAYqOfMzMwELYoHM40PpeUbRpkLPJMbSJ0FMY0ALov///8XQJQHNY8HNI8Ysu0An+QRO5EYR5cINY4GMo2qqqoCL4sPNo9ph7QIM40INY4ZPJECfcgLNY9/f/8MpeYSPJKqxuMDnuMEoeY7xOtwz+8AnuQZR5gJNo83YaQUP5Oqqv8GMY0JMow4aKcALYoXPJIINI43X6p/v78Ljs4QpugeUqARQZR/zOUAMIwAn+QAIG0AMIsAnuQAIW4AL4sAIW0An+UAI3MALooAoOUAL4oAJXIAOIMAJHMAJXUAIG4AH20AfcQAPokAXqcAS5YAWaEAkdcALHgAmN0AWq0Ab7cAesIAk9gAgMkAJHEAbLsAInEALIUALIYAJXYAOpEAKncAN4MBMowAKoEAJXcAKHsAJ3sAldsAInIAh84ARJsAL4kAV6oAmN4AOYQAOZIALosAPYcAaroAe8IATZgAJnkAKnYAneIAKX8AK4XJLBzLAAAAVnRSTlMAdPZ9xQGg68HBBfSzVAQI0eR6pR2wIc2G5twYhxFJBePDi3GnOcr9AmS66iv8pDbe9gP4lxHTtzP4twK4fAnA5w0Q/VLRKn0DsXYg+k3iMwSisTtKCgDfksoAAAGuSURBVBgZBcE9a14FAAbQ897n3nw0qQlFCGaIEB0UKloHEQQHXRz9b/4MURwcXNRB1LmI2KkGagZLaIlikve9eXI9Z4UPl8crWPDa7mYZzmYYMZye0jRSTR59sbs8YcX74xtNpUGqkSfbPxvILJKqKuhbBwxsHqGSSFNRHT838HRTopAGqQeLgYOmglKJNm4nI2ui3rxYNiBbq4dZfW/Fe29D3/kdMN2Vg+fj4OOA3IkpmCseftrRzuk61VyrWRSd7n6ZBtu3NuTdZ5jSTsi8MNhU1OU+09zJjH7wK4OhiO1LZmbC3g1Gt2noEAWTntw+TjN8ti0l6zJJzHdH/56rDnlVg3tqNjjcezC9kMi4vkqwe7Pv+P6fw7LTF2vKj+OahuPtjTPSC6TkeQ6PLMvQnb/WNXcuLPhuNdxoIltrCgh+Gq/GSRrbd4AovhyPLoy3Gl5/KUW498383/H5M8aPruH+3t8l/eH68OU/nMN43WjmP4C7y0sAI8QQ0n67AcBAsXVF2Z8BMH4Nzk5I3QLAuAaRlgUABmDUkH0AGMEnKym/vQIAI1j6FZw8BYD/AdhpyvwmsXrWAAAAAElFTkSuQmCC" alt="">
                            <p class="name"><span>PayPal</span> ( <span class="not-bind">{{__('lang.This_payment_method_is_not_open_to_your_region')}}</span> ) </p>
                        </div>
                        <label role="radio" aria-disabled="true" tabindex="-1" class="el-radio is-disabled"><span class="el-radio__input is-disabled"><span class="el-radio__inner"></span><input type="radio" aria-hidden="true" disabled="disabled" tabindex="-1" autocomplete="off" class="el-radio__original" value="2"></span><span class="el-radio__label">2</span></label>
                    </li>
                    <li class="pay-method-item flex-between">
                        <div class="pay-method-item-left flex-start">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAgCAMAAABNTyq8AAAB2lBMVEVRhbPk7PNlk7yGqsqNsM7/4LX/5cH/////pCUATY9UiLTx9fj+/v7R3+sgY5280OEiZZ4kZp/X4+7W4+2Mr80lZ5/w9fgHUpIBTpBWibVRhbLf6fHS4OuaudMBTo+7z+G6zuFql73+//++0eJjkrve6PHZ5e9plr2mwNhdjbfd5/C80eJej7h4oMPh6vIzcKX09/pKgbC/0eOPsc4paqJYirX8/f4/eKoZXpoTWpfT4OxgkLoJU5M9eKpEfK3z9/oQWZb1+PtSh7MDT5G90eKfvNT5+/1MgrEiZZ/l7fQUW5iRs89xnMGFqspQhbKDp8hyncF1nsLp8PVGfq4SWpf6+/290eOjv9cMVpQsbKJNg7B7osXZ5e4zcaWlwdgYXZn7/P0wbqT9/v5bjbfm7vV6osXb5u9nlbxgkLmUtNAbYJt7o8UGUZIKVJMFUZE6dql0nsK2zeAwb6SKrsuXt9FjkrqcutM4dKjf6PFFfq5ajLfV4u1Ce6spaqG5zuB/pcf9/f6Cp8hcjbfe6PCevNS1y972+PsqaqHc5++Mr8zH2OcIU5Pt8/dcjbiCqMlcjLgzcabR3uvP3erm7vT6+/xnlr0mZ5/c5u9XirVtmb+FqsldjrgQWJaCqMh0XZRJAAAAAXRSTlPxilwa7AAAAVZJREFUOMvV1MVTVnEYR/GLVz2e+wbdpWJht6JiB7YgdneC3YHdBbbi/+oCLzPMuPhtOevPzDPzXTzRiIAiAooKAooMaPiikQH9d8zsioVDx4SpM5JkKZC5m9RdTqqPVxwo35RfNC8Lc5NkSlMhRMDsxXYCj71zr9tLR49ZtW35sqJVZIt18qSJA4hW98LTqsrSm3r9mu6BjSdhq9r87xzs9CI8sYf7WnJCtwBw+0jxSucMog366KH5F5kGr1KvnqkFPnq4zVmDaLVWPPA5z/QUbFfz32n0E4csGp+iBXrBaqjRg8Dpl/r1yzfr+27ZkknRdK08X0umy3J2Ab36+8/A2GtmpmiCWgZXtL1j37lXde/1V39DYy53Q3MpGqfzgRrdXajq2w8//Als1mkpGhvHpcC6uGw9a3fsP/v6zbs4/gwsieMSIBoVUDQmoOGLRgcU9jBC+gvK0Uy4fxEWSQAAAABJRU5ErkJggg==" alt="">
                            <p class="name"><span>Visa</span> ( <span class="not-bind">{{__('lang.This_payment_method_is_not_open_to_your_region')}}</span> ) </p>
                        </div>
                        <label role="radio" aria-disabled="true" tabindex="-1" class="el-radio is-disabled"><span class="el-radio__input is-disabled"><span class="el-radio__inner"></span><input type="radio" aria-hidden="true" disabled="disabled" tabindex="-1" autocomplete="off" class="el-radio__original" value="3"></span><span class="el-radio__label">3</span></label>
                    </li>
                    <li class="pay-method-item flex-between">
                        <div class="pay-method-item-left flex-start">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAUCAMAAADSpG8HAAAC91BMVEXtHCT3jBz80ozuMiPvMSP3m57wQkj////6phr5mRv//v77wF33+Pv7rzH+8fH94uTwP0b/+O/83N7uKjLyV13uJSz1io7+7OzxRkz6qyb7u1H7w2XzaW76qyj8zX3+6sr+6cj915n4o6X7tD3p7fT8y3r4jBv++PjQ2ub4z4vy9fjX3+rwPiH2hor3naHuJCPf5e77wmP+68z7szv+9OP+6ML8xGf91JD947fzdHj/+/P+8Nn7qSH7tD/7tUD83N37sDP7uk72j5PvOUHvNT3xS1L7w2TyWmDvO0H4p6ryWF792Z/vOD/7rCr7sTb+7M/94OH2kZX92qH85OXvNj3+4K77sjf++Pn4qa37v3nxTlT6rCj+9ejwQUj3pKf8zoD7wFz96erxQ0KSZoT5srX97e3//PfzWSD8zHqzcIX4r7PbanXwPyKrjKPTHy2+UGPewcv+5bz0cHX+5LrP2eauJz7T3Ofn5eyqU2r+9vbpJzDHk6KrcYf2jZG5kKK8xdbGv87v8vZ6ZYn0ZB/2hB3q7/TFrbz4kBv6qiTsoKbZx6Xs8PW9ua3utlickqzt4OXvtbrq5equmK73l5vwmp+8hZexfZHyTCCanp/uxH3//fr7uUzlqUPn6ev0l5uqscelssHHx8O/v7u4usylg0S5tan/+O391ZO2w9PU0Mj4v2D29/i2sqb11qH+9umps737xWrAjDK2rJj7/P2aqbvRzsd3hpfty5L92JrI0Nqfa4TmjJTDuMfo6+3YyKjX09/Y4Or5mxv1ch70dHr92Z2+sJTtzpnvNSL40I6isMHtHiR6aYy3k1P8+vv6w2ebqbmSgmT+5r/zWx/zXx/84eLv7+v2pBvMrbu4g5fvOEC+donuKyOeOlW/n7DPuqf6wsS1IjfVkp6vhJn5ubyfobr2gx2jtMyrfZShQFrxVl26s8TBobKxrMHpe4P39ffHbHzk6fH1en7tOUCfeJG8rb/z1tnivcWzr8O3xtjv7/Ly5urdqrT9/v5AzHsOAAABu0lEQVQoz2NghwB7JxFnFzYv1gAQR9DbTdnBWErOCCrJACb5LEUZIEBUTJxLUZYDCgxNEIqEBBgQICmBAwF45WGKgkOQ1DA0t3AiqZL0hygKl0BWc1xH5xCSIo7oGLCiOGQ1+oxAgGwURwZYERuyotvMQHAJWZFsGlBRKkORblWtDETNzaecIHDt3BFuqJpe6WKgoqy8ssoa9s7JemyHlW5xvfyo9+mt7r2r54/tUJyjKbdq+UomJqAi1lIelYbuaVMX7z9749Gv119f/f7ww4Cb48zRnVt5Vqxez75BFaQov1y7vnXCRJU9GlfuPv7y7uebPy94lDk092otXLdGYePm2SBFKZkFJXWNGlP65+3SMrj/7Nv7z+oPtC+eOLB97gL5TcvWzuoAWZeYnJ1bUd3UNqlv/u7T1+88+f78of6Fk/u2LeqZMXPJlqVdUoWgIIhFDoLLYN8hB4EkF0hRBLKiUyxAcBBZUTwk7kSQVU1XU2tHVhMWClEk5IOsKicdWY2vAiyp8HsgKYqKRE4qpkiJTkwYqkQ4UJ09yA+qxMZMECllAg3zdHcVkJBREgdxrCykHW3tuM2toZIAE4qJV0+gQL8AAAAASUVORK5CYII=" alt="">
                            <p class="name"><span>MasterCard</span> ( <span class="not-bind">{{__('lang.This_payment_method_is_not_open_to_your_region')}}</span> ) </p>
                        </div>
                        <label role="radio" aria-disabled="true" tabindex="-1" class="el-radio is-disabled"><span class="el-radio__input is-disabled"><span class="el-radio__inner"></span><input type="radio" aria-hidden="true" disabled="disabled" tabindex="-1" autocomplete="off" class="el-radio__original" value="4"></span><span class="el-radio__label">4</span></label>
                    </li>
                </ul>
            </div>
            <div class="settlement-pay flex-between">
                <div class="settlement-pay-label"> {{__('lang.Chon')}} <span class="toggle-item-settlement">{{ $sum }}</span> Pcs </div>
                <button type="button" class="el-button el-button--primary actionOrder">
                    <span><span>{{__('lang.Submit')}}</span><span class="settlement-product-total">{{ CommonHelper::convertPrice($total) }}</span></span>
                </button>
            </div>
            <div class="icon-tips">
                <div class="icon-tips-bottom flex-between">
                    <div class="icon-tips-bottom-item flex-center">
                        <i class="ri-heart-line" style="font-size: 59px"></i><span>{{__('lang.100%_Original')}}</span>
                    </div>

                    <div class="icon-tips-bottom-item flex-center">
                        <i class="ri-skip-back-line" style="font-size: 59px"></i> <span>{{__('lang.7_days_return')}}</span>
                    </div>
                    <div class="icon-tips-bottom-item flex-center">
                        <i class="ri-truck-line" style="font-size: 59px"></i>  <span> {{__('lang.Freight_discount')}} </span>
                    </div>
                    <div class="icon-tips-bottom-item flex-center">
                        <i class="ri-wallet-line" style="font-size: 59px"></i> <span>{{__('lang.Secure_Payment')}}</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Dialog 1: Address Selection --}}
        <div class="el-dialog__wrapper es-dialog" id="addressSelectionDialog" style="display: none; z-index: 2045; background-color: rgba(128, 128, 128, 0.6)">
            <div role="dialog" aria-modal="true" aria-label="dialog" class="el-dialog el-dialog--center" style="margin-top: 15vh; width: 600px;">
                <div class="el-dialog__header">
                    <div class="dialog-title"><span>Choose a shipping address</span></div>
                    <button type="button" aria-label="Close" class="el-dialog__headerbtn" onclick="closeAddressSelectionDialog()"><i class="el-dialog__close el-icon el-icon-close"></i></button>
                </div>
                <div class="el-dialog__body">
                    <div class="address-modal-content dialog-content" style="width: 100%; border: 1px solid var(--color-border); padding: 12px 16px; border-radius: 4px;">
                        @foreach($addresses as $address)
                            <div class="address-active item"
                                 style="width: 100%; border: 1px solid var(--color-border); padding: 12px 16px; border-radius: 4px; cursor: pointer; margin-bottom: 20px;"
                                 data-id="{{ $address->id }}"
                                 data-country-code="{{ $address->quoc_gia_id }}"
                                 data-province-code="{{ $address->thanh_pho_id }}"
                                 data-district-code="{{ $address->quan_huyen_id }}"
                                 data-postal-code="{{ $address->ma_buu_dien }}"
                                 data-address="{{ $address->vi_tri_cu_the }}"
                                 data-phone="{{ $address->sdt }}"
                                 data-email="{{ $address->email }}"
                                 onclick="selectAddress(this)">
                                <div class="info">
                                    <span class="name">{{ $address->ten_nguoi_nhan }}</span>
                                    <span class="mobile">&nbsp;+{{ $address->ma_quoc_gia }} {{ $address->sdt }}</span>
                                </div>
                                <div class="address">
                                    {{ $address->country->country ?? '' }},
                                    {{ $thanh_pho->ascii_name ?? '' }},
                                    @if($address->district) {{ $address->district->ascii_name ?? '' }} @endif
                                    @if($address->vi_tri_cu_the) {{ ','. $address->vi_tri_cu_the ?? '' }} @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Add New address --}}
                        <div class="item" onclick="openAddAddressDialog()"
                             style="width: 100%; border: 1px solid var(--color-border); padding: 12px 16px; border-radius: 4px; cursor: pointer; margin-bottom: 20px;">
                            {{ __('lang.add_new_address') }}
                        </div>
                    </div>

                </div>
                <div class="el-dialog__footer"><span></span></div>
            </div>
        </div>
        {{-- Dialog 2: Add New Address --}}
        <div class="el-dialog__wrapper es-dialog" id="addAddressDialog" style="display: none; z-index: 2043; background-color: rgba(128, 128, 128, 0.6)">
            <div role="dialog" aria-modal="true" aria-label="dialog" class="el-dialog el-dialog--center" style="margin-top: 15vh; width: 600px;">
                <div class="el-dialog__header">
                    <div class="dialog-title"><span>{{__('lang.add_new_address')}}</span></div>
                    <button type="button" aria-label="Close" class="el-dialog__headerbtn" onclick="closeAddAddressDialog()"><i class="el-dialog__close el-icon el-icon-close"></i></button>
                </div>
                <div class="el-dialog__body">
                    <div class="add-address-content">
                        <form class="el-form" id="addressForm">
                            <div class="el-form-item is-required">
                                <div class="el-form-item__content">
                                    <div class="el-input">
                                        <input type="text" autocomplete="off" placeholder="{{__('lang.recipient_name')}}" maxlength="64" class="el-input__inner" id="recipientName">
                                    </div>
                                </div>
                            </div>
                            <div class="el-form-item is-required">
                                <div class="el-form-item__content">
                                    <div class="el-input">
                                        <input type="text" autocomplete="off" placeholder="Email" maxlength="64" class="el-input__inner" id="email">
                                    </div>
                                </div>
                            </div>
                            <div class="el-form-item is-required">
                                <div class="el-form-item__content">
                                    <div class="form-phone">
                                        <div class="el-input">
                                            <input type="text" autocomplete="off" placeholder="{{__('lang.Please_enter_the_mobile_number')}}" maxlength="20" class="el-input__inner" id="phoneNumber">
                                        </div>
                                        <div id="vue_country_intl-4" class="vue-country-popover-container">
                                            <select class="area-code setupSelect2" id="countryCode" style="border: none; margin-left: 4px;">
                                            @if(count($countries))
                                                @foreach($countries as $item)
                                                <option value="{{$item->phone}}">{{$item->phone}}</option>
                                                @endforeach
                                            @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="check_address">
                                <div class="el-form-item is-required">
                                    <div class="el-form-item__content">
                                        <select class="setupSelect2 el-input__inner settlement-address" id="countries" data-target="provinces" data-col="country_code">
                                            <option value="0">-- Country --</option>
                                        @if(count($countries))
                                            @foreach($countries as $item)
                                            <option value="{{ $item->iso }}">{{ $item->country }}</option>
                                            @endforeach
                                        @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="el-form-item is-required">
                                    <div class="el-form-item__content">
                                        <select class="setupSelect2 el-input__inner settlement-address" id="provinces" data-target="districts" data-col="admin1_code">
                                            <option value="0">-- Province --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="el-form-item is-required">
                                    <div class="el-form-item__content">
                                        <select class="setupSelect2 el-input__inner settlement-address" id="districts" data-col="ascii_name">
                                            <option value="0">-- District --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="el-form-item is-required">
                                <div class="el-form-item__content">
                                    <div class="el-input">
                                        <input type="text" autocomplete="off" placeholder="{{__('lang.ma_buu_dien')}}" maxlength="32" class="el-input__inner" id="postalCode">
                                    </div>
                                </div>
                            </div>
                            <div class="el-form-item is-required">
                                <div class="el-form-item__content">
                                    <div class="el-textarea">
                                        <textarea autocomplete="off" placeholder="{{__('lang.detailed_address')}}" rows="4" maxlength="255" class="el-textarea__inner" id="detailedAddress"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="el-form-item">
                                <div class="el-form-item__content">
                                    <div class="flex-between">
                                        <span>{{__('lang.Set_as_the_default_address')}}</span>
                                        {{--                     Button set default                   --}}
                                        <div role="switch" class="el-switch">
                                            <input type="checkbox" name="defaultAddress" id="defaultAddress" true-value="1" false-value="0" class="el-switch__input">
                                            <span class="el-switch__core" style="width: 40px; border-color: rgb(236, 236, 236); background-color: rgb(236, 236, 236);"></span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="el-form-item">
                                <div class="el-form-item__content">
                                    <div class="submit-btn flex-center">
                                        <button type="button" class="el-button el-button--primary" onclick="submitAddressForm()">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // ---- TOGGLE SWITCH ----
            document.querySelectorAll('[role="switch"]').forEach(el => {
                el.addEventListener('click', () => {
                    const isChecked = el.getAttribute('aria-checked') === 'true';
                    el.setAttribute('aria-checked', !isChecked);
                    if (!isChecked) {
                        el.classList.add('is-checked');
                        el.querySelector('.el-switch__core').style.backgroundColor = "var(--color-main)";
                        el.querySelector('.el-switch__core').style.borderColor = "var(--color-main)";
                    } else {
                        el.classList.remove('is-checked');
                        el.querySelector('.el-switch__core').style.backgroundColor = "rgb(236,236,236)";
                        el.querySelector('.el-switch__core').style.borderColor = "rgb(236,236,236)";
                    }
                });
            });

            // ---- OPEN/CLOSE DIALOG ----
            const userinfo = document.querySelector('.userinfo');
            if (userinfo) {
                userinfo.addEventListener('click', () => {
                    document.getElementById('addressSelectionDialog').style.display = 'flex';
                });
            }
            window.closeAddressSelectionDialog = () => {
                document.getElementById('addressSelectionDialog').style.display = 'none';
            }
            window.openAddAddressDialog = () => {
                document.getElementById('addressSelectionDialog').style.display = 'none';
                document.getElementById('addAddressDialog').style.display = 'flex';
            }
            window.closeAddAddressDialog = () => {
                document.getElementById('addAddressDialog').style.display = 'none';
            }

            // ---- VALIDATE FORM ----
            function validateForm() {
                let isValid = true;
                document.querySelectorAll(".el-form-item").forEach(item => {
                    item.classList.remove("is-error");
                    const err = item.querySelector(".el-form-item__error");
                    if (err) err.remove();
                });

                function setError(input, message) {
                    if (!input) return;
                    const formItem = input.closest(".el-form-item");
                    if (!formItem) return;
                    formItem.classList.add("is-error");
                    const errorEl = document.createElement("div");
                    errorEl.className = "el-form-item__error";
                    errorEl.innerText = message;
                    formItem.querySelector(".el-form-item__content").appendChild(errorEl);
                    isValid = false;
                }

                const recipientName = document.querySelector("#recipientName");
                if (!recipientName?.value.trim()) setError(recipientName, "Please enter Recipient name");
                //address
                const settlement_address = document.querySelector("#countries");
                // console.log('settlement_address:' + settlement_address.value);
                if (Number(settlement_address?.value) === 0) setError(settlement_address, "Please enter address");

                const email = document.querySelector("#email");
                const emailVal = email?.value.trim();
                if (!emailVal) {
                    setError(email, "Please enter Email");
                } else {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailVal)) setError(email, "Invalid email format");
                }

                const phone = document.querySelector("#phoneNumber");
                if (!phone?.value.trim()) setError(phone, "Please enter the mobile number");

                const postal = document.querySelector("#postalCode");
                if (!postal?.value.trim()) setError(postal, "Please enter Postal Code");

                const address = document.querySelector("#detailedAddress");
                if (!address?.value.trim()) setError(address, "Please enter Detailed Address");

                const city = document.querySelector("#city");
                if (!city?.value.trim()) setError(city, "Please enter City");

                const country = document.querySelector("#country");
                if(!country?.value.trim()) setError(country, "Please enter Country");

                const provinceStateRegion = document.querySelector("#province_state_region");
                if(!provinceStateRegion?.value.trim()) setError(provinceStateRegion, "Please enter Province State Region");

                return isValid;
            }

            window.submitAddressForm = () => {
                if (validateForm()) {
                    // alert("Form hợp lệ, gửi API được rồi!");
                    submitAddressFrom();
                    closeAddAddressDialog();
                }
            }

            // ---- HANDLE ADDRESS SELECTION ----
            const addressModal = document.querySelector('.address-modal-content');
            if (addressModal) {
                addressModal.addEventListener('click', event => {
                    const target = event.target.closest('.address-active.item');
                    if (target) {
                        const name = target.querySelector('.name')?.textContent || '';
                        const address = target.querySelector('.address')?.textContent || '';
                        document.querySelector('.address').textContent = `${name}, ${address}`;
                        document.querySelector('.userinfo span').textContent = 'Edit Address';
                        closeAddressSelectionDialog();
                    }
                });
            }

            // ---- CLOSE DIALOGS ON OUTSIDE CLICK ----
            document.querySelectorAll('.el-dialog__wrapper').forEach(dialog => {
                dialog.addEventListener('click', event => {
                    if (event.target === dialog) {
                        if (dialog.id === 'addressSelectionDialog') {
                            closeAddressSelectionDialog();
                        } else if (dialog.id === 'addAddressDialog') {
                            closeAddAddressDialog();
                        }
                    }
                });
            });

            document.querySelectorAll('.el-dialog').forEach(dialogContent => {
                dialogContent.addEventListener('click', event => event.stopPropagation());
            });

            // ---- PRODUCT QUANTITY ----
            const input = document.querySelector('.productNumber');
            const btnDecrease = document.querySelector('.el-input-number__decrease');
            const btnIncrease = document.querySelector('.el-input-number__increase');

            if (input && btnDecrease && btnIncrease) {
                btnDecrease.addEventListener("click", () => {
                    let value = parseInt(input.value) || 1;
                    const min = parseInt(input.min) || 1;
                    if (value > min) input.value = value - 1;
                });
                btnIncrease.addEventListener("click", () => {
                    let value = parseInt(input.value) || 1;
                    const max = parseInt(input.max) || 9999;
                    if (value < max) input.value = value + 1;
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/address.js') }}"></script>
    <script type="text/javascript" src="{{ asset(config('core.frontend_asset').'/js/settlement.js') }}"></script>

@endsection
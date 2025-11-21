<div id="variants-wrapper">
    <button type="button" class="btn btn-primary mb-2" onclick="addVariant()">+ {{__('lang.Add_new_attribute')}}</button>



    <?php
    $allAttributes = \App\Modules\Product\Models\Attributes::all();

  

    if (!empty($result)) {
        $productAttributes = \App\Modules\Product\Models\ProductAttributes::where('product_id', $result->id)->get();
    }
    ?>

    <div id="variants-container">
        {{-- Nếu edit, đổ ra các biến thể đã có --}}
        @if(isset($productAttributes) && $productAttributes->count())
            @foreach($productAttributes as $vIndex => $attr)
                <div class="variant-box card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <select name="variants[{{ $vIndex }}][attribute_id]" class="form-control w-25">
                            @foreach($allAttributes as $a)
                                <option value="{{ $a->id }}" {{ $attr->attributes_id == $a->id ? 'selected' : '' }}>
                                    {{ $a->parent_id ? '-- '.$a->name : $a->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="number" name="variants[{{ $vIndex }}][so_luong]"
                               class="form-control w-25 mx-2"
                               value="{{ $attr->so_luong }}" placeholder="{{__('lang.quantity')}}">

                        <input type="file" name="variants[{{ $vIndex }}][images][]" class="form-control w-50" multiple>



                        @if(!empty($attr->image_extral))


                            <div class="mt-1 d-flex gap-2">
                                @foreach(explode('|', $attr->image_extral) as $img)
                                    <img src="{{ asset('filemanager/userfiles/' .$img) }}" height="40">
                                @endforeach
                            </div>
                        @endif

                        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeVariant(this)">Xóa</button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

{{-- Template cho thêm mới --}}
<template id="variant-template">
    <div class="variant-box card p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <select name="variants[__INDEX__][attribute_id]" class="form-control w-25">
                @foreach($allAttributes as $a)
                    <option value="{{ $a->id }}">
                        {{ $a->parent_id ? '-- '.$a->name : $a->name }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="variants[__INDEX__][so_luong]"
                   class="form-control w-25 mx-2"
                   placeholder="Số lượng">

            <input type="file" name="variants[__INDEX__][images][]" multiple class="form-control w-50">

            <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeVariant(this)">Xóa</button>
        </div>
    </div>
</template>

<script>
    let variantIndex = document.querySelectorAll('#variants-container .variant-box').length;

    function addVariant() {
        let tmpl = document.getElementById('variant-template').innerHTML;
        tmpl = tmpl.replace(/__INDEX__/g, variantIndex);
        document.getElementById('variants-container').insertAdjacentHTML('beforeend', tmpl);
        variantIndex++;
    }

    function removeVariant(el) {
        el.closest('.variant-box').remove();
    }
</script>

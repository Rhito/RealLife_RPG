<div class="input-group">
    @if(!isset($field['show_p_tag']) || $field['show_p_tag'] === true)
        <p id="input-{{ $field['name'] }}" style="color: #000; margin: 0;">{!! old($field['name']) != null ? nl2br(old($field['name'])) : nl2br(number_format(@$field['value'], 0, '.', '.')) !!}<sup>đ</sup></p>
    @endif
    <input type="text" name="{{ @$field['name'] }}" class="form-control {{ @$field['class'] }}"
    id="{{ $field['name'] }}" {!! @$field['inner'] !!} 

    @if(isset($field['value']) && $field['value'] != '') style="display: none;" @endif

    value="{{ old($field['name']) != null ? old($field['name']) : @$field['value'] }}"
    {{ strpos(@$field['class'], 'require') !== false ? 'required' : '' }}
    >
    <div class="input-group-append" 
    @if(isset($field['value']) && $field['value'] != '') style="display: none;" @endif
    >
        <span class="input-group-text">đ</span>
    </div>

    <script>
        $('input#{{ $field['name'] }}, #form-group-{{ $field['name'] }}').click(function () {
            $('#input-{{ $field['name'] }}').hide();
            $('#{{ $field['name'] }}').show().click();
        });
    </script>
</div>
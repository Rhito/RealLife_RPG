@php
    $model = new $module['modal'];
    $catParent = $model->where('type', '=', 3)->where('id', '!=', $result->id ?? null)->get();
@endphp
<select name="{{ $field['name'] }}" class="form-control setupSelect2">
    <option value="0">Chọn {{ trans(@$field['label']) }}</option>
    @if(!empty($catParent))
        @foreach ($catParent as $v)
            <option value="{{$v->id}}" {{old($field['name'], isset($result->parent_id) ? $result->parent_id : null) == $v->id ? 'selected' : ''}}>{{$v->{$field['display_field']} }}</option>
        @endforeach
    @endif
</select>
<script type="text/javascript" src="{{ asset(config('core.admin_asset').'/js/setupSelect2.js') }}"></script>

<?php
$model = new $field['model'];

if (isset($field['where'])) {
    $model = $model->whereRaw($field['where']);
}
if (isset($field['where_attr']) && isset($result)) {
    $model = $model->where($field['where_attr'], $result->{$field['where_attr']});
}

if (isset($result)) {
    $data = $model->orderBy($field['display_field'], 'asc')->get();
} else {
    $data = \App\Modules\Product\Models\Category::where('parent_id', 0)
        ->with('children')
        ->orderBy('id', 'asc')
        ->get();
}

// chỉ lấy 1 id (single)
$value = null;
if (isset($result)) {
    $value = $result->{$field['name']};
} else {
    if (old($field['name']) != null) {
        $value = old($field['name']);
    } elseif (isset($field['value'])) {
        $value = $field['value'];
    }
}

// Hàm đệ quy render option
function renderOptions($items, $value, $field, $prefix = '')
{
    foreach ($items as $item) {
        echo "<option value='{$item->id}' "
            . ($item->id == $value ? 'selected' : '')
            . ">{$prefix}{$item->{$field['display_field']}}"
            . (isset($field['display_field2']) ? ' | ' . $item->{$field['display_field2']} : '')
            . "</option>";

        if ($item->children && $item->children->count()) {
            renderOptions($item->children, $value, $field, $prefix . '-- ');
        }
    }
}
?>

<select class="form-control {{ $field['class'] ?? '' }} select2-{{ $field['name'] }}"
        id="{{ $field['name'] }}"
        {{ strpos(@$field['class'], 'require') !== false ? 'required' : '' }}
        name="{{ $field['name'] }}">
    <option value="">{{ trans('admin.choose') }} {{ __($field['label']) }}</option>
    <?php renderOptions($data, $value, $field); ?>
</select>

<script>
    $(document).ready(function () {
        $('.select2-{{ $field['name'] }}').select2();
    });
</script>

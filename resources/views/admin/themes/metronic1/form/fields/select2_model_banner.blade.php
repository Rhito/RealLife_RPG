@php
    $model = $field['model'];
    $query = $model::query();

    // Nếu có where thì áp dụng
    if (!empty($field['where'])) {
        $query->whereRaw($field['where']);
    }

    // Lấy dữ liệu
    $options = $query->pluck($field['display_field'], 'id')->toArray();

    if (isset($field['multiple'])) {
        $data = old($field['name']) != null ? old($field['name']) : explode('|', @$field['value']);
    } else {
        $data[] = old($field['name']) != null ? old($field['name']) : @$field['value'];
    }
@endphp

<select class="form-control select2 {{ $field['class'] ?? '' }}"
        id="{{ $field['name'] }}"
        name="{{ $field['name'] }}{{ isset($field['multiple']) ? '[]' : '' }}"
        @if(isset($field['multiple'])) multiple @endif>

    <option value="">-- Chọn {{ $field['label'] ?? '' }} --</option>
    @foreach($options as $value => $name)
        <option value="{{ $value }}" {{ in_array($value, $data) ? 'selected' : '' }}>
            {{ $name }}
        </option>
    @endforeach
</select>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ $field['name'] }}').select2();
        });
    </script>
@endpush

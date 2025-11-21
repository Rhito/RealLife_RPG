<div class="form-group">
    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    <textarea id="{{ $field['name'] }}"
              name="{{ $field['name'] }}"
              class="form-control {{ $field['class'] ?? '' }}"
              rows="3"
              placeholder="{{ $field['label'] }}">{{ old($field['name'], $field['value'] ?? '') }}</textarea>

    {{-- Hiển thị lỗi --}}
    @if($errors->has($field['name']))
        <span class="text-danger">{{ $errors->first($field['name']) }}</span>
    @endif
</div>

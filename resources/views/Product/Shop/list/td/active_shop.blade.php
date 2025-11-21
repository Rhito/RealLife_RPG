@if(isset($field['options'][$item->{$field['name']}]))


    {{__(trans(@$field['options'][$item->{$field['name']}]))}}
@else

    {{__($item->{$field['name']})}}


@endif
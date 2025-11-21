@if($item->{$field['name']} != null)
    {!! date('H:i:s', strtotime($item->{$field['name']})) !!}
@endif
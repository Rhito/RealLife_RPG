<?php
$user_district =App\Modules\Product\Models\Wards::find(@$item->{$field['name']});
?>
{{@$user_district->name}}
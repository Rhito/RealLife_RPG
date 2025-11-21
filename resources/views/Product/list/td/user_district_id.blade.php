<?php
$user_district =App\Modules\Product\Models\Districts::find(@$item->{$field['name']});
?>
{{@$user_district->name}}
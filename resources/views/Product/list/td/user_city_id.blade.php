<?php
        $user_city =App\Modules\Product\Models\Provinces::find(@$item->{$field['name']});
        ?>
{{@$user_city->name}}

<?php
$code = '|';
foreach (@$item->orders as $prd){
    $code .= @$prd->product->code.'|';
}
?>
{{$code}}

<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'utils'.DIRECTORY_SEPARATOR.'Crypt.php';

$text = "N4DESWvRN36rSnY+KX6G0L7iapFbK9hmVlmZwqFrEwY=";
$decr = Crypt::decrypt($text);
echo $decr;
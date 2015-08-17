<?php

require_once ('src/facebook.php');

$pict = $POST['pict'];

$facebook = new Facebook();

$post = $facebook->api("me/feed", "POST", array(
"message"=>"Esto es un ejemplo",
"picture"=>$pict,


));
var_dump($post);

?>
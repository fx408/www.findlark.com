<?php

include 'request.php';

$request = new Request();
$code = $_GET['code'];


$url = 'https://github.com/login/oauth/access_token';

$data = array('client_id'=>'f3a16dca70ce2d2a2f3e', 'client_secret'=>'58888aefb8fe1659c67dbf33513c62b45592959e', 'code'=>$code);

$content = $request->sendCURLRequest($url, $data, 'post');


$access_token = $token_type = $error = null;

// https://api.github.com/user?access_token=53289012f8e7357f83dad539be732054c5d14575
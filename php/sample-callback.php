<?php

/// 1. Exchange auth code for access token
$client_id = 'YOUR_CLIENT_ID';
$client_secret = 'YOUR_CLIENT_SECRET';
$redirect_uri= "http://localhost:8888/callback.php";
$authorization_code = $_GET['code'];
$url = 'https://auth.domain.com.au/v1/connect/token';

$options = array(
    'http' => array(
        'header'  =>  [
		"Content-type: application/x-www-form-urlencoded",
		"Authorization: Basic " . base64_encode($client_id . ":" . $client_secret),
	],
        'method'  => 'POST',
        'content' => 'grant_type=authorization_code&code=' . $authorization_code . '&redirect_uri=' . $redirect_uri
    )
);

$context  = stream_context_create($options);

$result = file_get_contents($url, false, $context);

$tokenobj = json_decode($result);

$token = $tokenobj->{'access_token'};

/// 2. Use access token to query agencies...

$url = 'https://api.domain.com.au/v1/agencies/?q=sydney';

$options = array(
  'http' => array(
    'header' => [
      'Authorization: Bearer ' . $token,
      'Content-Type: application/json',
    ],
    'method' => 'GET',
  )
);
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

var_dump($result);
?>
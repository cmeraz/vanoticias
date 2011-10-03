<?php
/* Session */
session_start();

/* Include */
require_once('twitteroauth.php');
require_once('config.php');

/* Init */
$connect = new TwitterOAuth(
	CONSUMER_KEY,
	CONSUMER_SECRET
);

/* Get Token */
$token = $connect->getRequestToken($_GET['_callback']);

/* Set session */
$_SESSION['oauth_token'] = $token['oauth_token'];
$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
 
/* HTTP Code */
switch ($connect->http_code) {
  case 200:
    $url = $connect->getAuthorizeURL($token['oauth_token']);
    header('Location: ' . $url); 
    break;
  default:
    echo 'Could not connect to Twitter.';
}
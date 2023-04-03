<?php
require_once('config.php');
require_once('../vendor/autoload.php');
require_once(('functions.php'));
use GuzzleHttp\Client;

$client = new Client(['timeout'  => 2.0, 'verify' => false]);

$response = $client->request('POST', 'https://oauth2.googleapis.com/token',
['form_params' => [
    'code' => $_GET['code'],
    'client_id' => GOOGLE_ID,
    'client_secret' => GOOGLE_SECRET,
    'redirect_uri' => 'http://localhost:8081/connect.php',
    'grant_type' => 'authorization_code'
]]);

$token = json_decode($response->getBody())->access_token;

$response = $client->request('GET', 'https://openidconnect.googleapis.com/v1/userinfo',
[ 'headers' => [
    'Authorization' => 'Bearer ' . $token

]]);

$response = json_decode((string)$response->getBody());

if ($response->email_verified === true) {
    if (isset(getUserByEmail($response->email)[0])) {
        session_start();
        $_SESSION['user'] = getUserByEmail($response->email)[0];
    }
    header('Location: index.php');
    exit;
}
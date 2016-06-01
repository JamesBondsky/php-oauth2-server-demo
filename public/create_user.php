<?php

include_once (realpath(__DIR__) . '/bootstrap.php');

$username = $_POST['username'];
$password = $_POST['password'];

$sql = 'INSERT INTO oauth_users (username, password) VALUES (:username, :password)';
$params = array(':username' => $username, ':password' => sha1($password));
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$stmt->closeCursor();

$oauthRequest->request->set('grant_type', 'password');
$oauthRequest->request->set('client_id', 'test-client-id');
$oauthRequest->request->set('client_secret', 'test-client-secret');
$accessToken = $oauthServer->grantAccessToken($oauthRequest);

$apiResponse = new \Symfony\Component\HttpFoundation\Response();
if ($oauthServer->getResponse()->isSuccessful()) {
    $apiResponse->setStatusCode(201);
    $apiResponse->setContent(json_encode(array(
        'meta' => array(
            'statusCode' => 201,
        ),
        'access_token' => $accessToken['access_token']
    )));
} else {
    $apiResponse->setStatusCode(400);
    $apiResponse->setContent(json_encode(array(
        'meta' => array(
            'statusCode' => 400
        )
    )));
}
$apiResponse->send();

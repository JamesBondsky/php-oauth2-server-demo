<?php

include_once (realpath(__DIR__) . '/bootstrap.php');

$username = $_POST['username'];
$password = $_POST['password'];

$sql = 'SELECT * FROM oauth_users WHERE username =:username AND password =:password';
$params = array(':username' => $username, ':password' => sha1($password));
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$user = $stmt->fetchObject();
$stmt->closeCursor();

$apiResponse = new \Symfony\Component\HttpFoundation\Response();
if ($user instanceof stdClass) {
    $oauthRequest->request->set('grant_type', 'password');
    $oauthRequest->request->set('client_id', 'test-client-id');
    $oauthRequest->request->set('client_secret', 'test-client-secret');
    $accessToken = $oauthServer->grantAccessToken($oauthRequest);
    if ($oauthServer->getResponse()->isSuccessful()) {
        $apiResponse->setContent(json_encode(array(
            'meta' => array(
                'statusCode' => 200,
            ),
            'access_token' => $accessToken['access_token']
        )));
        $apiResponse->send();
        exit;
    }
}
$apiResponse->setStatusCode(401);
$apiResponse->setContent(json_encode(array(
    'meta' => array(
        'statusCode' => 401
    )
)));
$apiResponse->send();
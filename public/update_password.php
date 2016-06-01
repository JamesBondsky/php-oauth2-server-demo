<?php

include_once (realpath(__DIR__) . '/bootstrap.php');

$apiResponse = new \Symfony\Component\HttpFoundation\Response();
if ($oauthServer->verifyResourceRequest($oauthRequest) == false) {
    $apiResponse->setStatusCode(401);
    $apiResponse->setContent(json_encode(array(
        'meta' => array(
            'statusCode' => 401
        )
    )));
} else {
    $username = $oauthServer->getResourceController()->getToken()['user_id'];
    $password = $_POST['password'];
    $sql = 'UPDATE oauth_users SET password =:password WHERE username =:username';
    $params = array(':username' => $username, ':password' => sha1($password));
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $stmt->closeCursor();
    $apiResponse->setContent(json_encode(array(
        'meta' => array(
            'statusCode' => 200,
        )
    )));
}
$apiResponse->send();
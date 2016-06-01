<?php

$basePath = realpath(__DIR__ . '/../');
include_once ($basePath . '/vendor/autoload.php');

ini_set('display_error', true);
error_reporting(E_ALL);
ini_set('error_log', $basePath . '/logs/php_error.log');
$dataFolder = $basePath . '/data';
$dbFile = $dataFolder . '/data.db';
$privateKeyFile = $dataFolder . '/private.pem';
$publicKeyFile = $dataFolder . '/public.pem';

if (is_file($dbFile) == false) {
    include_once ($basePath . '/data/init.php');
}

// Database
$pdo = new PDO('sqlite:' . $dbFile);

// OAuth Server
$pdoStorage = new \OAuth2\Storage\Pdo($pdo);
$keyStorage = new \OAuth2\Storage\Memory(array(
    'keys' => array(
        'public_key' => $publicKeyFile,
        'private_key' => $privateKeyFile
    )
));
$oauthServer = new \OAuth2\Server();
$oauthServer->addStorage($pdoStorage);
$oauthServer->addStorage($keyStorage, 'public_key');
$oauthServer->addGrantType(new \OAuth2\GrantType\UserCredentials($pdoStorage));

$oauthRequest = \OAuth2\HttpFoundationBridge\Request::createFromGlobals();

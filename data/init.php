<?php

$dbFile = realpath(__DIR__) . '/data.db';
if (is_file($dbFile)) {
    unlink($dbFile);
}
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec('CREATE TABLE oauth_clients (client_id TEXT, client_secret TEXT, redirect_uri TEXT)');
$db->exec('CREATE TABLE oauth_access_tokens (access_token TEXT, client_id TEXT, user_id TEXT, expires TIMESTAMP, scope TEXT)');
$db->exec('CREATE TABLE oauth_authorization_codes (authorization_code TEXT, client_id TEXT, user_id TEXT, redirect_uri TEXT, expires TIMESTAMP, scope TEXT, id_token TEXT)');
$db->exec('CREATE TABLE oauth_refresh_tokens (refresh_token TEXT, client_id TEXT, user_id TEXT, expires TIMESTAMP, scope TEXT)');
$db->exec('CREATE TABLE oauth_scopes (scope TEXT, is_default BOOLEAN);');
$db->exec('CREATE TABLE oauth_users (username VARCHAR(255) NOT NULL, password VARCHAR(2000), first_name VARCHAR(255), last_name VARCHAR(255), CONSTRAINT username_pk PRIMARY KEY (username));');
$db->exec('CREATE TABLE oauth_public_keys (client_id VARCHAR(80), public_key VARCHAR(8000), private_key VARCHAR(8000), encryption_algorithm VARCHAR(80) DEFAULT "RS256")');

// test data
$db->exec('INSERT INTO oauth_clients (client_id, client_secret) VALUES ("test-client-id", "test-client-secret")');

chmod($dbFile, 0777);

$privateKeyFile = realpath(__DIR__) . '/private.pem';
$publicKeyFile = realpath(__DIR__) . '/public.pem';
if (is_file($privateKeyFile)) {
    unlink($privateKeyFile);
}
if (is_file($publicKeyFile)) {
    unlink($publicKeyFile);
}
exec('openssl genrsa -out ' . $privateKeyFile . ' 2048');
exec('openssl rsa -in ' . $privateKeyFile . ' -pubout > ' . $publicKeyFile);

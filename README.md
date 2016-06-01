# About
This demo project demonstrates how can we use oauth2 server to protect our rest api.

# Setup
If you are using docker, you can prepare your environment with following command:
```bash
$ docker-compose up -d
```

This optional command helps you to create initial files (sqlite database, private/public keys). If there is not any
database file, these operations run automatically while handling http requests.
```bash
$ php data/init.php
```

# Usage

We need token to access our protected resources. There are two methods for that: create_user.php, login.php.

create_user.php:
```bash
$ curl -X POST -F 'username=username' -F 'password=password' http://127.0.0.1:7888/create_user.php
{"meta":{"statusCode":201},"access_token":"22f072f7c9296bc799063ec86b91d0eb51e1972a"}
```

login.php:
```bash
$ curl -X POST -F 'username=username' -F 'password=password' http://127.0.0.1:7888/login.php
{"meta":{"statusCode":200},"access_token":"ba5c0f63debb68f525e65da8956edfcde902d580"}
```

update_password.php is our protected resource. So we need use access_token to complete this action:
```bash
$ curl -X POST -d 'access_token=c910fac0a7b924b508e1cb20d7557fb707273bfc&password=deneme' -H 'Content-Type:application/x-www-form-urlencoded' http://127.0.0.1:7888/update_password.php
{"meta":{"statusCode":200}}
```
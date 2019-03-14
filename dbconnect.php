<?php
$dsn = 'mysql:dbname=heroku_5fab00ca18dd060;host=us-cdbr-iron-east-03.cleardb.net';
$user = 'b5b8dd5bf9a2b6';
$password='572237fe';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
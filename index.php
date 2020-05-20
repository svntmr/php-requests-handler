<?php

echo 'Hello, World from Docker!';

$getIp = static function () {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
};
$getHostname = static function () {
    return php_uname('n');
};
$getPath = static function () {
    return $_SERVER['REQUEST_URI'];
};
$getTimestamp = static function () {
    return date('Y-d-m H:i:s +v');
};

$putRequestLine = static function () use ($getIp, $getHostname, $getPath, $getTimestamp) {
    $password = getenv('POSTGRES_PASSWORD');
    $user = getenv('POSTGRES_USER');
    $host = getenv('POSTGRES_HOST');
    $db = getenv('POSTGRES_DB');
    $connectionString = ("host={$host} dbname={$db} user={$user} password={$password}");
    $pgConnection = pg_connect($connectionString);
    $query = "INSERT INTO requests (ip, path, host, requested_at) VALUES ('{$getIp()}', '{$getPath()}', '{$getHostname()}', '{$getTimestamp()}')";
    pg_query($pgConnection, $query);
};
$putRequestLine();
<?php

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

$createConnection = static function () {
    $password = getenv('POSTGRES_PASSWORD');
    $user = getenv('POSTGRES_USER');
    $host = getenv('POSTGRES_HOST');
    $db = getenv('POSTGRES_DB');
    $connectionString = ("host={$host} dbname={$db} user={$user} password={$password}");
    $pgConnection = pg_connect($connectionString);
    return $pgConnection;
};

$pgConnection = $createConnection();

$putRequestLine = static function () use ($getIp, $getHostname, $getPath, $getTimestamp, $pgConnection) {
    $query = "INSERT INTO requests (ip, path, host, requested_at) VALUES ('{$getIp()}', '{$getPath()}', '{$getHostname()}', '{$getTimestamp()}')";
    pg_query($pgConnection, $query);
};
$putRequestLine();

$getRequests = static function () use ($pgConnection) {
    $query = 'SELECT id, ip, path, host, requested_at FROM requests ORDER BY id DESC LIMIT 25';
    $queryResource = pg_query($pgConnection, $query);
    return pg_fetch_all($queryResource);
};

$requests = $getRequests();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            Dockerized PHP Hello-World
        </title>
    </head>
    <body>
        <h1>Hello, World from Docker!</h1>
        <h2>The requests</h2>
        <table style="width: 100%;" border="1">
            <thead>
                <tr>
                    <th>IP</th>
                    <th>Path</th>
                    <th>Container</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request) : ?>
                    <tr>
                        <td><?= $request['ip'] ?></td>
                        <td><?= $request['path'] ?></td>
                        <td><?= $request['host'] ?></td>
                        <td><?= $request['requested_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>
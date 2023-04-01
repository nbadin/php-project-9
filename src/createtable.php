<?php

namespace Hexlet\Code;

require './vendor/autoload.php';

use Hexlet\Code\Connection;

try {
    $connection = Connection::get()->connect();
    $sqlCommand = file_get_contents('./src/database.sql');
    $connection->exec($sqlCommand);
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

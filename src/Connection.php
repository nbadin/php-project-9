<?php

namespace Hexlet\Code;

final class Connection
{
    private static ?Connection $conn = null;

    public function connect()
    {
        // $params = parse_url($_ENV['DATABASE_URL']);
        $params = parse_url('postgresql://postgres:XRo0TBD2K56q11r0XBUx@containers-us-west-177.railway.app:6413/railway');
        if ($params === false) {
            throw new \Exception("Error reading database configuration file");
        }

        $conStr = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s",
            $params['host'],
            $params['port'],
            ltrim($params['path'], '/'),
        );

        $pdo = new \PDO($conStr, $params['user'], $params['pass']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function get()
    {
        if (null === static::$conn) {
            static::$conn = new self();
        }

        return static::$conn;
    }

    protected function __construct()
    {
    }
}

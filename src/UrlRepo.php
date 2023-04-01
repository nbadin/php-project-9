<?php

namespace Hexlet\Code;

require '../vendor/autoload.php';

use Carbon\Carbon;
use Hexlet\Code\Connection;

class UrlRepo
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::get()->connect();
    }

    public function getAllUrls()
    {
        $sql = 'SELECT * FROM urls ORDER BY created_at DESC';
        return $this->pdo->query($sql)->fetchAll();
    }
    
    public function saveUrl(string $urlName)
    {
        $createdAt = Carbon::now();
        $sql = "INSERT INTO urls (name, created_at) VALUES ('{$urlName}', '{$createdAt}')";
        return $this->pdo->query($sql);
    }

    public function findUrlByName(string $name)
    {
        $sql = "SELECT * FROM urls WHERE name = '{$name}'";
        return $this->pdo->query($sql)->fetch();
    }

    public function findUrlById(int $id)
    {
        $sql = "SELECT * FROM urls WHERE id = {$id}";
        return $this->pdo->query($sql)->fetch();
    }
}

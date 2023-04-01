<?php

namespace Hexlet\Code;

require '../vendor/autoload.php';

class Url
{
    private $id;
    private $name;
    private $creationTime;

    public function __construct($urlData)
    {
        $this->id = $urlData['id'];
        $this->name = $urlData['name'];
        $this->creationTime = $urlData['created_at'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCreationTime()
    {
        return $this->creationTime;
    }
}

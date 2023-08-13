<?php
namespace back;

use Symfony\Component\Yaml\Yaml;

class Method
{
    public static function getMethod(){
        return Yaml::parseFile("../back/database_config.yaml")['method'];
    }
}
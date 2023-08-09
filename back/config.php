<?php
include_once "../vendor/autoload.php";
use Symfony\Component\Yaml\Yaml;
class config
{
    static function getMethod(){
        return Yaml::parseFile("database_config.yaml")['method'];
    }
}
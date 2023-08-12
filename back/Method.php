<?php
use Symfony\Component\Yaml\Yaml;

class Method
{
    public static function getMethod(){
        return Yaml::parseFile("database_config.yaml")['method'];
    }
}
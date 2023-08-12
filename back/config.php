<?php
include_once "../vendor/autoload.php";
use Symfony\Component\Yaml\Yaml;
class config
{
    private static function getMethod(){
        return Yaml::parseFile("database_config.yaml")['method'];
    }

    public static function setMethod(DataSaverInterface $dataSaver)
    {

    }
}
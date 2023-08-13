<?php
include_once "../vendor/autoload.php";

final class Saver
{
    private function __construct(){}
    private static DataSaverInterface $saver;

    private static function getMethod():void
    {
        self::$saver = (strtolower(Method::getMethod()) == "sql") ? SQLManager::getInstance() : JsonManager::getInstance();
    }

    public static function setMethod(DataSaverInterface $dataSaver):void
    {
        self::$saver = $dataSaver;
    }

    public static function getSaverObject():DataSaverInterface
    {
        self::getMethod();
        return self::$saver;
    }

}
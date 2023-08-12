<?php

class DatabaseManager implements DataSaverInterface
{

    private static string $dns = "mysql:host=localhost;";
    private static string $user = "root";
    private static PDO $pdo;

    public static function start()
    {
        self::$pdo = new PDO(self::$dns, self::$user);
        self::createDataBase();
    }

    private static function createDataBase(): void
    {
        $isAvailable = self::$pdo->query("show databases like 'chattest'", PDO::FETCH_ASSOC)->fetch();
        if (!$isAvailable) {
            self::$pdo->query('create database "chat_database";')->execute();
        }
        self::$pdo->query("use chattest;")->execute();
    }

    public static function getMassages(): array|false
    {
        return self::$pdo->query("select * from massages;", PDO::FETCH_ASSOC)->fetchAll();
    }

    public static function getUsers(): array|false
    {
        return self::$pdo->query("select * from users;", PDO::FETCH_ASSOC)->fetchAll();
    }

    public static function addUser(array $user): void
    {
        self::$pdo->prepare(
            'insert into users values
                                    (:userName,:name,:email,:password,:admin,:blocked,null);'
        )->execute($user);
    }

    public static function addMassage(array $massage)
    {
        self::$pdo->prepare(
            'insert into massages values
                                    (:id,:text,:Image,:sender,:time);'
        )->execute($massage);
    }

    public static function makeAdmin(){
        //todo}
    }

    public static function blockToggle()
    {
        //todo
    }

    public static function deleteMassage()
    {
        //todo
    }

}

<?php

class JsonManager
{
    private static array $jsonUsersArray = [];
    private static array $jsonMassagesArray = [];
    private static function createJson()
    {
        if (is_file('../front/userData.json')) {
            self::$jsonUsersArray= json_decode(file_get_contents('../front/userData.json'), true);
        }else file_put_contents('../front/userData.json',[]);

        if (is_file('../front/msg.json')) {
            self::$jsonMassagesArray = json_decode(file_get_contents('../front/msg.json'), true);
        }else file_put_contents('../front/msg.json',[]);
    }

    /**
     * @return array
     */
    public static function getJsonMassagesArray(): array
    {
        self::createJson();
        return self::$jsonMassagesArray;
    }

    /**
     * @return array
     */
    public static function getJsonUsersArray(): array
    {
        self::createJson();
        return self::$jsonUsersArray;
    }

    public static function saveUsers(array $users):void
    {
        file_put_contents('../front/userData.json',$users);
    }

    public static function saveMassages(array $msgs):void
    {
        file_put_contents('../front/msg.json',$msgs);
    }
}
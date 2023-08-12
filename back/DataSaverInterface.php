<?php

interface DataSaverInterface
{
    public static function getUsers();
    public static function getMassages();

    public static function addMassage(array $massage);
    public static function addUser(array $user);

    public static function addProfilePic(array $pic);

    public static function deleteMassage();
}
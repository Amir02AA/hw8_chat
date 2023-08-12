<?php

interface DataSaverInterface
{

    public static function getInstance() : DataSaverInterface;
    public  function getUsers();
    public  function getMassages();

    public  function addMassage(array $massage);
    public  function addUser(array $user);

    public  function addProfilePic(array $pic);

    public  function deleteMassage();

    public  function addImage(array $image);

    public  function deleteImage(array $image);

    public  function getImagesOfUser(string $userName);

    public  function makeAdmin(string $userName);

    public  function isAdmin(string $userName);

    public  function isBlocked(string $userName);

    public  function blockToggle(string $userName);
}
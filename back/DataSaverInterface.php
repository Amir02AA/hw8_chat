<?php
namespace back;
interface DataSaverInterface
{

    public static function getInstance() : DataSaverInterface;
    public  function getUsers();
    public  function getMassages();

    public  function addMassage(array $massage);
    public  function addUser(array $user);

    public  function addProfilePic(array $pic);

    public  function deleteMassage(string $id);


    public  function deleteImage(int $index);

    public  function getImagesOfUser(string $userName);

    public  function makeAdmin();

    public  function isAdmin();

    public  function isBlocked(string $userName='');

    public  function blockToggle(string $userName);
}
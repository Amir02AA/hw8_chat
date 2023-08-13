<?php
include_once "DataSaverInterface.php";
include_once "SQLManager.php";
include_once "Saver.php";
include_once "Method.php";
include_once "JsonManager.php";

//$db = SQLManager::getInstance();
$db = Saver::getSaverObject();
$user = [
    'userName' => "aa04",
    'name' => 'Amir',
    'email' => 'aa02@mail',
    'password' => '123456',
    'admin' => 0,
    'blocked' => 0
];
$image = [
    'userName' => "aa04",
    "pic" => 'aa04 pic '
];
$massage = [
    'id'=>uniqid(),
    'sender'=>'aa02',
    'text'=>'first Sql Massage',
    'Image'=>'',
    'time'=>time()
];
//$db->addProfilePic($image);
$db->addUser($user);
$db->addMassage($massage);
echo "<pre>";
//print_r($db->getImagesOfUser('aa04'));
print_r($db->getUsers());
print_r($db->getMassages());
echo "<pre>";
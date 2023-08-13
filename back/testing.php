<?php
include_once "DataSaverInterface.php";
include_once "SQLManager.php";

$db = SQLManager::getInstance();
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
$db->addProfilePic($image);
//$db->addUser($user);
echo "<pre>";
print_r($db->getImagesOfUser('aa04'));
echo "<pre>";
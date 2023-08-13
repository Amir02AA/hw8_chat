<?php
namespace back;

if (!session_id()) session_start();
$userName = @$_SESSION['userName'];

$jsonArray = [];
$msgArray = [];
$userData = [];
if (is_file('../front/userData.json')) {
    $jsonArray = json_decode(file_get_contents('../front/userData.json'), true);
}
if (false){
    //todo
    $jsonArray = SQLManager::getUsers();
    $msgArray  = SQLManager::getMassages();
}

if (is_file('../front/msg.json')) {
    $msgArray = json_decode(file_get_contents('../front/msg.json'), true);
}
foreach ($jsonArray as $item) {
    if ($item['userName'] == $userName) {
        $userData = $item;
        break;
    }
}

if (isset($_POST['delete'])) {
    if (is_file($_POST['delete'])) unlink($_POST['delete']);
}

$imageTypes = ['png', 'jpeg', 'jpg', 'icon'];


function getUsersArray(): array
{
    global $usersArray;
    return $usersArray;
}

function getUserData()
{
    global $userData;
    return $userData;
}

function addImage($imagePath)
{
    global $userData;
    $userData['images'][] = $imagePath;
    saveChangesProfile();
}

function deleteImage($index): void
{
    global $userData;
    unlink($userData['images'][$index]);
    unset($userData['images'][$index]);
    $userData['images'] = array_values($userData['images']);
    saveChangesProfile();
}

function getImages()
{
    global $userData;
    return (isset($userData['images'])) ? $userData['images'] : false;
}

function saveChangesProfile(): void
{
    global $usersArray, $userData;
    foreach ($usersArray as $key => $item) {
        if ($item['userName'] == $userData['userName']) {
            $usersArray[$key] = $userData;
            file_put_contents('../front/userData.json', json_encode($usersArray, JSON_PRETTY_PRINT));
        }
    }
}

function addMsg($text, $imageName = false): void
{
    global $userData, $msgArray;

    $massage = [
        'id'=> uniqid(),
        "text" => $text,
        "Image" => (!$imageName)?false:"../UsersData/msg Images/" . $imageName,
        "sender" => $userData['userName'],
        "time" => time()
    ];
    if (false){
        //todo
        SQLManager::addMassage($massage);
    }
    $msgArray[] = $massage;
    saveChangesMsg();
}

function deleteMsg($id):void{
    global $msgArray;
    foreach ($msgArray as $key => $item) {
        if ($item['id'] == $id){
            unset($msgArray[$key]);
            break;
        }
    }
    saveChangesMsg();
}

function saveChangesMsg(): void
{
    global $msgArray;
    file_put_contents('../front/msg.json', json_encode($msgArray, JSON_PRETTY_PRINT));
}

function imageErrorCheck($name, $size): string|false
{
    $imageTypes = ['png', 'jpeg', 'jpg', 'icon'];
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    if (!in_array($ext, $imageTypes)) {
        return "wrong format. only pictures are allowed";
    } else if ($size > 1024 * 1024 * 2) {
        return "Size should be less than 2 MB";
    } else {
        return false;
    }
}

function getMsg(){
    global $msgArray;
    return $msgArray;
}

function getProfByUser($userName)
{
    global $usersArray;
    foreach ($usersArray as $key => $item) {
        if ($item['userName'] == $userName) {
            if ((@$item['images']) != []){
                return $item['images'][0];
            }else{
                return "../UsersData/diffProf.jpg";
            }
        }
    }
}

function makeAdmin(){
    global $userData;
    $userData['admin'] = true;
    saveChangesProfile();
}

function isAdmin(){
    global $userData;
    return $userData['admin'];
}

function isBlocked($userName){
    global $usersArray;
    foreach ($usersArray as $key => $item) {
        if ($item['userName'] == $userName) {
           return $item['blocked'];
        }
    }
}

function blockToggle($userName){
    global $usersArray;
    foreach ($usersArray as $key => $item) {
        if ($item['userName'] == $userName) {
            $usersArray[$key]['blocked'] = !$usersArray[$key]['blocked'];
        }
    }
    saveChangesProfile();
}
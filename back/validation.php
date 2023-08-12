<?php
$usersArray = [];

if (is_file('../back/userData.json')) {
    $usersArray = json_decode(file_get_contents('../back/userData.json'), true);
}


function checkSignUpErrors(array $user): array|false
{ 
    $errors = [];


    $errors['userName'] = userNameError($user['userName']);
    $errors['email'] = emailError($user['email']);
    $errors['name'] = nameError($user['name']);
    $errors['password'] = passwordError($user['password']);

    $errorCount = 0;
    foreach ($errors as $error) {
        if ($error != "") $errorCount++;
    }

    if ($errorCount == 0) {
        return false;
    }
    return $errors;
}

function checkLoginErrors(array $user)
{
    global $usersArray;
    if ($user['userName']=="" || $user['password']==""){
        return "Please Enter your User Name AND Password";
    }
    foreach ($usersArray as $userData) {
        if ($userData['userName'] == $user['userName'] && $userData['password'] == $user['password']) {
            return false;
        }
    }
    return "wrong User Name or Password";
}

function userNameError(string $userName): string
{
    global $usersArray;
    $userName_is_unique = true;
    foreach ($usersArray as $userData) {
        if ($userData['userName'] == $userName) {
            $userName_is_unique = false;
            break;
        }
    }

    $error = "";
    $match = [];
    if ($userName == "") $error = 'Please enter your User Name';
    elseif (!$userName_is_unique) $error = 'User Name must be unique';
    else {
        preg_match_all("/[a-zA-Z0-9]{3,32}/", $userName, $match);
        $error = ($match[0][0] == $userName) ? "" : "Wrong User Name Format";
    }

    return $error;
}

function emailError(string $email): string
{
    global $usersArray;
    $email_is_unique = true;
    foreach ($usersArray as $userData) {
        if ($userData['email'] == $email) {
            $email_is_unique = false;
            break;
        }

    }

    $error = "";
    if ($email == "") $error = 'Please enter your email';
    elseif (!$email_is_unique) $error = 'email must be unique';

    return $error;
}

function nameError(string $name): string
{
    $error = '';
    if ($name == "") $error = 'Please enter your Name';
    else {
        $match = [];
        preg_match("/[a-zA-Z\s]{3,32}/", $name, $match);
        $error = ($match[0] == $name) ? "" : "Name Format invalid";
    }

    return $error;
}

function passwordError(string $password): string
{
    $error = "";

    if ($password == "") $error = 'Please enter your password';
    else {
        $match = [];
        preg_match("/\w{3,32}/s", $password, $match);
        $error = ($match[0] == $password) ? "" : "Wrong Password Form";
    }

    return $error;
}

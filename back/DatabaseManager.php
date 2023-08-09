<?php

class DatabaseManager
{

    private string $dns = "mysql:host=localhost;";
    private string $user = "root";
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO($this->dns, $this->user);
        $this->createDataBase();
    }

    private function createDataBase(): void
    {
        $isAvailable = $this->pdo->query("show databases like 'chattest'", PDO::FETCH_ASSOC)->fetch();
        if (!$isAvailable) {
            $this->pdo->query('create database "chat_database";')->execute();
        }
        $this->pdo->query("use chattest;")->execute();
    }

    public function getMassages(): array|false
    {
        return $this->pdo->query("select * from massages;", PDO::FETCH_ASSOC)->fetchAll();
    }

    public function getUsers(): array|false
    {
        return $this->pdo->query("select * from users;", PDO::FETCH_ASSOC)->fetchAll();
    }

    public function addUser(array $user): void
    {
        $this->pdo->prepare(
            'insert into users values
                                    (:userName,:name,:email,:password,:admin,:blocked,null);'
        )->execute($user);
    }

    public function addMassage(string $text,bool $imageName = false, string $userName)
    {
        $massage = [
            'id' => uniqid(),
            "text" => $text,
            "Image" => (!$imageName) ? false : "../UsersData/msg Images/" . $imageName,
            "sender" => $userName,
            "time" => time()
        ];
        $this->pdo->prepare(
            'insert into massages values
                                    (:id,:text,:Image,:sender,:time);'
        )->execute($massage);
    }


}

$a = new DatabaseManager();

//$user = [
//    'userName' => 'sqluser226',
//    'name' => 'secret2',
//    'email' => 'email2@mail.com',
//    'password' => 'pass2',
//    'admin'=>false,
//    'blocked'=>false
//];
//$a->addUser($user);

echo "<pre>";
print_r($a->getUsers());
echo "<pre>";
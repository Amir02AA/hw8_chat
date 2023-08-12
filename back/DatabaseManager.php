<?php

class DatabaseManager implements DataSaverInterface
{
    private static DataSaverInterface|null $instance = null;
    private string $dns = "mysql:host=localhost;";
    private string $user = "root";
    private  PDO $pdo;

    public  function __cunstruct()
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

    public  function getMassages(): array|false
    {
        return $this->pdo->query("select * from massages;", PDO::FETCH_ASSOC)->fetchAll();
    }

    public  function getUsers(): array|false
    {
        return $this->pdo->query("select * from users;", PDO::FETCH_ASSOC)->fetchAll();
    }

    public  function addUser(array $user): void
    {
        $this->pdo->prepare(
            'insert into users values
                                    (:userName,:name,:email,:password,:admin,:blocked,null);'
        )->execute($user);
    }

    public  function addMassage(array $massage)
    {
        $this->pdo->prepare(
            'insert into massages values
                                    (:id,:text,:Image,:sender,:time);'
        )->execute($massage);
    }

    public static function getInstance(): DataSaverInterface
    {
        if (self::$instance == null){
            self::$instance = new DatabaseManager();
        }
        return self::$instance;
    }
    public  function deleteMassage()
    {
        //todo
    }

    public function addProfilePic(array $pic)
    {
        // TODO: Implement addProfilePic() method.
    }

    public function addImage(array $image)
    {
        // TODO: Implement addImage() method.
    }

    public function deleteImage(array $image)
    {
        // TODO: Implement deleteImage() method.
    }

    public function getImagesOfUser(string $userName)
    {
        // TODO: Implement getImagesOfUser() method.
    }

    public function isAdmin(string $userName)
    {
        // TODO: Implement isAdmin() method.
    }

    public function isBlocked(string $userName)
    {
        // TODO: Implement isBlocked() method.
    }

    public function makeAdmin(string $userName)
    {
        // TODO: Implement makeAdmin() method.
    }

    public function blockToggle(string $userName)
    {
        // TODO: Implement blockToggle() method.
    }
}

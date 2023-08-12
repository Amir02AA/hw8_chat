<?php

class DatabaseManager implements DataSaverInterface
{
    private static DataSaverInterface|null $instance = null;
    private string $dns = "mysql:host=localhost;";
    private string $user = "root";
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO($this->dns, $this->user);
        $this->createDataBase();
        $this->createTables();
    }

    public static function getInstance(): DataSaverInterface
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseManager();
        }
        return self::$instance;
    }

    private function createDataBase(): void
    {
        $databaseName = 'chat_database';
//        echo "db check<br>";
        $isAvailable = $this->pdo->query("show databases like '$databaseName'", PDO::FETCH_ASSOC)->fetchAll();
        if (!$isAvailable) {
            $this->pdo->prepare("create database $databaseName;")->execute();
        }

        $this->pdo->query("use $databaseName;")->execute();
    }

    private function createTables():void
    {
        $isAvailableUsers = $this->pdo->query("show tables like 'users';", PDO::FETCH_ASSOC)->fetchAll();
        if (!$isAvailableUsers) {
//            echo "users check <br>";
            $this->pdo->prepare ("CREATE TABLE `users` (
                                      `userName` varchar(50) NOT NULL,
                                      `name` varchar(50) DEFAULT NULL,
                                      `email` varchar(50) DEFAULT NULL,
                                      `password` varchar(50) DEFAULT NULL,
                                      `isAdmin` tinyint(4) DEFAULT NULL,
                                      `blocked` tinyint(4) DEFAULT NULL,
                                      PRIMARY KEY (`userName`)
                                    ) ;")->execute();
        }
        $isAvailableMassages = $this->pdo->query("show tables like 'massages';", PDO::FETCH_ASSOC)->fetchAll();
        if (!$isAvailableMassages){
//            echo "msg check<bR>";
            $this->pdo->prepare("CREATE TABLE `massages` (
                                      `id` varchar(50) NOT NULL,
                                      `text` text DEFAULT NULL,
                                      `image` varchar(255) DEFAULT NULL,
                                      `time` int(11) DEFAULT NULL,
                                      `sender_userName` varchar(50) DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `sender_userName` (`sender_userName`),
                                      CONSTRAINT `massages_ibfk_1` FOREIGN KEY (`sender_userName`) REFERENCES `users` (`userName`)
                                    );")->execute();
        }
        $isAvailablePics = $this->pdo->query("show tables like 'pics';", PDO::FETCH_ASSOC)->fetchAll();
        if (!$isAvailablePics){
//            echo "pic check<br>";
            $this->pdo->prepare("CREATE TABLE `pics` (
                                          `user` varchar(50) NOT NULL,
                                          `pic` varchar(255) NOT NULL,
                                          KEY `FK_userName` (`user`),
                                          CONSTRAINT `FK_userName` FOREIGN KEY (`user`) REFERENCES `users` (`userName`)
                                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;")->execute();
        }
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

    public function addMassage(array $massage)
    {
        $this->pdo->prepare(
            'insert into massages values
                                    (:id,:text,:Image,:sender,:time);'
        )->execute($massage);
    }


    public function deleteMassage()
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

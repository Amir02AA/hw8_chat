<?php

class SQLManager implements DataSaverInterface
{
    private static DataSaverInterface|null $instance = null;
    private string $dns = "mysql:host=localhost;";
    private string $user = "root";
    private PDO $pdo;

    private function __construct()
    {
        $this->pdo = new PDO($this->dns, $this->user);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_ASSOC);
        $this->createDataBase();
        $this->createTables();
    }

    public static function getInstance(): DataSaverInterface
    {
        if (self::$instance == null) {
            self::$instance = new SQLManager();
        }
        return self::$instance;
    }

    private function createDataBase(): void
    {
        $databaseName = 'chat_database';
//        echo "db check<br>";
        $isAvailable = $this->pdo->query("show databases like '$databaseName'")->fetchAll();
        if (!$isAvailable) {
            $this->pdo->prepare("create database $databaseName;")->execute();
        }

        $this->pdo->query("use $databaseName;")->execute();
    }

    private function createTables(): void
    {
        $isAvailableUsers = $this->pdo->query("show tables like 'users';")->fetchAll();
        if (!$isAvailableUsers) {
//            echo "users check <br>";
            $this->pdo->prepare("CREATE TABLE `users` (
                                      `userName` varchar(50) NOT NULL,
                                      `name` varchar(50) DEFAULT NULL,
                                      `email` varchar(50) DEFAULT NULL,
                                      `password` varchar(50) DEFAULT NULL,
                                      `isAdmin` tinyint(4) DEFAULT NULL,
                                      `blocked` tinyint(4) DEFAULT NULL,
                                      PRIMARY KEY (`userName`)
                                    ) ;")->execute();
        }
        $isAvailableMassages = $this->pdo->query("show tables like 'massages';")->fetchAll();
        if (!$isAvailableMassages) {
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
        $isAvailablePics = $this->pdo->query("show tables like 'pics';")->fetchAll();
        if (!$isAvailablePics) {
//            echo "pic check<br>";
            $this->pdo->prepare("CREATE TABLE `pics` (
              `user` varchar(50) NOT NULL,
              `pic` varchar(255) NOT NULL,
              `pic_index` int(11) NOT NULL,
              KEY `FK_userName` (`user`),
              CONSTRAINT `FK_userName` FOREIGN KEY (`user`) REFERENCES `users` (`userName`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;")->execute();
        }
    }

    public function getMassages(): array|false
    {
        return $this->pdo->query("select * from massages;")->fetchAll();
    }

    public function getUsers(): array|false
    {
        return $this->pdo->query("select * from users;")->fetchAll();
    }

    public function addUser(array $user): void
    {
        $this->pdo->prepare(
            'insert into users values
                                    (:userName,:name,:email,:password,:admin,:blocked);'
        )->execute($user);
    }

    public function addMassage(array $massage)
    {
        $this->pdo->prepare(
            'insert into massages values
                                    (:id,:text,:Image,:time,:sender);'
        )->execute($massage);
    }


    public function deleteMassage(string $id): void
    {
        $this->pdo->prepare("DELETE FROM massages
                                    WHERE id = :id;")->execute(["id" => $id]);
    }

    public function addProfilePic(array $pic): void
    {

        $st = $this->pdo->prepare("SELECT MAX(pic_index) from pics WHERE user = :userName;");
        $st->execute(['userName' => $pic['userName']]);
        $index = $st->fetch()['MAX(pic_index)'];

        $pic['index'] =$index + 1;
        $this->pdo->prepare("insert into pics values 
                                    (:userName,:pic,:index)")->execute($pic);
    }

    public function deleteImage(array $image): void
    {
        $this->pdo->prepare("delete from pics where
                                    user = :userName and 
                                    pic_index = :index")->execute($image);
    }

    public function getImagesOfUser(string $userName)
    {
        $state = $this->pdo->prepare("select pic from pics where user = :user");
        $state->execute(['user' => $userName]);
        return $state->fetchAll();
    }

    public function isAdmin(string $userName)
    {
        $st = $this->pdo->prepare("select isAdmin from users
                                        where userName = :userName");
        $st->execute(['userName' => $userName]);
        return $st->fetch();
    }

    public function isBlocked(string $userName)
    {
        $st = $this->pdo->prepare("select blocked from users
                                            where userName = :userName");
        $st->execute(['userName' => $userName]);
        return $st->fetch()['blocked'];
    }

    public function makeAdmin(string $userName)
    {
        $this->pdo->prepare("update users set
                                    isAdmin = 1
                                    where userName = :userName")
            ->execute(['userName' => $userName]);
    }

    public function blockToggle(string $userName)
    {
        $blocked = ($this->isBlocked($userName) == 1);
        $values = [
            'userName' => $userName,
            'blocked' => ($blocked) ? 0 : 1
        ];
        $this->pdo->prepare("update users set
                                    blocked = :blocked
                                    where userName = :userName")
            ->execute($values);
    }
}

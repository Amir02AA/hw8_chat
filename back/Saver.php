<?php
include_once "../vendor/autoload.php";

class Saver implements DataSaverInterface
{
    private static Saver|null $instance = null;
    private DataSaverInterface $saver;

    public function __construct()
    {
        $this->saver = (strtolower(Method::getMethod()) == "sql") ? DatabaseManager::getInstance() : JsonManager::getInstance();
    }

    public function setMethod(DataSaverInterface $dataSaver)
    {
        $this->saver = $dataSaver;
    }


    public static function getInstance(): DataSaverInterface
    {
        if (self::$instance == null) {
            self::$instance = new Saver();
        }
        return self::$instance;
    }

    public function getUsers()
    {
       return $this->saver->getUsers();
    }

    public function getMassages()
    {
        return $this->saver->getMassages();
    }

    public function addMassage(array $massage)
    {
        $this->saver->addMassage($massage);
    }

    public function addUser(array $user)
    {
        $this->saver->addUser($user);
    }

    public function addProfilePic(array $pic)
    {
        $this->saver->addProfilePic($pic);
    }

    public function deleteMassage()
    {
        $this->saver->deleteMassage();
    }

    public function addImage(array $image)
    {
        $this->saver->addImage($image);
    }

    public function deleteImage(array $image)
    {
        $this->saver->deleteImage($image);
    }

    public function getImagesOfUser(string $userName)
    {
       return $this->saver->getImagesOfUser($userName);
    }

    public function makeAdmin(string $userName)
    {
        $this->saver->makeAdmin($userName);
    }

    public function isAdmin(string $userName)
    {
        return $this->saver->isAdmin();
    }

    public function isBlocked(string $userName)
    {
        return $this->saver->isBlocked();
    }

    public function blockToggle(string $userName)
    {
        $this->saver->blockToggle($userName);
    }
}
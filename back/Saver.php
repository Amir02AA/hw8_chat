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
        // TODO: Implement getUsers() method.
    }

    public function getMassages()
    {
        // TODO: Implement getMassages() method.
    }

    public function addMassage(array $massage)
    {
        // TODO: Implement addMassage() method.
    }

    public function addUser(array $user)
    {
        // TODO: Implement addUser() method.
    }

    public function addProfilePic(array $pic)
    {
        // TODO: Implement addProfilePic() method.
    }

    public function deleteMassage()
    {
        // TODO: Implement deleteMassage() method.
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

    public function makeAdmin(string $userName)
    {
        // TODO: Implement makeAdmin() method.
    }

    public function isAdmin(string $userName)
    {
        // TODO: Implement isAdmin() method.
    }

    public function isBlocked(string $userName)
    {
        // TODO: Implement isBlocked() method.
    }

    public function blockToggle(string $userName)
    {
        // TODO: Implement blockToggle() method.
    }
}
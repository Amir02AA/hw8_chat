<?php

class JsonManager implements DataSaverInterface
{
    private static DataSaverInterface|null $instance = null;
    private array $jsonUsersArray = [];
    private array $jsonMassagesArray = [];
    private  function __construct()
    {
       $this->createJson();
    }

    private function createJson()
    {
        if (is_file('../front/userData.json')) {
            $this->jsonUsersArray= json_decode(file_get_contents('../front/userData.json'), true);
        }else file_put_contents('../front/userData.json',[]);

        if (is_file('../front/msg.json')) {
            $this->jsonMassagesArray = json_decode(file_get_contents('../front/msg.json'), true);
        }else file_put_contents('../front/msg.json',[]);
    }

    /**
     * @return array
     */
    public  function getJsonMassagesArray(): array
    {
        $this->createJson();
        return $this->jsonMassagesArray;
    }

    /**
     * @return array
     */
    public  function getJsonUsersArray(): array
    {
        $this->createJson();
        return $this->jsonUsersArray;
    }

    public  function saveUsers(array $users):void
    {
        file_put_contents('../front/userData.json',$users);
    }

    public  function saveMassages(array $msgs):void
    {
        file_put_contents('../front/msg.json',$msgs);
    }

    public static function getInstance(): DataSaverInterface
    {
        if (self::$instance == null){
            self::$instance = new JsonManager();
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
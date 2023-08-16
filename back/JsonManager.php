<?php

namespace back;

class JsonManager implements DataSaverInterface
{
    private static DataSaverInterface|null $instance = null;
    private array $jsonUsersArray = [];
    private array $jsonMassagesArray = [];
    private array $userData = [];
    private string $userName;

    private function __construct()
    {
        $this->jsonRefresh();
    }

    private function jsonRefresh(): void
    {
        if (is_file('../front/userData.json')) {
            $this->jsonUsersArray = json_decode(file_get_contents('../front/userData.json'), true);
        }
//        else file_put_contents('../front/userData.json', []);

        if (is_file('../front/msg.json')) {
            $this->jsonMassagesArray = json_decode(file_get_contents('../front/msg.json'), true);
        }
//        else file_put_contents('../front/msg.json', []);

        $this->getUserData();
    }

    private function getUserData(): void
    {
        $userName = @$_SESSION['userName'];
        foreach ($this->jsonUsersArray as $item) {
            if ($item['userName'] == $userName) {
                $this->userData = $item;
                break;
            }
        }
    }

    private function updateUserData()
    {
        foreach ($this->jsonUsersArray as $key => $user) {
            if ($this->userData['userName'] == $user['userName']) {
                $this->jsonUsersArray[$key] = $this->userData;
                $this->saveUsers();
                $this->jsonRefresh();
                break;
            }
        }
    }

    /**
     * @return array
     */
    public function getMassages(): array
    {
        $this->jsonRefresh();
        return $this->jsonMassagesArray;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        $this->jsonRefresh();
        return $this->jsonUsersArray;
    }

    public function saveUsers(): void
    {
        file_put_contents('../front/userData.json', json_encode($this->jsonUsersArray, JSON_PRETTY_PRINT));
    }

    public function saveMassages(): void
    {
        file_put_contents('../front/msg.json', json_encode($this->jsonMassagesArray, JSON_PRETTY_PRINT));
    }

    public static function getInstance(): DataSaverInterface
    {
        if (self::$instance == null) {
            self::$instance = new JsonManager();
        }
        return self::$instance;
    }

    public function addMassage(array $massage)
    {
        $this->jsonMassagesArray[] = $massage;
        $this->saveMassages();
    }

    public function addUser(array $user)
    {
        $this->jsonUsersArray[] = $user;
        $this->saveUsers();
    }

    public function addProfilePic(array $pic)
    {
        $this->jsonRefresh();
        $this->userData['images'][] = $pic['pic'];

        $this->updateUserData();
    }

    public function deleteMassage(string $id)
    {
        foreach ($this->jsonMassagesArray as $key => $item) {
            if ($item['id'] == $id) {
                unset($this->jsonMassagesArray[$key]);
                $this->jsonRefresh();
                break;
            }
        }
    }


    public function deleteImage(int $id)
    {
        unlink($this->userData['images'][$id]);
        unset($this->userData['images'][$id]);
        $this->userData['images'] = array_values($this->userData['images']);
        $this->updateUserData();
    }

    public function getImagesOfUser(string $userName = '')
    {
        $this->jsonRefresh();
        $userName = ($userName == '') ? $this->userData['userName'] : $userName;
        foreach ($this->jsonUsersArray as $key => $val) {
            if ($val['userName'] == $userName) {
                $images = (!array_key_exists("images", $val)) ? ["../UsersData/diffProf.jpg"] : $val['images'];
                return $images;
            }
        }
    }

    public function makeAdmin()

    {
        $this->userData['admin'] = true;
        $this->updateUserData();
    }

    public function isAdmin()
    {
        return $this->userData['admin'];
    }

    public function isBlocked(string $userName = ''): bool
    {
        $userName = ($userName == '') ? $this->userData['userName'] : $userName;

        foreach ($this->jsonUsersArray as $key => $item) {
            if ($item['userName'] == $userName) {
                return $item['blocked'];
            }
        }
    }

    public function blockToggle(string $userName)
    {
        foreach ($this->jsonUsersArray as $key => $item) {
            if ($item['userName'] == $userName) {
                $this->jsonUsersArray[$key]['blocked'] = !$this->jsonUsersArray[$key]['blocked'];
                $this->saveUsers();
                break;
            }
        }
    }
}
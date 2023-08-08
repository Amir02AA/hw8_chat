<?php
include_once "../back/user_functions.php";
if (!session_id()) session_start();
$userName = @$_SESSION['userName'];
if (!is_dir("../UsersData/" . $userName . "/")) {
    mkdir("../UsersData/" . $userName . "/");
    mkdir("../UsersData/" . $userName . "/" . "profile_pics/");
}

if (isset($_POST['submit'])) {
    if (@$_FILES['file']['name'] != "") {
        $imageTypes = ['png', 'jpeg', 'jpg', 'icon'];
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (in_array($ext, $imageTypes)) {
            $imagePath = "../UsersData/" . $userName . "/" . "profile_pics/" . $_FILES['file']['name'];
            addImage($imagePath);
            move_uploaded_file($_FILES['file']['tmp_name'], "../UsersData/" . $userName . "/" . "profile_pics/" . $_FILES['file']['name']);
        }
        else{
            $error = "<label class='text-red-400'>Wrong Format</label>";
        }
    }
}
if (isset($_POST['sBio'])) {
    if ($_POST['bio'] != "") {
        file_put_contents("../UsersData/" . $userName . "/" . "bio.txt", $_POST['bio']);
    }
}
$imagesCount = 0;
if (isset($_POST['delete']) && $_GET['page'] != -1) {
    deleteImage($_GET['page']);
    header("location:mainPage.php");
}

if (getImages()) {
    $imagesCount = sizeof(getImages());
    if (!isset($_GET['page'])) {
        $_GET['page'] = 0;
        header("location:mainPage.php?page=0");
    }
} elseif (!isset($_GET['page'])) $_GET['page'] = -1;

if (isset($_POST['admin'])) makeAdmin();
if (isset($_POST['block']) && isAdmin()){
    header("location:admin.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-600 flex flex-col py-5 px-30percent">
<div class="flex flex-row items-center justify-center">
    <div class="flex flex-col items-center justify-between px-4 py-3 sm:px-6 w-1/6 gap-3">
        <div class="profile">
            <?php $currentImage = (($_GET['page'] != -1) ? getImages()[$_GET['page']] : "../UsersData/diffProf.jpg");
            ?>
            <img src="<?= $currentImage ?>" alt="prof" class="rounded-full prof">
        </div>
        <div class="sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <?php
                    if (getImages()) {
                        foreach (getImages() as $index => $Image) { ?>
                            <a href="?page=<?= $index ?>" aria-current="page"
                               class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold
                       text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 rounded-lg
                       focus-visible:outline-indigo-600" <?= (($index == $_GET['page'])?"style='background-color:green'":"")?>><?= $index+1?></a>
                        <?php }
                    }
                    ?>
                </nav>
            </div>
        </div>
        <div>
            <form method="post">
                <button type="submit" name="delete" class="bg-red-900 text-white rounded-lg hover: px-3 py-1 text-center">Delete</button>
            </form>
        </div>
    </div>

</div>
<form class="flex flex-col gap-5" method="post" enctype="multipart/form-data">
    <div class="flex flex-col gap-3">
        <label for="bio">Bio:</label>
        <textarea name="bio" id="bio" rows="4" cols="25"></textarea>
        <div class="flex gap-3">
            <button type="submit" name="sBio" class="bg-blue-700 text-white rounded-lg hover: px-3 py-1 text-center">Change Bio</button>
            <?php if (!isAdmin()){?>
            <button type="submit" name="admin" class="bg-blue-700 text-white rounded-lg hover: px-3 py-1 text-center">Make Admin</button>
            <?php }else{ ?>
            <button type="submit" name="block" class="bg-red-500 text-white rounded-lg hover: px-3 py-1 text-center">Block Users</button>
            <?php } ?>

        </div>
    </div>
    <div class="flex gap-3">
        <label for="profile" class="bg-blue-700 text-white rounded-lg hover: px-3 py-1 text-center">Add Profile Pic</label>
        <input type="file" hidden="hidden" id="profile" name="file" class="w-1/4">
        <button type="submit" class="bg-blue-700 text-white rounded-lg hover: px-3 py-1 text-center" name="submit">Submit</button>
        <div class="flex flex-col gap-2">
            <?php
            if (isset($error))
                echo $error?>
        </div>
    </div>
    <div>
        <a href="chatGroup.php" class="bg-blue-700 text-white rounded-lg hover: px-3 py-1 text-center">Enter Chat Room</a>
        <a href="signin.php" class="bg-blue-700 text-white rounded-lg hover: px-3 py-1 text-center">Logout</a>
    </div>
</form>
</body>

</html>

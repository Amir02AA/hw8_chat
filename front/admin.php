<?php
include_once "../vendor/autoload.php";
include_once "autoloader.php";
if (!session_id()) session_start();

$saver = \back\Saver::getSaverObject();
$users = $saver->getUsers();

if (isset($_POST['blockToggle'])) {
    $saver->blockToggle($_POST['blockToggle']);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-600 flex flex-col gap-3 px-5 py-20 justify-center items-center">
<?php foreach ($users as $user) {
if ($user['userName'] != @$_SESSION['userName']) {
    ?>
    <form class="flex gap2 justify-center items-center w-1/4" method="post">
        <div class="w-full">
            <button type="submit" name="blockToggle"
                    class="font-bold w-full rounded-lg px-2 py-4 text-black hover:bg-opacity-50
                        <?= ($saver->isBlocked($user['userName'])) ? "bg-red-400" : "bg-green-400" ?>"
                    value="<?= $user['userName'] ?>">
                <?= $user['userName'] ?>
            </button>
        </div>
    </form>
<?php }
} ?>
<a class="bg-gray-900 border-white text-white font-bold rounded-lg px-2 py-4 hover:bg-opacity-50" href="mainPage.php">Back</a>
</body>
</html>

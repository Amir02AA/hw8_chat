<?php
include_once "../vendor/autoload.php";
include_once "autoloader.php";
include_once "../back/user_functions.php";
//echo "<pre>";
//var_dump(@$_SESSION);
//echo "<pre>";
if (!session_id()) session_start();
$saver = \back\Saver::getSaverObject();
if (isset($_POST['submit'])) {
    if (@$_FILES['pic']['name'] != "" && strlen($_POST['msg']) <= 100) {
        if (!\back\imageErrorCheck($_FILES['pic']['name'], $_FILES['pic']['size'])) {
//            echo "<pre>";
//            print_r($_FILES);
//            echo "<pre>";
            $imagesDIR = "../UsersData/msg Images/";
            $msg = [
                'id' => uniqid(),
                'text' => $_POST['msg'],
                'Image' => $imagesDIR.$_FILES['pic']['name'],
                'sender' => @$_SESSION['userName'],
                'time'=>time()
            ];
            $saver->addMassage($msg);
            if (!is_dir("../UsersData/msg Images/")) mkdir("../UsersData/msg Images/");
            move_uploaded_file($_FILES['pic']['tmp_name'], "../UsersData/msg Images/" . $_FILES['pic']['name']);
        } else {
            $error = \back\imageErrorCheck($_FILES['pic']['name'], $_FILES['pic']['size']);
            $error = "<label class='text-red-800'>$error</label>";
        }
    } elseif (strlen($_POST['msg']) <= 100) {
        $msg = [
            'id' => uniqid(),
            'text' => $_POST['msg'],
            'Image' => '',
            'sender' => @$_SESSION['userName'],
            'time'=>time()
        ];
        $saver->addMassage($msg);
    }
}
if (isset($_POST['delete']) && $saver->isAdmin()) {
    $saver->deleteMassage($_POST['delete']);
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
<body class="flex flex-col">
<header class="fixed bg-gray-800 flex gap-3 justify-between w-full py-5 px-5">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 12 12">
        <path fill="white"
              d="M4 5.5a.5.5 0 0 1 .5-.5h3a.5.5 0 1 1 0 1h-3a.5.5 0 0 1-.5-.5ZM4.5 7a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2ZM1 6a5 5 0 1 1 2.59 4.382l-1.944.592a.5.5 0 0 1-.624-.624l.592-1.947A4.98 4.98 0 0 1 1 6Zm5-4a4 4 0 0 0-3.417 6.08a.5.5 0 0 1 .051.406l-.383 1.259l1.257-.383a.5.5 0 0 1 .407.052A4 4 0 1 0 6 2Z"/>
    </svg>
    <div class="flex gap-3 justify-end">
        <div class="flex justify-end gap-3 px-5">
            <a>Contacts</a>
            <a href="mainPage.php">profile</a>
            <a>theme</a>
            <a>about us</a>
        </div>
</header>
<main class="flex flex-col pt-24 min-h-screen bg-gray-600 pb-2">
    <section class="flex flex-col gap-3 bg-gray-600 px-6 py-3 flex-1">
        <div id="messages" class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-w-2">
            <?php foreach ($saver->getMassages() as $item => $massage) {
//                var_dump($massage);
                if ($massage['sender'] == @$_SESSION['userName']) {
                    ?>
                    <div class="chat-message myMSG">
                        <div class="flex items-end justify-end">
                            <div class="flex flex-col space-y-2 text-xl max-w-xs mx-2 order-1 items-end">
                                <div>
                                    <?php
                                    if ($massage['Image']) { ?>
                                        <img src="<?= $massage['Image'] ?>" width="200px" height="auto">
                                    <?php }
                                    ?>
                                    <span
                                        class="px-4 py-2 rounded-lg inline-block
                                        rounded-br-none bg-blue-600 text-white ">
                                        <?= $massage['text'] ?>
                                    </span>

                                </div>
                            </div>
                            <img
                                src="<?= $saver->getImagesOfUser($massage['sender'])[0] ?>"
                                alt="My profile" class="w-14 h-14 rounded-full order-2">
                            <?php if ($saver->isAdmin()) { ?>
                                <form method="post">
                                    <button type="submit" name="delete" value="<?= $massage['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red"
                                             class="bi bi-x-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path
                                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="chat-message othersMSG">
                        <div class="flex items-end">
                            <div class="flex flex-col space-y-2 text-xl max-w-xs mx-2 order-2 items-start">
                                <div>
                                    <?php
                                    if ($massage['Image']) { ?>
                                        <img src="<?= $massage['Image'] ?>" width="200px" height="auto">
                                    <?php }
                                    ?>
                                    <span
                                        class="px-4 py-2 rounded-lg inline-block
                                        rounded-br-none bg-gray-200 text-black ">
                                        <?= $massage['text'] ?>
                                    </span>

                                </div>
                            </div>
                            <img
                                src="<?= $saver->getImagesOfUser($massage['sender'])[0] ?>"
                                alt="My profile" class="w-14 h-14 rounded-full order-1">
                            <?php if ($saver->isAdmin()) { ?>
                                <form method="post">
                                    <button type="submit" name="delete" value="<?= $massage['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red"
                                             class="bi bi-x-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path
                                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </button>
                                </form>
                            <?php } ?>
                        </div>

                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </section>
    <section class="<?= ($saver->isBlocked(@$_SESSION['userName'])) ? "hidden" : "" ?>">
        <div class="bg-gray-600 px-4 pt-4 mb-2 sm:mb-0">
            <span id="msgSpan" class="text-green-700 bg-gray-200 py-2 px-2 rounded font-bold">100
            </span>
            <span class="px-2 py-2 bg-gray-200">
                <?php
                if (isset($error)) echo $error;
                ?>
            </span>
            <form method="post" enctype="multipart/form-data">
                <div class="relative flex">
                    <input type="text" placeholder="Write your message!"
                           class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600
                               placeholder-gray-600 pl-12 bg-gray-200 rounded-md py-3"
                           name="msg" id="msg">

                    <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
                        <label for="uploadPic" class="inline-flex items-center justify-center rounded-full h-10 w-10
                            transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" class="h-6 w-6 text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </label>

                        <input type="file" hidden="hidden" name="pic" id="uploadPic">

                        <button type="button"
                                class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" class="h-6 w-6 text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        <button type="submit" name="submit"
                                class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                            <span class="font-bold">Send</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 class="h-6 w-6 ml-2 transform rotate-90">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>
</body>
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('#msg').on("input", function (event) {
            const chars = $('#msg').val().length;
            let span = $('#msgSpan');
            span.text(100 - chars);
            console.log(chars);
            if (chars >= 100) {
                span.removeClass("text-green-700");
                span.addClass("text-red-700");
            } else {
                span.removeClass("text-red-700");
                span.addClass("text-green-700");
            }
        })
    })
</script>
</html>
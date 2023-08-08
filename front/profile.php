<?php
session_start();
$userName = "User1";

if(!is_dir($userName)) mkdir($userName);

if(isset($_POST['delete'])) {
    if(is_file($_POST['delete'])) unlink($_POST['delete']);
}

if(isset($_POST['submit'])){
    $file = $_FILES['file'];
    $fileName = $file['name'];
    move_uploaded_file($file['tmp_name'],"../UsersData/$userName/$fileName");
    $bio= $_POST['bio'];
    file_put_contents("../UsersData/$userName/bio.txt",$bio);
}
$properties = scandir($userName);
$srcs = [];
foreach($properties as $key => $item)
{
$srcs[$key] = "./".$userName."/".$item;
$properties[$key]=realpath("./".$userName."/".$item);
}

$imageTypes = ['png','jpeg','jpg','icon'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" enctype="multipart/form-data" method="post">
<input type="text" name="bio" placeholder="bio" >
<input type="file" name="file">
<input type="submit" name="submit">
    </form>


    
    <?php 

    foreach($properties as $key => $file){
        $ext = pathinfo($file,PATHINFO_EXTENSION);
        if(!in_array($ext,$imageTypes)) unset($properties[$key]);
    }
    ?>
   
    <?php
    foreach($properties as $key => $file){
    ?>
    <form method="post">
        <div>
        <img src='<?=$srcs[$key]?>' >
        
        <button type="submit" name="delete" value='<?= $file ?>'> Delete <?= $file ?> </button>
        </div>
    <?php } ?>
    </form>
</body>
</html>


<?php
    require_once "usesession.php";
    // session_start();
    // //kas on sisse logitud
    // if(!isset($_SESSION["user_id"])){
    //     header("Location: page.php");
    // }
    // //välja logimine
    // if(isset($_GET["logout"])){
    //     session_destroy();
    //     header("Location: page.php");
    // }

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8"><title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
    <h1>Tere tulemast <?php echo $_SESSION["user_first_name"] ." ".$_SESSION["user_last_name"]; ?></h1> <!--Lisan juurde sisselogija nime-->
    <p>See leht on valminud õppetöö raames!</p>
    <hr>
    <p>Vajuta <a href = "show_news.php"> siia</a> kui soovid uudiseid lugeda!</p>
    <p>Vajuta <a href = "add_news.php"> siia</a> kui soovid uudiseid lisada!</p>
    <p><a href = "upload_photo.php">Fotode üleslaadimine!</a></p>
    <p>Siit leiad avalike piltide <a href = "gallery.php">galerii!</a></p> <!--Lisan galerii nupu-->
    <p><a href="?logout=1">Logi välja</a></p>
</body>
</html>
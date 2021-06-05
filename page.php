<?php

    //session_start();
    require("classes/SessionManager.class.php");
    SessionManager::sessionStart("vr", 0, "/~kaspar.reisenbuk/", "tigu.hk.tlu.ee");

    require_once "../../../conf.php";
    require_once "fnc_general.php";
    require_once "fnc_user.php";

    $myname = "Kaspar Reisenbuk";
    $currenttime = date("d.m.Y H:i:s");
    $timehtml = "\n <p>Lehe avamise hetkel oli: " .$currenttime .".</p>";
    $semesterbegin = new DateTime("2021-1-25");
    $semesterend = new DateTime("2021-6-30");
    $semesterduration = $semesterbegin->diff($semesterend);
    $semesterdurationdays = $semesterduration->format("%r%a");
    $semesterdurhtml = "\n <p>2021 kevadsemestri kestus on " .$semesterdurationdays ." päeva.</p> \n";
    $today = new DateTime("now");
    $fromsemesterbegin = $semesterbegin->diff($today);
    $fromsemesterbegindays = $fromsemesterbegin->format("%r%a");
    
    //Kodune Osa 3
    $weekdays = ["pühapäev", "esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev"];  //Lisan päevad listi
    $currentweekday = $weekdays[date("w")]; //Kasutan inglise pärast, sest ladusin nädala alates pühapäevast.
    $today = "<p> Täna on " .$currentweekday ."</p>"; //Kuvab tänast päeva


    //Kodune Osa 2
    //Kui semester on peal.
    if($fromsemesterbegindays <= $semesterdurationdays){
        $semesterprogress = "\n" .'<p>Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"> </meter>.</p>' ."\n";
    }
    //Kui semester ei ole veel alanud.
    elseif($currenttime < $semesterbegin){
        $semesterprogress = "\n <p>Kevadsemester pole veel alanud</p> \n";
    }
    //Kui semester on lõppenud.
    else{
        $semesterprogress = "\n <p>Semester on lõppenud</p> \n";
    }

    

    $picsdir = "../pics/";      //Loeme piltide kataloogi sisu.
    $allfiles = array_slice(scandir($picsdir), 2);
    //echo $allfiles[5]; 
    //var_dump($allfiles);
    $allowedphototypes = ["image/jpeg", "image/png"];
    $picfiles = [];

    //for($x = 0; $x <10;$++){
        //tegevus
    //}

    foreach($allfiles as $file){                                 //For tsükkel, et leida kõikide failide alt ainult pildifailid
        $fileinfo = getimagesize($picsdir .$file);               //Küsin pildi suurust
        if(isset($fileinfo["mime"])) {                           //Otsime faili infost selle "mime" väärtuse
            if(in_array($fileinfo["mime"], $allowedphototypes)){ //Kui infos on "mime" ja tüüp on lubatud
                array_push($picfiles, $file);                    //siis lükkan selle massiivi
            }
        }
    }
    
         //KODUNE OSA 1

    $photocount = count($picfiles); //Loen piltide arvu
    $randomnumberarray =[];         //Loon listi  kuhu pannakse randomiga leitud pildid
    do{                             //Loon loobi, mis loendab pilte kolme haaval.
        $photonum = mt_rand(0, $photocount-1);  //Leian suvalise pildi mt_rand funktsiooniga
        $randomphoto = $picfiles[$photonum]; 
        if(array_search($randomphoto, $randomnumberarray, false)); //Kui pilti veel listis ei ole
            array_push($randomnumberarray, $randomphoto);          //Siis selle funktsiooniga lisan
    }   while (count($randomnumberarray) <3);                      //Kuni pilte on vähem kui kolm.

    //Need on kolm "suvalist ja loositud pilti"
    $image1 = $randomnumberarray[0];
    $image2 = $randomnumberarray[1];
    $image3 = $randomnumberarray[2];
    //Sisselogimine
    $notice = null;
    $email = null;
    $email_error = null;
    $password_error = null;
    //Lisan tsükkli kontrollimaks kasutajanime ja parooli.
    if(isset($_POST["login_submit"])){
		if (isset($_POST["email_input"]) and !empty($_POST["email_input"])){
			$email = test_input($_POST["email_input"]);
		} else {
		  $email_error = "Palun sisestage oma e-postiaadress!";
		}
	    if (!isset($_POST["password_input"]) or strlen($_POST["password_input"]) < 8){
		  $password_error = "Palun sisestage parool!";
		}	  
		if(empty($email_error) and empty($password_error)){
		   $notice = sign_in($email, $_POST["password_input"]);
		} else {
			$notice = "Sisselogimine ebaõnnestus!";
		}
	}


?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8"><title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
    <h1>
    <?php
        echo $myname;
    ?>
    </h1>
    <p>See leht on valminud õppetöö raames!</p>
    <hr>
    <h2>Logi sisse</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>E-mail (kasutajatunnus):</label><br>
    <input type="email" name="email_input" value="<?php echo $email; ?>"><span><?php echo $email_error; ?></span><br>
    <label>Salasõna:</label><br>
    <input name="password_input" type="password"><span><?php echo $password_error; ?></span><br>
    <input name="login_submit" type="submit" value="Logi sisse!"><span><?php echo $notice; ?></span>
    </form>
    <p>Loo endale <a href = "add_user.php">kasutajakonto!</a></p> <!--Lisame konto loomise nupu-->
    <hr>
    <?php
        echo $timehtml;
        echo $semesterdurhtml;
        echo $semesterprogress;
        echo $today;
    ?>
    <!--Sellega kuvan oma loositud pilte-->
    <img src="<?php echo $picsdir .$image1; ?>" alt="vaade Haapsalus">
    <img src="<?php echo $picsdir .$image2; ?>" alt="vaade Haapsalus">
    <img src="<?php echo $picsdir .$image3; ?>" alt="vaade Haapsalus">
</body>
</html>
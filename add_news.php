<?php

    require_once "../../../conf.php";
    require_once "fnc_general.php";
    require_once "usesession.php";
    //echo $server_host;
    $news_input_error = NULL;
    //var_dump($_POST); //On olemas ka $_GET, selle käsuga saab vormi sisu oma lehel kuvada.
     $news_title = NULL;
     $news_content = NULL;
    if(isset($_POST["news_submit"])){
		if(empty($_POST["news_title_input"])){
			$news_input_error = "Uudise pealkiri on puudu! ";
		}else{ 														
			$news_title = $_POST["news_title_input"];				
		}
		if(empty($_POST["news_content_input"])){
			$news_input_error .= "Uudise sisu on puudu!";
		}else{														
			$news_content = $_POST["news_content_input"];			
		}
		if(empty($news_input_error)){
			//salvestame andmebaasi
			store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"]);
		}
	}

    function store_news($news_title, $news_content, $news_author){
        //echo $news_title .$news_content .$news_author;
        //loome andmebaasis serveriga ja baasiga ühenduse
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        //määran kodeeringu
        $conn -> set_charset ("utf8");
        //valmistan ette SQL Käsu
        $stmt = $conn -> prepare("INSERT INTO vr21_news (vr21_news_news_title, vr21_news_news_content, vr21_news_news_author) VALUES (?,?,?)");
        echo $conn -> error;            //i on integer, s on string, d on deciaml.
        $stmt -> bind_param("sss", $news_title, $news_content, $news_author);
        $stmt -> execute();
        $stmt -> close();
        $conn -> close(); 
        $GLOBALS["news_input_error"] = NULL;
        $GLOBALS["news_title"] = NULL;
        $GLOBALS["news_content"] = NULL;
        $GLOBALS["news_author"] = NULL;
    } 

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8"><title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
    <h1>Uudiste lisamine</h1>
    <p>See leht on valminud õppetöö raames!</p>
    <hr>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  <! -- Post meethod saadab andmed ära nii, et lingil ei ole näha kogu sisu. -->                      
        <label for="news_title_input">Uudise pealkiri</label>
        <br>
        <input type="text" id="news_title_input" name="news_title_input" placeholder="Uudise pealkiri" value="<?php echo $news_title; ?>">
        <br>
        <label for="news_content_input">Uudise sisu</label>
        <br>
        <textarea id="news_content_input" name="news_content_input" placeholder="Uudise sisu" rows="6" cols="40"><?php echo $news_content; ?></textarea>
        <br>
        <label for="news_author_input">Uudise lisaja nmi</label>
        <br>
        <input type="text" id="news_author_input" name="news_author_input" placeholder="Nimi">
        <br>
        <input type="submit" name="news_submit" value="Salvesta uudis!">
    </form>
    <p><?php echo $news_input_error; ?></p>
</body>
</html>
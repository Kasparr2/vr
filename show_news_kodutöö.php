<?php

    require_once "../../../conf.php";
    require_once "fnc_general.php";

    function read_news(){
        if(isset($_POST["show_news"])) {
        $count_news = $_POST['count_news']; //Kui kasutaja sisestab numbri kuvatakse väljund
        } else {                          //"BY DEFAULT" on 6
            $count_news = 6;
        }
        //loome andmebaasis serveriga ja baasiga ühenduse
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        //määran kodeeringu
        $conn -> set_charset ("utf8");
        //valmistan ette SQL Käsu
        //Täiendan sql käsku nii, et uudised oleks id järgi kahanevas järjekorras
        //Täiendan käsku veel LIMIT-iga
        $stmt = $conn -> prepare("SELECT vr21_news_news_title, vr21_news_news_content, vr21_news_news_author, vr21_news_added FROM vr21_news ORDER BY vr21_news_id DESC LIMIT ?");
        echo $conn -> error;
        //i - integer   s - string   d - decimal
        $stmt -> bind_result($news_title_from_db, $news_content_from_db, $news_author_from_db, $news_added_from_db);
        $stmt -> bind_param("s", $count_news);
        $stmt -> execute();
        $raw_news_html = null;

        while ($stmt -> fetch()){
            $raw_news_html .= "\n <h2>" .$news_title_from_db ."</h2>";
            $news_date = new DateTime($news_added_from_db);     //Lisan postitustele aja.
            $raw_news_html .= "\n <p> Lisatud: " .$news_date -> format("m.d.Y H:i") . "</p>"; //Kuvan aja välja pealkirja alla.
            $raw_news_html .= "\n <p>" .nl2br($news_content_from_db) ."</p>";
            $raw_news_html .= "\n <p>Edastas: ";
            if(!empty($news_author_from_db)){
                $raw_news_html .= $news_author_from_db;
            } else {
                $raw_news_html .= "Tundmatu reporter";
            }
            $raw_news_html .= "</p>";
        }
        $stmt -> close();
        $conn -> close();
        return $raw_news_html;
    }
    $news_html = read_news();

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8"><title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
    <h1>Uudiste lugemine</h1>
    <p>See leht on valminud õppetöö raames!</p>
    <form method="POST"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!-- Sellega kuvan uudised välja. -->
	<input type="number" min="0" max="10" value="" name="count_news">
	<input type="submit" name="show_news" value="Kuva uudised">
	</form>
    <hr>
    <?php echo $news_html; ?>
</body>
</html>
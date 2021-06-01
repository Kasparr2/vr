<?php
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
    $semesterprogress = "\n" .'<p>Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"> </meter>.</p>' ."\n";
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
    <?php
        echo $timehtml;
        echo $semesterdurhtml;
        echo $semesterprogress;
    ?>
</body>
</html>
<?php

require_once "../../../conf.php";
require_once "fnc_upload_photo.php";

$gallery_pictures = thumbnail_gallery();

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<h1>Galerii</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
    <div class="gallery">
    <?php echo $gallery_pictures; ?>
	</div>
    <p><a href = "page.php">Avalehele</a></p>
</body>
</html>
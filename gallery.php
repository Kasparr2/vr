<?php
require_once "usesession.php";
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
	<link rel="stylesheet" href="modal.css">
	<script src="javascript/modal.js" defer></script>
</head>
<body>
  <!--Modaalaken fotogalerii jaoks-->
  <div id="modalarea" class="modalarea">
	<!--sulgemisnupp-->
	<span id="modalclose" class="modalclose">&times;</span>
	<!--pildikoht-->
	<div class="modalhorizontal">
		<div class="modalvertical">
			<p id="modalcaption"></p>
			<img id="modalimg" src="../images/empty.png" alt="galeriipilt">
			  <br>
			<div id="rating" class="modalRating">
				<label><input id="rate1" name="rating" type="radio" value="1">1</label>
				<label><input id="rate2" name="rating" type="radio" value="2">2</label>
				<label><input id="rate3" name="rating" type="radio" value="3">3</label>
				<label><input id="rate4" name="rating" type="radio" value="4">4</label>
				<label><input id="rate5" name="rating" type="radio" value="5">5</label>
				<button id="storeRating">Salvesta hinnang!</button>
				<br>
				<p id="avgRating"></p>
			</div>
		</div>
	</div>
  </div>
	<h1>Galerii</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
    <div id="gallery" class="gallery">
    <?php echo $gallery_pictures; ?>
	</div>
    <p><a href = "page.php">Avalehele</a></p>
</body>
</html>
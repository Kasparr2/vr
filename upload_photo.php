<?php

	require_once "usesession.php";
	require_once "../../../conf.php";
	require_once "fnc_general.php";
	require_once "fnc_upload_photo.php";
	require_once "classes/Upload_photo.class.php";

	$photo_upload_error = null;
	$image_file_type = null;
	$image_file_name = null;
	$file_name_prefix = "vr_";
	$file_size_limit = 2 * 1024 * 1024;     //Arvutame pildi suuruse välja, 2 MB
	$image_max_w = 600;
	$image_max_h = 400;
	$notice = null;
	$watermark = "../images/vr_watermark.png";

    if(isset($_POST["photo_submit"])){
		$photo_upload = new Upload_photo($_FILES["file_input"],$file_size_limit);		//Võtame kasutusele Upload_photo klassi
		$photo_upload_error .= $photo_upload->photo_upload_error;
		if(empty($photo_upload->photo_upload_error)){		//Muudame suurust.
			$photo_upload->resize_photo($image_max_w, $image_max_h);
			$photo_upload->add_watermark($watermark);		// lisan vesimärgi
			$image_file_name = $photo_upload->generate_filename();	//salvestame pikslikogumi faili
			$target_file = "../upload_photos_normal/" .$image_file_name;
			$result = $photo_upload->save_image_to_file($target_file, false);
			if($result == 1) {
				$notice = "Vähendatud pilt laeti üles! ";
			} else {
				$photo_upload_error = "Vähendatud pildi salvestamisel tekkis viga!";
			}
			$photo_upload->resize_photo(100, 100, false);  //teen pisipildi parameetrid 100 px x 100 px
			$target_file = "../upload_photos_thumbnail/" .$image_file_name;		//salvestame pisipildi faili
			$result = $photo_upload->save_image_to_file($target_file, false);
			if($result == 1) {
				$notice .= " Pisipilt laeti üles! ";
			} else {
				$photo_upload_error .= " Pisipildi salvestamisel tekkis viga!";
			}
			$target_file = "../upload_photos_orig/" .$_FILES["file_input"]["name"];
			$result = $photo_upload->save_image_to_file($target_file, true);
			if($result == 1){
				$notice .= " Originaalfoto üleslaadimine õnnestus!";
			} else {
				$photo_upload_error .= " Originaalfoto üleslaadimine ebaõnnestus!";
			}
			$photo_upload_error = $photo_upload->photo_upload_error;
			unset($photo_upload);
			if($photo_upload_error == null){       //Kui vigu ei ole saadame pildi andmebaasi.
				$result = store_photo_data($image_file_name, $_POST["alt_text"], $_POST["privacy_input"], $_FILES["file_input"]["name"]);
				if($result == 1){
					$notice .= " Pildi andmed lisati andmebaasi!";
				} else {
					$photo_upload_error = "Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: ";
				}
			}
		}
	}
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8"><title>Veebirakendused ja nende loomine 2021</title>
	<script src="javascript/checkImageSize.js" defer></script>
</head>
<body>
    <h1>Fotode lisamine</h1>
    <p>See leht on valminud õppetöö raames!</p>
    <hr>
    <form method = "POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype = "multipart/form-data">  <! -- Post meethod saadab andmed ära nii, et lingil ei ole näha kogu sisu. -->
        <label for = "file_input">Vali foto fail!</label>
        <input id = "file_input" name = "file_input" type = "file">
        <br>
		<label for="alt_input">Alternatiivtekst ehk pildi selgitus</label>
		<input id="alt_text" name="alt_text" type="text" placeholder="Pildil on ...">
		<br>
        <label>Privaatsustase: </label>
        <br>
        <input id = "privacy_input_1" name = "privacy_input" type = "radio" value = "3" checked>
        <label for = "privacy_input_1">Privaatne </label>
        <br>
        <input id = "privacy_input_2" name = "privacy_input" type = "radio" value = "2">
        <label for = "privacy_input_2">Registreeritud kasutajatele</label>
        <br>
        <input id = "privacy_input_3" name = "privacy_input" type = "radio" value = "1">
        <label for = "privacy_input_3">Avalik</label>
        <br>
        <input type="submit" id="photo_submit" name="photo_submit" value="Lae pilt üles!">
    </form>
    <p><a href = "home.php">Avalehele</a></p>
    <p><a href="?logout=1">Logi välja</a></p>
    <p id="notice"><?php echo $photo_upload_error; echo $notice; ?></p>
</body>
</html>
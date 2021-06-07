<?php

	require_once "usesession.php";
	require_once "../../../conf.php";
	require_once "fnc_general.php";
	require_once "fnc_upload_photo.php";
	//echo $server_host;
    //var_dump($_POST); //On olemas ka $_GET, selle käsuga saab vormi sisu oma lehel kuvada.
    //muutujad
	$photo_upload_error = null;
	$image_file_type = null;
	$image_file_name = null;
	$file_name_prefix = "vr_";
	$file_size_limit = 2 * 1024 * 1024;     //Arvutame pildi suuruse välja, 2 MB
	$image_max_w = 600;
	$image_max_h = 400;
	$notice = null;
    if(isset($_POST["photo_submit"])){
		//var_dump($_POST);
		//var_dump($_FILES);
		$check = getimagesize($_FILES["file_input"]["tmp_name"]);		//kas üldse on pilt
		if($check !== false){
			if($check["mime"] == "image/jpeg"){		//kontrollime, kas aktepteeritud failivorming ja fikseerime laiendi
				$image_file_type = "jpg";
			} elseif ($check["mime"] == "image/png"){
				$image_file_type = "png";
			} else {
				$photo_upload_error = "Pole sobiv formaat! Ainult jpg ja png on lubatud!";
			}
		} else {
			$photo_upload_error = "Tegemist pole pildifailiga!";
		}
		if(empty($photo_upload_error)){
			if($_FILES["file_input"]["size"] > $file_size_limit){		//Kontrollime, et fail liiga suur ei ole
				$photo_upload_error = "Valitud fail on liiga suur! Lubatud kuni 2MB!";
			}
			
			if(empty($photo_upload_error)){		//loome oma failinime
				$timestamp = microtime(1) * 10000;
				$image_file_name = $file_name_prefix .$timestamp ."." .$image_file_type; //."." lisame nimele juurde .
				$temp_image = null;
				if($image_file_type == "jpg"){		//suuruse muutmine ja loome pikslikogumi ehk image objekti
					$temp_image = imagecreatefromjpeg($_FILES["file_input"]["tmp_name"]);
				}
				if($image_file_type == "png"){
					$temp_image = imagecreatefrompng($_FILES["file_input"]["tmp_name"]);
				}
				$new_temp_image = resize_photo($temp_image, $image_max_w, $image_max_h, false);    //kasutan foto suuruse muutmise funktsiooni ja lisan ka false sest muidu ei tule õige suurus.
				
				$target_file = "../upload_photos_normal/" .$image_file_name;    //salvestame pikslikogumi faili
				$result = save_image_to_file($new_temp_image, $target_file, $image_file_type);
				if($result == 1) {
					$notice = "Vähendatud pilt laeti üles! ";
				} else {
					$photo_upload_error = "Vähendatud pildi salvestamisel tekkis viga!";
				}
				$new_temp_image = resize_photo($temp_image, 100, 100, false); //teen pisipildi ja määran suuruseks 100px 100px
				$target_file = "../upload_photos_thumbnail/" .$image_file_name;     //salvestame pisipildi faili
				$result = save_image_to_file($new_temp_image, $target_file, $image_file_type);
				//echo $result;
				if($result == 1) {
					$notice .= " Pisipilt laeti üles! ";
				} else {
					$photo_upload_error .= " Pisipildi salvestamisel tekkis viga!";
				}
				$target_file = "../upload_photos_orig/" .$image_file_name;
				if(move_uploaded_file($_FILES["file_input"]["tmp_name"], $target_file)){
					$notice .= " Originaalfoto üleslaadimine õnnestus!";
				} else {
					$photo_upload_error .= " Originaalfoto üleslaadimine ebaõnnestus!";
				}
			}
			if($photo_upload_error == null){       //Kui vigu ei ole saadame pildi andmebaasi.
				$result = store_photo_data($image_file_name, $_POST["alt_input"], $_POST["privacy_input"], $_FILES["file_input"]["name"]);
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
        <input type="submit" name="photo_submit" value="Lae pilt üles!">
    </form>
    <p><a href = "home.php">Avalehele</a></p>
    <p><a href="?logout=1">Logi välja</a></p>
    <p><?php echo $photo_upload_error; echo $notice; ?></p>
</body>
</html>
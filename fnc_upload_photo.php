<?php
	function thumbnail_gallery() {
		$notice = 0;
		$privacy = 2;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vr21_photos.vr21_photos_id, vr21_photos.vr21_photos_filename, vr21_photos.vr21_photos_alttext, vr21_users.vr21_users_firstname, vr21_users.vr21_users_lastname FROM vr21_photos JOIN vr21_users ON vr21_photos.vr21_photos_userid = vr21_users.vr21_users_id WHERE vr21_photos.vr21_photos_privacy <= ? AND vr21_photos.vr21_photos_deleted IS NULL GROUP BY vr21_photos.vr21_photos_id");
		echo $conn -> error;
		$stmt -> bind_param("i", $privacy);
		$stmt -> bind_result($photo_id_from_db, $photo_filename_from_db, $photo_alttext_from_db, $user_firstname_from_db, $user_lastname_from_db);
		$stmt -> execute();
		$gallery_pictures = null;
	
		while ($stmt -> fetch()) {
			
			$gallery_pictures .= '<div class="picture">';
			$gallery_pictures .= '<img src="../upload_photos_thumbnail/' .$photo_filename_from_db .'" alt="' .$photo_alttext_from_db .'" class="thumbnail" data-fn="' .$photo_filename_from_db .'" data-id="' .$photo_id_from_db .'">';
			$gallery_pictures .= '<p>'.$user_firstname_from_db ." " .$user_lastname_from_db .'</p></div>';
	
		}
		$stmt -> close();
		$conn -> close();
		return $gallery_pictures;
	
	}
	function store_photo_data($image_file_name, $alt, $privacy, $orig_name){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vr21_photos (vr21_photos_userid, vr21_photos_filename, vr21_photos_alttext, vr21_photos_privacy, vr21_photos_origname) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["user_id"], $image_file_name, $alt, $privacy, $orig_name);
		if($stmt->execute()){
		  $notice = 1;
		} else {
		  $notice = $stmt->error;
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
<?php
	class Upload_photo {
		private $photo_to_upload;
		private $image_file_type;
		private $temp_image;
		private $new_temp_image;
		public $photo_upload_error;
		
		function __construct($photo_to_upload, $file_size_limit){
			$this -> photo_to_upload = $photo_to_upload;
			$this->image_file_type = $this->check_image_type($this->photo_to_upload["tmp_name"], $file_size_limit);
			$this->temp_image = $this->create_image_from_file($this->photo_to_upload["tmp_name"], $this->image_file_type);
		}
		function __destruct(){  //Lõpetab konstruktori.
			if(isset($this->new_temp_image)){
				@imagedestroy($this -> new_temp_image);
			}
			if(isset($this -> temp_image)){
				imagedestroy($this -> temp_image);
			}
		}
		private function check_image_type($image_file_type, $file_size_limit) {			//// kontrollime kas on pilt ja mis tüüpi
			if($this->photo_to_upload["size"] > $file_size_limit){
				return $this->photo_upload_error .= "Valitud fail on liiga suur! Lubatud kuni 2MB!";
			} else {
				if($image_file_type !== false and !empty($image_file_type)){	////kontrollime, kas aktepteeritud failivorming ja fikseerime laiendi
					if(getimagesize($image_file_type)["mime"] == "image/jpeg"){
						return $image_file_type = "jpg";
					} elseif (getimagesize($image_file_type)["mime"] == "image/png"){
						return $image_file_type = "png";
					} else {
						return $this->photo_upload_error .= "Pole sobiv formaat! Ainult jpg ja png on lubatud!";
					}
				} else{
					return $this->photo_upload_error .= "Palun valige fail või kontrollige faili suurust! Lubatud on kuni 2MB!";
				}
			}
		}
		private function create_image_from_file($image, $image_file_type){  //Looome pikslikogumi
			$temp_image = null;
			if($image_file_type == "jpg"){
				$temp_image = imagecreatefromjpeg($image);
			}
			if($image_file_type == "png"){
				$temp_image = imagecreatefrompng($image);
			}
			return $temp_image;
		}
		public function resize_photo($w, $h, $keep_orig_proportion = true){
			$image_w = imagesx($this -> temp_image);
			$image_h = imagesy($this -> temp_image);
			$new_w = $w;
			$new_h = $h;
			$cut_x = 0;
			$cut_y = 0;
			$cut_size_w = $image_w;
			$cut_size_h = $image_h;
			
			if($w == $h){
				if($image_w > $image_h){
					$cut_size_w = $image_h;
					$cut_x = round(($image_w - $cut_size_w) / 2);
				} else {
					$cut_size_h = $image_w;
					$cut_y = round(($image_h - $cut_size_h) / 2);
				}	
			} elseif($keep_orig_proportion){//kui tuleb originaaproportsioone säilitada
				if($image_w / $w > $image_h / $h){
					$new_h = round($image_h / ($image_w / $w));
				} else {
					$new_w = round($image_w / ($image_h / $h));
				}
			} else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
				if($image_w / $w < $image_h / $h){
					$cut_size_h = round($image_w / $w * $h);
					$cut_y = round(($image_h - $cut_size_h) / 2);
				} else {
					$cut_size_w = round($image_h / $h * $w);
					$cut_x = round(($image_w - $cut_size_w) / 2);
				}
			}
			$this -> new_temp_image = imagecreatetruecolor($new_w, $new_h);		//loome uue ajutise pildiobjekti
			imagecopyresampled($this -> new_temp_image, $this -> temp_image, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
		}
		public function save_image_to_file($target, $keep_orig_photo){
			$notice = null;
			if( $keep_orig_photo == false){
				if($this->image_file_type == "jpg"){
					if(imagejpeg($this->new_temp_image, $target, 90)){
						$notice = 1;
					} else {
						$notice = 0;
					}
				}
				if($this->image_file_type == "png"){
					if(imagepng($this->new_temp_image, $target, 6)){
						$notice = 1;
					} else {
						$notice = 0;
					}
				}
				imagedestroy($this->new_temp_image);
			}
			if($keep_orig_photo) {
				if(move_uploaded_file($this->photo_to_upload["tmp_name"], $target)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			@imagedestroy($this -> new_temp_image);      //mälu tühjendus, ei peaks tegema aga ilus oleks.
			return $notice;
		}
		public function add_watermark($watermark){  //Lisame vesimärgi
			$watermark_file_type = strtolower(pathinfo($watermark, PATHINFO_EXTENSION)); //strtolower konverdib stringi tähed väikseks
			$watermark_image = $this -> create_image_from_file($watermark, $watermark_file_type);
			$watermark_w = imagesx($watermark_image);
			$watermark_h = imagesy($watermark_image);
			$watermark_x = imagesx($this -> new_temp_image) - $watermark_w - 10;
			$watermark_y = imagesy($this -> new_temp_image) - $watermark_h - 10;
			imagecopy($this -> new_temp_image, $watermark_image, $watermark_x, $watermark_y, 0, 0, $watermark_w, $watermark_h);  //Parameetrid, kus võta pildi, kuhu paned ja kordinaadid.
			imagedestroy($watermark_image);
		}
		public function generate_filename(){	// genereerime failile nime
		$timestamp = microtime(1) * 10000;
		$file_name_prefix = "vr_";		//lisame nime algusesse vr_
		return $image_file_name = $file_name_prefix .$timestamp ."." .$this->image_file_type;
		}	
	}//class lõppeb
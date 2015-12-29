<?php

class ImageProcessor {
	
	public static function MakeThumbnails($sourcePath,$outputPath, $usePercentage, $shrinkValue)
	{
		$files = scandir($sourcePath);
		unset($files[0]);
		unset($files[1]);

		foreach ($files as $key => $value) 
		{
			echo $value."<br/>";

			$extenstion= self::ext($value);

		    if($extenstion==="jpg" || $extenstion==="jpeg" || $extenstion==="gif" ||  $extenstion==="png")
		    {
		    	if($usePercentage)
		    		$img = self::resize_image_percent($sourcePath."/".$value, $shrinkValue, $shrinkValue);
		    	else
		    		$img = self::resize_image_1D_absolute($sourcePath."/".$value, $shrinkValue);
				imagejpeg($img, $outputPath."/".$value);
				imagedestroy($img);   // Free up memory
		    }
		    else
		       	echo $value." does not have jpg/jpeg/gif/png extenstion<br>";
		}

		return true;

	}

	public static function RotateManyImages($sourcePath,$degrees)
	{
		$files = scandir($sourcePath);
		unset($files[0]);
		unset($files[1]);

		foreach ($files as $key => $value) 
		{
			echo $value."<br/>";
			self::RotateImage($sourcePath,$value,$degrees);
		}
	}

	public static function RotateImage($sourcePath,$filename,$degrees)
	{

		// Content type
		// header('Content-type: image/jpeg');

		// Load
		$source = imagecreatefromjpeg($sourcePath.'/'.$filename);

		// Rotate
		$rotate = imagerotate($source, $degrees, 0);

		// Output
		imagejpeg($rotate,$sourcePath.'/'.$filename);

		// Free the memory
		imagedestroy($source);
		imagedestroy($rotate);
		return true;
	}

	private static function resize_image_percent($file, $w, $h) 
	{
	    list($width, $height) = getimagesize($file);

	    $newwidth=$w*$width;
	    $newheight=$h*$height;
	    
	    $extenstion=self::ext($file);

	    if($extenstion==="jpg" || $extenstion==="jpeg")
	    	$src = imagecreatefromjpeg($file);
		else if($extenstion==="png")
			$src= imagecreatefrompng($file);
		elseif ($extenstion==="gif") 
			$src= imagecreatefromgif($file);

	    $dst = imagecreatetruecolor($newwidth, $newheight);
	    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	    return $dst;
	}

	//
	private static function resize_image_1D_absolute($file, $dim)
	{
		list($width, $height) = getimagesize($file);
		$r=$width/$height;

		if($width>$height)
		{
			$newwidth=$dim;
			$newheight=$dim/$r;
		}
		else if($height>$width)
		{
			$newheight=$dim;
			$newwidth=$dim*$r;
		}
		else
		{
			$newwidth=$dim;
			$newheight=$dim;
		}

		$extenstion=self::ext($file);

	    if($extenstion==="jpg" || $extenstion==="jpeg")
	    	$src = imagecreatefromjpeg($file);
		else if($extenstion==="png")
			$src= imagecreatefrompng($file);
		elseif ($extenstion==="gif") 
			$src= imagecreatefromgif($file);

	    $dst = imagecreatetruecolor($newwidth, $newheight);
	    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	    return $dst;
	}

	private static function resize_image($file, $w, $h, $crop=FALSE) {
	    list($width, $height) = getimagesize($file);
	    $r = $width / $height;
	    if ($crop) {
	        if ($width > $height) {
	            $width = ceil($width-($width*abs($r-$w/$h)));
	        } else {
	            $height = ceil($height-($height*abs($r-$w/$h)));
	        }
	        $newwidth = $w;
	        $newheight = $h;
	    } else {
	        if ($w/$h > $r) {
	            $newwidth = $h*$r;
	            $newheight = $h;
	        } else {
	            $newheight = $w/$r;
	            $newwidth = $w;
	        }
	    }

	    $extenstion=self::ext($file);

	    if($extenstion==="jpg" || $extenstion==="jpeg")
	    	$src = imagecreatefromjpeg($file);
		else if($extenstion==="png")
			$src= imagecreatefrompng($file);
		elseif ($extenstion==="gif") 
			$src= imagecreatefromgif($file);

	    $dst = imagecreatetruecolor($newwidth, $newheight);
	    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	    return $dst;
	}

	private static function ext($file)
	{
		$lastdot=strripos($file, ".");
		$extenstion=substr($file, ($lastdot+1));
	    $extenstion=strtolower($extenstion);
	    return $extenstion;
	}

}

?>
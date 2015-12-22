<?php

class FileManager
{
	protected static $imagePath = '/images/';
	protected static $imageMimes = array(
		'image/gif'=>['gif'],
		'image/jpeg'=>['jpg','jpeg','jpe','jfif','JPG'],
		'image/png'=>['png'],
		'image/jp2'=>['jp2','jpx']);
	protected static $maxFileSize = 5242880;

	public static function uploadImage($fileData,$folder,$replace=false)
	{
		if (!$fileData->isValid())
			return array('success' => false, 'errors' => ['There was some error ins the file you uploaded']);

		// valid mime type?
		$mimeType = $fileData->getMimeType();
		if (!isset(self::$imageMimes[$mimeType]))
			return array('success' => false, 'errors' => ['Invalid image format']);

		// valid file extension as per mime type?
		$extension = $fileData->getClientOriginalExtension();
		if (!in_array($extension, self::$imageMimes[$mimeType]))
			return array('success' => false, 'errors' => ['Invalid image format']);

		// within max file size?
		$size = $fileData->getSize();
		if ($size > self::$maxFileSize)
			return array('success' => false, 'errors' => ['Files greater than 5MB not allowed.']);

		$fileName = $fileData->getClientOriginalName();
		$destinationPath = public_path().self::$imagePath.$folder.'/';
		$fullName = $destinationPath.$fileName;
		// echo $destinationPath;
		while (File::exists($fullName))
		{
			// echo 'in while';
			$fileName = uniqid(date('Y_m_d').'_', true) . '.' . $extension;
			$fullName = $destinationPath.$fileName;
		}
		// echo $fullName;
		// echo $fileName;
    	try
    	{
    		$fileData->move($destinationPath,$fileName);
    	}
        catch (Exception $e)
		{
			return array('success' => false, 'errors' => ['There was some error in saving the file']);
		}
        return array('success' => true, 'filename' => $fileName);
	}
}

?>
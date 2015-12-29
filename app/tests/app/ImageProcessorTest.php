<?php

class ImageProcessorTest extends TestCase 
{
	/*public function testMakingThumbnails()
	{
		$sp = public_path().'\images\graph-gallery';
		$op = public_path().'\images\graph-gallery-thumbs';
		$shrink = 0.5;

		$result = ImageProcessor::MakeThumbnails($sp,$op,1,$shrink);

		$this->assertTrue($result);
		
	}*/

	/*public function testRotation()
	{
		$path = public_path().'\images';
		$filename = 'image2.jpg';
		$result = ImageProcessor::RotateImage($path,$filename,360-90);

	}*/

	public function testRotationMultipleFiles()
	{
		$sp = public_path().'\images\imagestorotate';
		$result = ImageProcessor::RotateManyImages($sp,360-90);

	}
}
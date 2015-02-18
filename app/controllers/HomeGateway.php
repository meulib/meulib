<?php 

class HomeGateway extends BaseController
{

	public function showHome()
	{
		return View::make('home');		
	}

}
?>
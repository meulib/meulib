<?php 

class HomeGateway extends BaseController
{

	public function showHome()
	{
		// $books = FlatBook::getAllBooks();
		return View::make('home');		
	}

}
?>
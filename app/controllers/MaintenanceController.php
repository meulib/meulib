<?php

class MaintenanceController extends BaseController {

	public function updateSearch()
	{
		$results = Librarian::updateSearchTbl();
		var_dump($results);
	}

}
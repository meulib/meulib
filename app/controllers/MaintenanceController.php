<?php

class MaintenanceController extends BaseController {

	public function updateSearch()
	{
		$results = FlatBook::updateSearchTbl();
		var_dump($results);
	}

}
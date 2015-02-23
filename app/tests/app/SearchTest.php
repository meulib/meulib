<?php

class SearchTest extends TestCase 
{
	public function testSearchUpdation()
	{
		/*$results = Librarian::updateSearchTbl();
		var_dump($results);*/

		$results = Librarian::search('software');
		var_dump($results);
	}
}
<?php

class Librarian {

	public static function search($searchTerm)
	{
		// searches only book title right now

		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];

		// search search index
		$sql = "SELECT EntityID, EntityType, MATCH(Phrase) 
			AGAINST(:term1 IN BOOLEAN MODE) score FROM ".
			$tblPrefix."search_tbl WHERE MATCH(Phrase) 
			AGAINST(:term2 IN BOOLEAN MODE) order by score 
			asc limit 0,30";
		$searchResult = DB::select($sql,
			array('term1'=>$searchTerm,'term2'=>$searchTerm));
		// var_dump($searchResult);
		

		// retrieve only ids
		$bookIDsA = array_map(function($val)
					{
					    return $val->EntityID;
					}, $searchResult);
		// var_dump($bookIDsA);
		echo "<br/>";

		if (count($bookIDsA)>0)
		{
			// get book records for matching ids
			$books = FlatBook::select('ID','Title','Author1')
					->orderBy('ID','asc')
					->whereIn('ID', $bookIDsA)
					->get()
					->toArray();
			// var_dump($books);

			// sort search result by id
			usort($searchResult, function($a, $b)
					{
					    if ($a->EntityID == $b->EntityID) {
					        return 0;
					    }
					    return ($a->EntityID < $b->EntityID) ? -1 : 1;
					});
			// var_dump($searchResult);

			// combine book records and search results
			// so that book records may have search score
			$bIdx = $sIdx = 0;
			$finalArray = [];
			foreach ($books as $book) 
			{
				$found = true;
				$x = 0;
				do {
					if ($book['ID'] == $searchResult[$sIdx]->EntityID)
					{
						$finalArray[$bIdx] = $book;
						$finalArray[$bIdx]['Score'] = $searchResult[$sIdx]->score;
						$sIdx++;
						$bIdx++;
						$found = true;
						$x++;
					}
					else
					{
						//var_dump($book['ID']);
						//var_dump($searchResult[$sIdx]->EntityID);
						//echo "<br/>";
						$sIdx++;
						$found = false;
						$x++;
					}
				} while((!$found) && ($x < 2));
			}
			// var_dump($finalArray);

			// sort book records by search score (or title if score is same)
			usort($finalArray, function($a, $b)
					{
					    if ($a['Score'] == $b['Score']) {
					        return strcmp($a["Title"], $b["Title"]);
					    }
					    return ($a['Score'] > $b['Score']) ? -1 : 1;
					});
			return $finalArray;	
		}
		else
			return false;		
		
	}

	public static function membersByCountry($country)
	{
		return DB::table('users')
				->join('locations','users.LocationID','=','locations.ID')
				->select('users.FullName','users.Username','users.ProfilePicFile','users.LocationID', 'users.ClaimToFame')
				->orderBy('users.LocationID','asc')
				->orderBy('users.FullName','asc')
				->where('locations.Country','=',$country)
				->get();
	}

	public static function foundingMembers()
	{
		$founders = User::where('ClaimToFame','>',0)
			->orderBy('FullName','asc')
			->get();

		return $founders;
	}

	// ------------------- MAINTENANCE --------------

	public static function updateSearchTbl()
	{
		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];

		// which books are already in search tbl
		$existingIDs = DB::table('search_tbl')
						->select('EntityID')->distinct()
						->where('EntityType',1)->lists('EntityID');
		if (count($existingIDs) == 0)
			$existingIDs = [0];
		// which ids are not in search tbl
		$newIDs = DB::table('books_flat')->select('ID')->
			whereNotIn('ID', $existingIDs)->lists('ID');
		$newIDsString = implode(",", $newIDs);
		if (strlen($newIDsString) > 0)
		{
			// insert data abt new books into search tbl
			$sql = "insert into ".$tblPrefix."search_tbl (EntityID,Phrase,EntityType) select ID, concat(Title,' ',SubTitle,' ',Author1,' ',Author2), 1 from ".$tblPrefix."books_flat where ID in (".$newIDsString.") and deleted_at is not null";
			// var_dump($sql);
			$result = DB::statement($sql);
		}
		else
			$result = true;
		return $result;
	}

}

?>
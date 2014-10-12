<?php

class Location extends Eloquent {

	protected $table = 'locations';
	protected $primaryKey = 'ID';

	public function BookCopies()
	{
		return $this->hasMany('BookCopy', 'LocationID', 'ID');
	}

	public static function havingBooks()
	{
		return Location::has('BookCopies')
						->orderBy('Country','asc')
						->orderBy('Location', 'asc')						
						->get();
	}

	public static function newUserLocationID($city, $state, $country)
	{
		$combined = $country."--".$city;
		$location = false;
		switch ($combined)
		{
			case "India--Manipal":
			case "India--Udupi":
				$location = Location::where('Location', '=', 'Udupi-Manipal')
									->where('Country', '=', 'India')
									->first();
			case "India--Kolkata":
				$location = Location::where('Location', '=', 'Kolkata')
									->where('Country', '=', 'India')
									->first();
			case "USA--Boulder":
				if ($state == 'CO')
					$location = Location::where('Location', '=', 'Boulder CO')
									->where('Country', '=', 'USA')
									->first();
		}
		if ($location)
			return $location->ID;
		else
			return 0;
	}

}

?>
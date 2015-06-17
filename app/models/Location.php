<?php

class Location extends Eloquent {

	protected $table = 'locations';
	protected $primaryKey = 'ID';

	// relationship
	public function BookCopies()
	{
		return $this->hasMany('BookCopy', 'LocationID', 'ID');
	}

	public function Users()
	{
		return $this->hasMany('User','LocationID','ID');
	}

	public static function havingBooks()
	{
		$cacheKey = Config::get('app.cacheKeys')['allBooksLocations'];
		if (Cache::has($cacheKey))
		{
			return Cache::get($cacheKey);
		}
		else
		{
			$locations = Location::has('BookCopies')
						->orderBy('Country','asc')
						->orderBy('Location', 'asc')						
						->get();
			Cache::put($cacheKey,$locations,60);
			return $locations;
		}
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

	public function scopeLocation($query,$location)
	{
		return $query->whereLocation($location);
	}

	public static function getCountriesAsPerUsers()
	{
		return DB::table('locations')
				->join('users','locations.ID','=','users.LocationID')
				->groupBy('locations.Country')
				->select('locations.Country',DB::raw('count(*) as TotalMembers'))
				->orderBy('TotalMembers','desc')
				->get();
	}

	public static function getCitiesAsPerUsers($country)
	{
		return DB::table('locations')
				->join('users','locations.ID','=','users.LocationID')
				->groupBy('users.LocationID')
				->select('locations.ID','locations.Location','locations.Country',DB::raw('count(*) as TotalMembers'))
				->orderBy('locations.ID','asc')
				->where('locations.Country','=',$country)
				->get();
	}

}

?>
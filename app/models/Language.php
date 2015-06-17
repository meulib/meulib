<?php

class Language extends Eloquent {

	protected $table = 'languages';
	protected $primaryKey = 'ID';

	public function Books()
	{
		return $this->hasMany('FlatBook', 'Language1ID', 'ID');
	}

	public static function getAllLanguages()
	{
		$cacheKey = Config::get('app.cacheKeys')['allLanguages'];
		if (Cache::has($cacheKey))
		{
			return Cache::get($cacheKey);
		}
		else
		{
			$languages = self::orderBy('LanguageEnglish')
                                ->get();
			Cache::forever($cacheKey,$languages);
			return $languages;
		}
	}

	/*
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
	}*/

}

?>
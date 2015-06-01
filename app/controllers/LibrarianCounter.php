<?php 

// counter != number counter
// counter = service window
class LibrarianCounter extends BaseController
{

	public function whereAreYourBranches($country='')
	{
		if (strlen($country) == 0)
		{
			$countries = Location::getCountriesAsPerUsers();
			return View::make('memberCountries',
		        	array('countries' => $countries));
		}
		else
		{
			$cities = Location::getCitiesAsPerUsers($country);
			$members = Librarian::membersByCountry($country); // sorted by location id, name
			$offsetStart = 0;
			$citiesWithOffset = array();
			// set slice marks for cities based on total members
			// so that members can be sliced correctly in display
			// without re-retrieval
			foreach ($cities as $city)
			{
				$citiesWithOffset[$city->ID] = array('OffsetStart'=>$offsetStart,
													'TotalMembers'=>$city->TotalMembers);
				$offsetStart+=$city->TotalMembers;
			}
			// arrange cities - most members cities first, city name
			uasort($cities,function($a, $b)
		    {
		        $aM = $a->TotalMembers;
		        $bM = $b->TotalMembers;
		        if ($aM === $bM) 
		        {
		        	return strcmp($a->Location, $b->Location);
		        }
		        return ($aM > $bM) ? -1 : 1;
		    });
			return View::make('memberCities',
		        	array('cities' => $cities,'country'=>$country,
		        		'citiesWithOffset'=>$citiesWithOffset, 'members'=>$members));
		}
	}

}
?>
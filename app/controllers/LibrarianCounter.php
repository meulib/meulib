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
			$members = Librarian::membersByCountry($country);
			$offsetStart = 0;
			$citiesWithOffset = array();
			foreach ($cities as $city)
			{
				$citiesWithOffset[$city->ID] = array('OffsetStart'=>$offsetStart,
													'TotalMembers'=>$city->TotalMembers);
				$offsetStart+=$city->TotalMembers;
			}
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
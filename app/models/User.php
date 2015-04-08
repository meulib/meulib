<?php

class User extends Eloquent {

	protected $table = 'users';
	protected $primaryKey = 'UserID';
	protected $hidden = array('UserID','EMail');
	public $IsAdmin = 0;

	/*protected function RegistrationDetails()
    {
        return $this->hasOne('RegisteredUser','UserID','UserID');
    }*/

    public function setLibrarySettings($librarySettings)
    {
    	try
    	{
    		if (isset($librarySettings['LibraryName']))
	    	{
	    		$this->LibraryName = $librarySettings['LibraryName'];
	    		$this->save();
	    		return ['success'=>true,'UserId'=>$this->UserID];
	    	}
    	}
    	catch (Exception $e)
		{
			return ['success' => false, 'errors' => $e->getMessage()];
		}
    }

}

?>

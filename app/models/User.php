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

    public function setProfilePicture($profileData)
    {
    	try
    	{
    		$picFilename = "";
	        if (isset($profileData['profile-pic']))
			{
				$uploadResult = FileManager::uploadImage($profileData['profile-pic'],'member-pics');
				if ($uploadResult['success'])
				{
					// upload successful
					// set info in book record
					$picFilename = $uploadResult['filename'];
					$this->ProfilePicFile = $picFilename;
					$this->save();
					return ['success'=>true,'UserId'=>$this->UserID];
				}
				else
				{
					return $uploadResult;
				}
			}
			else
			{
				return ['success' => false, 'errors' => 'No picture received'];	
			}
    	}
    	catch (Exception $e)
    	{
    		return ['success' => false, 'errors' => $e->getMessage()];
    	}
    }

}

?>

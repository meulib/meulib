<?php

class BookCopy extends Eloquent {

	protected $table = 'bookcopies';
	protected $primaryKey = 'ID';

	public function Owner()
	{
		return $this->hasOne('User', 'UserID', 'UserID');
	}

	public function Book()
	{
		return $this->hasOne('FlatBook', 'ID', 'BookID');
	}

	public function StatusTxt()
	{
		switch ($this->Status) 
		{
			case 1:
				return 'Available';
				break;

			case 2:
				return 'Lent Out';
				break;
		
			default:
				return '';
				break;
		}
	}

	public static function StatusVal($val)
	{
		switch ($val) 
		{
			case 'Available':
				return 1;
				break;

			case 'Lent Out':
				return 2;
				break;
		
			default:
				return -1;
				break;
		}
	}
}

?>
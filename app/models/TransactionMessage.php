<?php

class TransactionMessage extends Eloquent {

	protected $table = 'transaction_messages';
	protected $primaryKey = 'ID';

	public static function MsgFromValue()
	{
		return 1;
	} 

	public static function MsgToValue()
	{
		return 0;
	} 

}

?>
<?php

/* ---------------------------------------------
| CLASS FOR ALL DATE TIME RELATED OurLib FUNCTIONS
| ----------------------------------------------- */ 
class TimeKeeper
{

	public static function niceDate($rawDate)
	{
		return date("jS M Y",strtotime($rawDate));
	}

	public static function niceDateNoYear($rawDate)
	{
		return date("jS M",strtotime($rawDate));
	}
}
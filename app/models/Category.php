<?php

class Category extends Eloquent {

	protected $table = 'categories';
	protected $primaryKey = 'ID';

	public function Books()
    {
        return $this->belongsToMany('FlatBook','book_categories','CategoryID','BookID')->withTimestamps();
    }

	public static function getAllCategories()
	{
		$cacheKey = Config::get('app.cacheKeys')['allCategories'];
		if (Cache::has($cacheKey))
		{
			return Cache::get($cacheKey);
		}
		else
		{
			$result = Category::orderBy('Category')
                                ->get();
			Cache::forever($cacheKey,$result);
			return $result;
		}
	}

}

?>
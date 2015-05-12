<?php 

class UserGateway extends BaseController
{

	public function showUserCollection($username)
	{

		$calledUser = RegisteredUser::getUserByUsername($username);
		if (is_null($calledUser))
			App::abort(404);

		$humanUser = $calledUser->HumanUser;

		$paginationItemCount = Config::get('view.pagination-itemcount');

		if (Session::has('loggedInUser'))
		{
			$currentUser = Session::get('loggedInUser');
			if ($calledUser->UserID == $currentUser->UserID)
			{
				$myBooks = $calledUser->myBookCopies()
							->paginate($paginationItemCount);;
		        return View::make('myBooks',
		        	array('books' => $myBooks));
			}
		}
		
		$usersBooks = $calledUser->Books()
			->orderBy('Title', 'asc')
			->paginate($paginationItemCount);

		return View::make('userBooks',
			array('books' => $usersBooks, 'user' => $humanUser));
	}

}
?>

@extends('templates.base')

<?php
	$loggedIn = false;
	if (Session::has('loggedInUser'))
		$loggedIn = true;
	$pendingReqURL = URL::to('pendingRequests');
	$returnForm = URL::to('returnForm');
	$tMsg = ["",""];
	if (Session::has('TransactionMessage'))
	{
		$tMsg = Session::get('TransactionMessage');
		//if (($tMsg[0] == 'LendBook') || ($tMsg[0] == 'ReturnBook'))
		//{
			Session::forget('TransactionMessage');	
		//}
	}

	$bookCount = $books->count();
?>

@section('content')




@if ($tMsg[1]!="")
	<p align='center'>
		<span style="border:2px solid blue;padding:4px;background-color:LemonChiffon">
			{{{$tMsg[1][1] }}}
			@if ($tMsg[1][0] && ($tMsg[0] == 'AddBook'))
				<a href="#AddBooks">Add More Books</a>
			@endif
		</span>
	</p>
@endif

<!-- --- BOOK LISTING --- -->

@if ($bookCount > 0)
@if (!$loggedIn)
	<form action={{URL::to('/signup-or-login')}}>
		{{ Form::submit('Become a Member', 
			array('class' => 'richButton',
			'name'=>'btnMember')); }}
		{{ Form::submit('Login', 
			array('class' => 'normalButton',
			'name'=>'btnLogin')); }} to request these books for borrowing.
	</form>	
@endif
<span class="pageTitle">{{$user->FullName."'s Collection"}}</span>
<p align="center">{{ $user->Locality . ', ' . $user->City . '. ' . $user->State . ', ' . $user->Country }}</p>
<ul>
	{{ $books->links() }}
	<br/>
		@foreach($books as $book)
			<li>
				<a href={{  URL::action('BookController@showSingle', array($book->ID))}}>
				{{{ $book->Title }}}
				@if ($book->SubTitle)
					{{{ ": ".$book->SubTitle }}}
				@endif
				</a>
				@if ($book->Author1)
					{{{ "&nbsp;by ".$book->Author1 }}}
				@endif
				@if ($book->Author2)
					{{{ ", ".$book->Author2 }}}
				@endif
			</li>	
		@endforeach
	<br/>
	{{ $books->links() }}
</ul>
@else
	<span class="pageTitle">{{$user->FullName."'s Collection"}}</span>
	<p style="text-align:center;">{{$user->FullName}} has not added any books as yet.</p>
@endif

@stop
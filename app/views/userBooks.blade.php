
@extends('templates.base')

@section('title', $user->FullName."'s Collection on " )

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
		<span class="positiveMessage">{{{$tMsg[1][1] }}}</span>
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
<span class="pageTitle">
	@if(strlen($user->LibraryName)>0)
		{{$user->LibraryName}}
	@else
		{{$user->FullName."'s Collection"}}
	@endif
</span>
<p align="center">{{ $user->Locality . ', ' . $user->City . '. ' . $user->State . ', ' . $user->Country }}</p>

	{{ $books->links() }}
	<br/>
		@foreach($books as $book)
			<div class="bookMat">
				<a href={{  URL::action('BookController@showSingle', array($book->ID))}}>
				@if (strlen($book->CoverFilename)>0)
				{{ HTML::image('images/book-covers/'.$book->CoverFilename, 'a picture', array('height' => '150')) }}<br/>
				@endif
				{{{ $book->Title }}}
				@if ($book->SubTitle)
					<div class="bookMatSubTitle">
					{{--@if (strlen($book->SubTitle)>30)
						{{{ substr($book->SubTitle,0,30).'...' }}}
					@else--}}
						{{{ $book->SubTitle }}}
					{{--@endif--}}
					</div>
				@endif
				</a>
				@if ($book->Author1)
					<div class="bookMatAuthor">
					{{{ $book->Author1 }}}
					@if ($book->Author2)
						{{{ ", ".$book->Author2 }}}
					@endif
					</div>
				@endif
			</div>				
		@endforeach
	<br/>
	{{ $books->links() }}
</ul>
@else
	<span class="pageTitle">{{$user->FullName."'s Collection"}}</span>
	<p style="text-align:center;">{{$user->FullName}} has not added any books as yet.</p>
@endif

@stop
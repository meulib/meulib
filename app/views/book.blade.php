
@extends('templates.base')

<?php
	$loggedIn = false;
	if (Session::has('loggedInUser'))
	{
		$loggedIn = true;
		$loggedInUser = Session::get('loggedInUser');
	}
	$title = $book->FullTitle();
	// if ($book->SubTitle)
	// 	$title .= ': '.$book->SubTitle;
	$tMsg = ["",""];
	if (Session::has('TransactionMessage'))
	{
		$tMsg = Session::get('TransactionMessage');
		Session::forget('TransactionMessage');	
	}
	$deleteFormURL = URL::to('delete-book-confirmation');
	$copies = $copies->keyBy('UserID');
?>

@section('title', $book->Title.': '.$book->Author1.': ')

@section('content')
<div class='contentDiv'>

@if (!$loggedIn)
	<form action={{URL::to('/signup-or-login')}}>
		{{ Form::submit('Become a Member', 
			array('class' => 'richButton',
			'name'=>'btnMember')); }}
		{{ Form::submit('Login', 
			array('class' => 'normalButton',
			'name'=>'btnLogin')); }} for more details about the owner(s) and to request this book.
	</form>	
	<br/><br/>
@endif

@if ($tMsg[1]!="")
	<p align='center'><span class="positiveMessage">{{{$tMsg[1]}}}</span></p>
@endif

<ul class="errors">
@foreach($errors->all() as $message)
    <li>{{ $message }}</li>
@endforeach
</ul>

<div style="display:inline-block" id="bookDisplay">
	@if (strlen($book->CoverFilename)>0)
		{{ HTML::image('images/book-covers/'.$book->CoverFilename, '', array('height' => '200','style'=>'float: left; margin-right: 15px;')) }}
	@endif

	<b>{{{$title}}}</b><br/>
	@if ($book->Author1)
		{{{ $book->Author1 }}}
		@if ($book->Author2)
			{{{ ", ".$book->Author2 }}}
			 (Authors)
		@else
			  (Author)
		@endif		
	@endif
	<br/><br/>
	@if (count($bookCategories)>0)
		Categories: 
		<?php $firstCategory = false; ?>
		@foreach($bookCategories as $bCategory)
			{{$firstCategory?', ':''}}
			<?php $firstCategory = true; ?>
			{{$bCategory->Category}}
		@endforeach
		<br/><br/>
	@endif
	<span style="display: block; clear: both; width: 1px; height: 0.001%; font-size: 0px; line-height: 0px;"/>
</div>
@if (($loggedIn) && (isset($copies[$loggedInUser->UserID])))
	<?php $onclickShowEdit = "showHideDiv('bookEdit','bookDisplay','inline-block')"; 
	$onclickHideEdit = "showHideDiv('bookDisplay','bookEdit','inline-block')";
	?>
	<div id="bookEdit" style="display:none">
		<span class="formTitle">
			Edit Book Info
		</span><br/>
		<br/>
		{{ Form::open(array('action' => 'BookController@editBook','files'=>true)) }}
		{{ Form::hidden('bookID',$book->ID) }}
		<div>
			@if (strlen($book->CoverFilename)>0)
				{{ HTML::image('images/book-covers/'.$book->CoverFilename, '', array('height' => '200')) }}<br/>
				Change Book Cover<br/>
			@else
				Add Book Cover<br/>
			@endif
			{{ Form::file('book-cover') }}
		</div>
		Title <span style="color:red">*</span><br/>
		{{ Form::text('Title', $book->Title, ['required','size'=>40,'maxlength'=>100]) }}<br/>
		Sub Title<br/>
		{{ Form::text('SubTitle', $book->SubTitle, ['size'=>40,'maxlength'=>100]) }}<br/>
		Author <span style="font-size:90%">(or editor or series name)</span> <span style="color:red">*</span><br/>
		{{ Form::text('Author1', $book->Author1, ['required','size'=>40,'maxlength'=>100]) }}<br/>
		Any other authors?<br/>
		{{ Form::text('Author2', $book->Author2, ['size'=>40,'maxlength'=>100]) }}<br/>
		Language <span style="color:red">*</span><br/>
		{{ Form::text('Language1', $book->Language1, ['required','maxlength'=>50]) }}<br/>
		Any other language? <span style="font-size:90%">(for multi-lingual books)</span><br/>
		{{ Form::text('Language2', $book->Language2, ['maxlength'=>50]) }}<br/>
		{{ Form::submit('Edit Info', array('class' => 'normalButton')); }}
		{{ Form::button('Cancel', array('class' => 'normalButton','onclick' => $onclickHideEdit)); }}
		{{ Form::close() }}
	</div>
	<div style="display:inline-block;vertical-align:top;margin-left:10px">
		<span class="subtleActionLink">
		{{ HTML::link('#','Edit Book Info', ['onclick'=>$onclickShowEdit]) }}
		</span>
	</div>
@endif

<div>
<br/>
Book Copies ({{{count($copies)}}})
<br/><br/>
<b>Owners</b><br/>
@foreach($copies as $bCopy)
	<a href={{ URL::route('user-books', array($bCopy->Owner->Username))}}>
	{{{$bCopy->Owner->FullName.': '}}}</a>
	in 
	{{{$bCopy->Owner->City.', '.$bCopy->Owner->Locality}}}<br/>
	@if ($loggedIn)
		@if ($bCopy->Owner->UserID != $loggedInUser->UserID)
			Book Status: {{ $bCopy->StatusTxt() }}
			<br/>
			<?php $onclick = "showDiv('requestBook".$bCopy->ID."')"; ?>
			{{ HTML::link('#','Request Book', ['onclick'=>$onclick]); }}
			@include('templates.requestBookForm')
		@else
			This is you!<br/>
			Book Status: {{ $bCopy->StatusTxt() }}
			@if ($bCopy->StatusTxt() == 'Lent Out')
				{{ 'on '.$bCopy->niceLentOutDt().'. '.$bCopy->daysAgoLentOut().' days ago'}}
			@endif
			<br/>
			<?php $onclick = "showPostDiv('".$bCopy->ID."','".$deleteFormURL."')"; ?>
			<span class="aTreadCarefully">{{ HTML::link('#','Delete This Copy', ['onclick'=>$onclick]) }}</span>
			{{"<div id='postDiv".$bCopy->ID."' style='display:none;' class='carefulDiv'></div>"}}
		@endif
	@endif
	<br/><br/>
@endforeach
</div>

</div>
@stop


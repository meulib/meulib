
@extends('templates.base')

@section('title', 'My Collection: ')

<?php
	$loggedIn = false;
	$libraryName = "";
	if (Session::has('loggedInUser'))
	{
		$loggedIn = true;
		$user = Session::get('loggedInUser');
		if (strlen($user->LibraryName)==0)
			$libraryName = $user->FullName."'s Collection";
		else
			$libraryName = $user->LibraryName;
	}
	$pendingReqURL = URL::to('pending-requests');
	$returnForm = URL::to('return-form');
	$tMsg = ["",""];
	if (Session::has('TransactionMessage'))
	{
		$tMsg = Session::get('TransactionMessage');
			Session::forget('TransactionMessage');
	}
	//var_dump($tMsg);
?>

@section('content')

@if ($tMsg[1]!="")
	<p align='center'>
		<span class="positiveMessage">{{{$tMsg[1] }}}</span>
	</p>
@endif

<ul class="errors">
@foreach($errors->all() as $message)
    <li>{{ $message }}</li>
@endforeach
</ul>
<?php $onclickEditLibName = "showHideDiv('editLibraryName','displayLibraryName','table')"; 
$onclickShowLibName = "showHideDiv('displayLibraryName','editLibraryName','block')";
?>
<span id="displayLibraryName" class="pageTitle">{{{$libraryName}}} {{ HTML::image('images/mEdit.png', '', array('height' => '16','onclick'=>$onclickEditLibName)) }}</span>
<div id="editLibraryName" style="display:none;margin:0 auto">
	{{ Form::open(array('action' => 'UserController@setLibrarySettings')) }}
		{{ Form::text('LibraryName', $libraryName, ['required','size'=>40,'maxlength'=>100]) }} 
		{{ Form::submit('Save', array('class' => 'normalButton')); }}
		{{ Form::button('Cancel', array('class' => 'normalButton', 'onclick' => $onclickShowLibName)); }}
	{{ Form::close() }}
</div>
<p align="center">{{ $user->Locality . ', ' . $user->City . '. ' . 
	$user->State . ', ' . $user->Country }} 
	{{-- HTML::image('images/mEdit.png', '', array('height' => '16')) --}}</p>
@if (count($books)>0)
	{{ $books->links() }}
	<!-- ul -->
	@foreach($books as $bookCopy)
		<!-- li -->
		<div style="margin-left:10px;margin-bottom:10px;">
			<a href={{  URL::action('BookController@showSingle', $bookCopy->Book->ID)}}>
			@if (strlen($bookCopy->Book->CoverFilename)>0)
				{{ HTML::image('images/book-covers/'.$bookCopy->Book->CoverFilename, '', array('height' => '100','style'=>'float: left; margin-right: 15px;')) }}
			@endif
			{{{ $bookCopy->Book->Title }}}
			@if ($bookCopy->Book->SubTitle)
				{{{ ": ".$bookCopy->Book->SubTitle }}}
			@endif
			</a>
			@if ($bookCopy->Book->Author1)
				{{{ "&nbsp;by ".$bookCopy->Book->Author1 }}}
			@endif
			@if ($bookCopy->Book->Author2)
				{{{ ", ".$bookCopy->Book->Author2 }}}
			@endif
			<!--br/-->
			(
			@if ($bookCopy->StatusTxt() == 'Available') 
				@if ($bookCopy->ForGiveAway)
					<?php $onclick = "showLendForm('".$bookCopy->BookCopyID."','1','".$pendingReqURL."')"; ?>
					{{ HTML::link('#','Give Away', ['onclick'=>$onclick]); }}
				@else
					<?php $onclick = "showLendForm('".$bookCopy->BookCopyID."','0','".$pendingReqURL."')"; ?>
					{{ HTML::link('#','Lend', ['onclick'=>$onclick]); }}
				@endif
				{{--"<div id='showDiv2".$copy->ID."' style='display:none; border:2px grey solid;padding: 5px;'></div>"--}}
				<div id='showLendDiv{{$bookCopy->BookCopyID}}' style='display:none;' class='formDiv'></div>
			@endif
			@if ($bookCopy->StatusTxt() == 'Lent Out')
				{{{$bookCopy->StatusTxt()}}}
				{{ 'on '.$bookCopy->niceLentOutDt().'. '.$bookCopy->daysAgoLentOut().' days ago'}}
				<?php $onclick = "showLendForm('".$bookCopy->BookCopyID."',0,'".$returnForm."')"; ?>
				{{ HTML::link('#','Accept Return', ['onclick'=>$onclick]); }}
				{{"<div id='showLendDiv".$bookCopy->BookCopyID."' style='display:none' class='formDiv'></div>"}}
			@endif
			)
			<span style="display: block; clear: both; width: 1px; height: 0.001%; font-size: 0px; line-height: 0px;"/>
		</div>
	@endforeach
	<!-- /ul -->
	{{ $books->links() }}
@else
	There are no books yet in your {{Config::get('app.name');}} collection.<br/><br/>
@endif


@if (Session::has('loggedInUser'))
	@include('templates.addBooks')
@endif

@stop
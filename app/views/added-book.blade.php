
@extends('templates.base')

@section('content')

<div class="contentDiv">

@if (isset($addedBook) && ($addedBook))
<div class='positiveMessage'>
Yay! {{$book->Title}} added!<br/>
</div>
@endif

@if (isset($addedInfo) && ($addedInfo))
<div class='positiveMessage'>
Very nice. Categories for {{$book->Title}} recorded.<br/>
Suggested categories sent to Admin for review.
</div>
@endif

<!--p align="center">
@if (isset($doAddInfo) && ($doAddInfo))
<a href="#AdditionalInfo">Add categories for the book</a><br/>
@endif
<a href="#AddBooks">Add more books</a><br/>
{{HTML::link(URL::to('my-books'), 'Go to My Books')}}<br/>
</p-->
<br/>
@if (isset($doAddInfo) && ($doAddInfo))
@include('templates.addBookInfo')
<br/>
@endif

@include('templates.addBooks')
{{HTML::link(URL::to('my-books'), 'Go to My Books')}}
</div>

@stop
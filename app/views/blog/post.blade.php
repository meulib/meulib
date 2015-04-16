@extends('blog-templates.blogbase')


@section('content')
<div class="contentDiv">
	<div style="font-weight:bolder;font-size:120%">{{$blogPost->Title}}</div>
	<div>{{$blogPost->SubTitle}}</div>
	<div style="text-align:right;width:100%;font-size:80%">{{$blogPost->nicePublishedDate()}}</div>
	<div><span style="font-family:'Merienda One',serif">This book came into my life </span>{{ $blogPost->Body }}</div>
</div>
@stop

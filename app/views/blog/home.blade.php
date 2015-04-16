@extends('blog-templates.blogbase')


@section('content')
<div class="contentDiv">
	@foreach($recentPosts as $blogPost)
		<div>{{$blogPost->Title}}</div>
		<div>{{$blogPost->SubTitle}}</div>
		<div>
			@if (strlen($blogPost->Excerpt)>0)
				{{$blogPost->Excerpt}}
			@else
				{{ substr($blogPost->Body,0,250).' ... Read more -' }}
			@endif
		</div>
	@endforeach
</div>
@stop

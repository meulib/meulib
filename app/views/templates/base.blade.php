<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @section('htmlHead')
        {{ HTML::script('js/jquery-1.11.0.min.js'); }}
        {{ HTML::script('js/ourlib.js'); }}
    	<title>{{Config::get('app.name')}}</title>
    @show
</head>
<body>
    @section('header')
   		@include('templates.mast')
	@show
    @section('appLinks')
        <a href={{URL::to('/browse')}}>Browse Collection</a> | 
        @if (Session::has('loggedInUser'))
            {{HTML::link(URL::to('/messages'), 'Messages')}}
             | 
            {{HTML::link(URL::to('my-books'), 'My Books')}} | 
            {{HTML::link(URL::to('borrowed-books'), 'Borrowed Books')}}
        @else
            {{HTML::link(URL::to('account/create'), 'Become a Member')}}
        @endif
        <br/><br/>
    @show
    @yield('content')
    {{--placeholder for footer--}}
</body>
</html>


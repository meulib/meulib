<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @section('htmlHead')
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{ HTML::style('css/ourlib.css') }}
    	<title>{{Config::get('app.name')}}</title>
    @show
</head>

<body>
    @section('header')
   		@include('templates.mast')
	@show

    @section('appLinks')
        <div class="appLinks">
        <a href={{URL::to('/browse')}}>Browse Collection</a> | 
        @if (Session::has('loggedInUser'))
            {{HTML::link(URL::to('/messages'), 'Messages')}}
             | 
            {{HTML::link(URL::to('my-books'), 'My Books')}} | 
            {{HTML::link(URL::to('borrowed-books'), 'Borrowed Books')}}
        @else
            {{HTML::link(URL::to('become-a-member'), 'Become a Member')}} | 
            {{HTML::link(URL::to('/how-it-works'), 'How It Works')}} | 
            {{HTML::link(URL::to('/faq'), 'FAQ')}} | 
            {{HTML::link(URL::to('/vision'), 'Vision')}}
        @endif
        </div>
    @show

    @yield('content')

    @section('footer')
        <div class="footer">
        <div style="vertical-align: middle;width:50px;display:inline-block">
            <a href="https://www.facebook.com/Meulib">{{ HTML::image('images/misc/06-facebook.png') }}</a>
            <a href="https://twitter.com/meulib">{{ HTML::image('images/misc/03-twitter.png') }}</a>
        </div>
        <div style="vertical-align:middle;text-align:right;display:inline-block;position:absolute;padding-right: 20px;
        right:0;">
            {{HTML::link('https://github.com/meulib/meulib', 'github')}} | 
            <!-- {{HTML::link('https://abc.com', 'founding members')}}
            founding members | -->
            {{HTML::link(URL::to('/contact-admin'), 'contact support')}}
            
        </div>
        {{ HTML::script('js/jquery-1.11.0.min.js'); }}
        {{ HTML::script('js/ourlib.js'); }}
    @show
</body>
</html>


<?php
    $currentRoute = Route::currentRouteName();
    if (is_null($currentRoute))
    {
        if (Request::is('*how-it-works*'))
        {
            $currentRoute = 'how-it-works';
        }
    }
    $loggedInUser = false;
    if (Session::has('loggedInUser'))
    {
        $loggedInUser = true;
        $user = Session::get('loggedInUser');
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @import url(//fonts.googleapis.com/css?family=Lato);
    </style>
    @section('htmlHead')
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{ HTML::style('css/ourlib.css') }}
    	<title>@yield('title'){{Config::get('app.name')}}</title>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-61079709-1', 'auto');
          ga('send', 'pageview');
        </script>
    @show
</head>

<body>
    @section('header')
   		@include('templates.mast')
	@show

    @section('appLinks')
        <div class="appLinks">
            {{($currentRoute=='browse' ? "<span class='currentPageHighlighter'>Browse Collection</span>" : HTML::link(URL::route('browse'), 'Browse Collection'))}} | 
        @if ($loggedInUser)
            {{(($currentRoute=='user-books') && (Request::is('*'.$user->Username.'*')) ? "<span class='currentPageHighlighter'>My Collection</span>" : HTML::link(URL::to('/'.$user->Username), 'My Collection'))}} | 
            {{($currentRoute=='borrowed' ? "<span class='currentPageHighlighter'>Borrowed Books</span>" : HTML::link(URL::route('borrowed'), 'Borrowed Books'))}} |
            {{($currentRoute=='messages' ? "<span class='currentPageHighlighter'>Messages</span>" : HTML::link(URL::route('messages'), 'Messages'))}}
        @else
            {{($currentRoute=='how-it-works' ? "<span class='currentPageHighlighter'>How It Works</span>" : HTML::link(URL::route('how-it-works'), 'How It Works'))}} | 
            {{($currentRoute=='faq' ? "<span class='currentPageHighlighter'>FAQ</span>" : HTML::link(URL::route('faq'), 'FAQ'))}} | 
            {{($currentRoute=='membership-rules' ? "<span class='currentPageHighlighter'>Membership Rules</span>" : HTML::link(URL::route('membership-rules'), 'Membership Rules'))}} | 
            {{($currentRoute=='vision' ? "<span class='currentPageHighlighter'>Vision</span>" : HTML::link(URL::route('vision'), 'Vision'))}}
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
            {{HTML::link(URL::to('/founding-members'), 'founding members')}} | 
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


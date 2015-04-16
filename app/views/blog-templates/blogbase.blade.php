<?php
    $currentRoute = Route::currentRouteName();
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
        {{--  import not necessary for Lato. I can use link for that too --}}
        @import url(//fonts.googleapis.com/css?family=Lato);
        @import url(//fonts.googleapis.com/css?family=Merienda+One);
        <!-- link href="http://fonts.googleapis.com/css?family=Merienda+One" rel="stylesheet" type="text/css" -->
    </style>
    @section('htmlHead')
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{ HTML::style('css/ourlib.css') }}
        {{ HTML::style('css/ourlib-blog.css') }}
    	<title>@yield('title'){{Config::get('app.blog_name').'-'.Config::get('app.blog_tag')}}</title>
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
   		@include('blog-templates.blogmast')
	@show

    @section('blogAppLinks')
        <!-- div class="blogAppLinks">
            <a href="http://meulib.com">MeULib: The Public Library by Me and You</a>
        </div -->
    @show

    @section('contentHolder')
        <div style="margin-left:10;width:75%;padding-right:10px;display:inline-block;border-right:2px solid #B48700">
        @yield('content')
        </div>
    @show

    @section('blogSidebar')
        <div style="padding:30px 0 30px 0;vertical-align:top;width:21%;display:inline-block;text-align:center;">
            <div class="richButton" style="padding:10px;display:table;margin:0 auto;font-size:100%;font-weight:bold">Receive Updates from<br/><span style="font-family: 'Merienda One', serif;">Books on Our Journey</span></div><br/><br/>
            <div style="font-family:'Merienda One',serif"><a href="http://yahoo.com">Write Of Books You Love</a></div>
            <br/><br/>
            <div style="font-family:'Merienda One',serif"><a href="http://yahoo.com">About This Blog</a></div>
        </div>
    @show

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


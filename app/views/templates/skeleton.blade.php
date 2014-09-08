<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @section('htmlHead')
    	<title>{{Config::get('app.name')}}</title>
    @show
</head>
<body>
    @yield('header')
    @yield('content')
    @yield('footer')
</body>
</html>
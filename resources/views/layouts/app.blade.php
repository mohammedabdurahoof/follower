<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Followers Sunglobal">
    <meta name="author" content="Ritobroto Mukherjee">
    <meta name="keyword" content="Insurance, Web Development, Flutter Development">
    <meta property="og:title" content="Follower Sunglobal Insurance" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ config('app.asset_url') }}/images/FollowerLogo.ico">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicons -->
    <link href="{{ config('app.asset_url') }}/images/FollowerLogo.ico" rel="icon">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ config('app.asset_url') }}/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--external css-->
    <link href="{{ config('app.asset_url') }}/lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ config('app.asset_url') }}/css/style.css" rel="stylesheet">
    <link href="{{ config('app.asset_url') }}/css/style-responsive.css" rel="stylesheet">
    @yield('css')<!-- all css in child views -->
</head>
<body>
    @yield('content')
<!--    <section id="container" style='bottom:0;'>
        footer start
        <footer class="site-footer">
            <div class="text-center">
                <p>
                  &copy; Copyrights {{ date('Y') }} <strong>Sunglobal</strong>. All Rights Reserved
                </p>
                <a href="#" class="go-top">
                    <i class="fa fa-angle-down"></i>
                </a>
            </div>
        </footer>
        footer end
    </section>-->
        <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{ config('app.asset_url') }}/lib/jquery/jquery.min.js"></script>
    <script src="{{ config('app.asset_url') }}/lib/bootstrap/js/bootstrap.min.js"></script>
    @yield('scripts')<!-- all scripts in child views -->
</body>
</html>

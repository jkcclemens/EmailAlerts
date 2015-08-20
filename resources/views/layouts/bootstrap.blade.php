<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/unsemantic/0.1/stylesheets/unsemantic-grid-responsive.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/semantic-ui/2.0.8/semantic.min.css"/>
    <link rel="stylesheet" href="/css/main.css"/>
</head>
<body>
<div class="grid-container">
    @yield('base')
</div>
<script src="//cdn.jsdelivr.net/jquery/3.0.0-alpha1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/semantic-ui/2.0.8/semantic.min.js"></script>
@yield('bottom')
</body>
</html>

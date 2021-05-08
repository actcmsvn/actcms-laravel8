<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @ActcmscssStyles
</head>
<body>
    @yield('content')

    @ActcmscssScripts
    @stack('scripts')
</body>
</html>

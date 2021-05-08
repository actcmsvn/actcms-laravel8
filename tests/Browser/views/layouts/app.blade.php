<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <!-- <script src="http://alpine.test/dist/alpine.js" defer></script> -->
    @ActcmscssStyles
</head>
<body>
    {{ $slot }}

    @ActcmscssScripts
    @stack('scripts')
</body>
</html>

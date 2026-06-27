<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk') - Maju Bersama</title>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    <meta name="theme-color" content="#b87333">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Maju Bersama">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/template/templatemo-crypto-pwa.css') }}">
</head>
<body class="auth-page">
    @yield('content')
    <script src="{{ asset('js/template/templatemo-crypto-script.js') }}"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset("sw.js") }}')
                .then(function() { console.log('SW registered'); })
                .catch(function(err) { console.log('SW failed:', err); });
        }
    </script>
</body>
</html>

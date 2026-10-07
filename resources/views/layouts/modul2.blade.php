<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'OpenTune' }}</title>

    @vite([
        'resources/css/modul2.css',
        'resources/js/modul2.js'
    ])
</head>

<body>
    @yield('content')
</body>
</html>
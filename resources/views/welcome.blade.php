<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="favicon.svg">
    <title>Progressa</title>
    @vite(['resources/js/app.ts'])
</head>
<body class="antialiased bg-gray-100">
    <div id="app"></div>
</body>
</html>

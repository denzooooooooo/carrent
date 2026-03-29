<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('carre_premium.company.name') }}</title>
</head>
<body style="margin:0; padding:0; background:#f6f7fb; color:#111827; font-family:Arial, Helvetica, sans-serif;">
    @yield('content')
</body>
</html>

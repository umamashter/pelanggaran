<!DOCTYPE html>
<html lang="en">

<head>
    @include('component.head')
    <title>E-Book | @yield('title')</title>
    <link rel="stylesheet" href="../css/auth.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>

    @yield('auths')

    @include('component.footer')
    @include('component.script')

</body>

</html>

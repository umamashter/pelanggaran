<!DOCTYPE html>
<html lang="en">

<head>
    @include('component.head')
    <title>MIS Nurul Ulum | @yield('title')</title>
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/dark-mode.css">
    <link rel="stylesheet" href="../css/loading.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    @include('component.loading')

    @yield('auths')

    @include('component.footer')
    @include('component.script')
    <script src="../js/loading.js"></script>

</body>

</html>
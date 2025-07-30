<!DOCTYPE html>
<html lang="en">

<head>
    @include('component.head')
    <title>E-Violation | @yield('title')</title>
    <link rel="stylesheet" href="../css/form-data.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>

    @yield('datas')

    @include('component.footer')
    @include('component.script')

</body>

</html>

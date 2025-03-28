<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="refresh" content="0; url={{ $redirect_url }}">

    <title>@lang('Redirecting to payment gateway...')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="Redirecting to payment gateway...">

    <link rel="icon" href="{{ asset_ver('assets/images/favicon.ico') }}" type="image/ico" sizes="48x48">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <p style="text-align:center;">
        @lang('Please do not refresh this page while you are being redirected to payment gateway...')
    </p>
</body>

</html>

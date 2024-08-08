<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kleinhaendler</title>
    <script src="https://js.stripe.com/v3/"></script>

    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
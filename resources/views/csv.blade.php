<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="container p-5">
        <button type="button" onclick="location.href='{{ route('download') }}'" class="btn btn-primary">CSV
            Dowonload</button>
    </div>
    <div class="container p-5">
        <input id="fileselector" type="file" onchange="browseResult(event)" webkitdirectory directory multiple="false"
            style="display:none" />
        <button onclick="getElementById('fileselector').click()">browse</button>
    </div>
    <div>
        <input id="file" type="file" name="upfile[]" webkitdirectory>
    </div>
</body>

</html>
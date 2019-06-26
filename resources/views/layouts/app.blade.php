<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Book</title>
</head>
<body>
    <div class="container" id="app">
        <div class="row my-5 bg-white shadow-sm p-2" style="height:700px">
            <div class="col-3 sidebar rounded">
                @include('layouts.partials.sidebar')
            </div>
            <div class="col-9">
                <router-view></router-view>
                <vue-progress-bar></vue-progress-bar>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>
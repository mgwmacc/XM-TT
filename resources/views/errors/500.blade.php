<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'My Test Task') }}</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Historical Data
                    </a>
                </div>
            </nav>
            <main class="py-4" >
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12 text-center">
                            {{ $exception->getMessage() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>

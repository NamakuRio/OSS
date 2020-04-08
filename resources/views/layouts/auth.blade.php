<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        @include('layouts._partials._head')
        @include('layouts._partials._styles')
    </head>

    <body>
        <div id="app">
            <section class="section">
                @yield('content')
            </section>
        </div>

        @include('layouts._partials._scripts')
    </body>
</html>

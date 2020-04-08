<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        @include('layouts._partials._head')
        @include('layouts._partials._styles')
    </head>

    <body>
        <div id="app">
            <div class="main-wrapper main-wrapper-1">
                @include('layouts._partials._header')
                @include('layouts._partials._sidebar')

                <!-- Main Content -->
                <div class="main-content">
                    @yield('content')
                </div>
                @include('layouts._partials._footer')
            </div>
        </div>

        @include('layouts._partials._scripts')
    </body>
</html>

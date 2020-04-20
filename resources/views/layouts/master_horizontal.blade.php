<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        @include('layouts._partials._head')
        @include('layouts._partials._styles')
    </head>

    <body class="layout-3">
        <div id="app">
            <div class="main-wrapper container">
                <div class="navbar-bg" style="max-height:70px;"></div>
				<nav class="navbar navbar-expand-lg main-navbar">
					<div class="container">
						<a href="" class="navbar-brand sidebar-gone-hide">OSS</a>
					</div>
      			</nav>
				<!-- Main Content -->
				<div class="main-content" style="padding-top:90px;">
					@yield('content')
				</div>
                @include('layouts._partials._footer')
            </div>
        </div>

        @include('layouts._partials._scripts')
    </body>
</html>

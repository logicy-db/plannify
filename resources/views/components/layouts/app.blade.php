{{-- Base template for all pages --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://kit.fontawesome.com/2d7d94fb92.js" crossorigin="anonymous"></script>
    <title>@yield('title')</title>
</head>
<body class="@yield('bodyClass')">
<header>
    <nav>
        <a class="logo" href="{{ url('/') }}"><b>Plannify</b></a>
        @auth
            <div class="nav-content">
                <a href="{{ url('/') }}">People</a>
                <a href="{{ url('/') }}">Projects</a>
                <a href="{{ url('/') }}">Events</a>
                @isAdmin
                    <a class="admin-panel-btn" href="{{ route('admin.dashboard') }}">Admin panel</a>
                @endisAdmin
                {{-- TODO: Rework handing of the form by using jQuery --}}
                <a class="logout-btn" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            <a class="nav-control">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>
        @endauth
    </nav>
</header>
<div class="page-wrapper">
    {{-- System messages for the users --}}
    @if (session()->has('error'))
        <div class="alert alert-error">
            {{ session()->get('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @yield('content')
</div>
<script type="text/javascript">
    $(document).ready(function () {
        // Hide/expand navigation menu
        let showNav = false;
        let $navContent = $('header nav .nav-content');
        $('header nav .nav-control').click(function () {
            showNav = !showNav;
            if (showNav) {
                $navContent.slideDown('fast');
            } else {
                $navContent.slideUp('fast');
            }
        })

        // Displaying/hiding system messages
        $('.alert').slideDown('fast', function () {
            setTimeout(() => {
                $(this).slideUp('fast');
            }, 5000);
        });
    });
</script>
</body>
</html>

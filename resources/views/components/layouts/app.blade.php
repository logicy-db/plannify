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
                @hasProfile
                    <a href="{{ route('profiles.index') }}">People</a>
                    <a href="{{ url('/') }}">Projects</a>
                    <a href="{{ route('events.index') }}">Events</a>
                    <a href="{{ route('users.show', Auth::user()) }}">My account</a>
                    @hasSystemAccess
                        <a class="system-panel-btn" href="{{ route('system.dashboard') }}">System panel</a>
                    @endhasSystemAccess
                @endhasProfile
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <a class="logout-btn">Logout</a>
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
    <div class="notifications">
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
    </div>
    {{--  Page content goes below  --}}
    @yield('content')
</div>
<footer>
    <div class="footer-content">
        Author: Daniels Buls<br/>
        Made for University of Latvia<br/>
        2021
    </div>
</footer>
<script type="text/javascript">
    $(document).ready(function () {
        // Submitting of log out form
        $('#logout-form .logout-btn').click(function (e) {
            e.preventDefault();
            $('#logout-form').submit();
        });

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
        $('.notifications .alert').slideDown('fast', function () {
            setTimeout(() => {
                $(this).slideUp('fast');
            }, 5000);
        });

        $('button.danger').on('click', function (e) {
            // Popup to confirm dangerous actions
            if (confirm('Are you sure about this action?') === false) {
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>
{{-- TODO: create footer --}}

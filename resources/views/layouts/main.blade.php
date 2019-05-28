<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta id="token" name="token" value="{{ csrf_token() }}"/>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" rel="stylesheet" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

    @yield('page-styles')

    <style>
        .jumbotron {
            padding: 4rem 2rem;
        }

        .yllogo {
            height: 35px
        }

        .show-mobile {
            display: none;
        }

        .hide-mobile {
            display: inline;
        }

        @media (max-width: 500px) {
            .yllogo {
                height: 25px
            }

            .show-mobile {
                display: inline;
            }

            .hide-mobile {
                display: none;
            }
        }

    </style>
    <!-- Custom styles for this template -->
    {{--}}<link href="navbar-top-fixed.css" rel="stylesheet">--}}
</head>
<body>
<nav class="navbar navbar-dark" style="background-color: #002E4E;">
    <a class="navbar-brand" href="/auctions"><img src="/img/yl-logo.png" class="yllogo"></a>
    <div class="dropdown float-right">
        <button class="btn dropdown-toggle" style="background: #002E4E; color: #fff" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @if (Auth::user()->admin)
                {{ Auth::user()->firstname }}
            @else
                <span class="badge badge-pill badge-light">#{{ Auth::user()->bidder_id }}</span> &nbsp;
            @endif
            {{--}}{{ Auth::user()->first_name }}--}}
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="/home"><i class="fa fa-list" style="width:30px;"></i> Rules</a>
            <a class="dropdown-item" href="/auctions"><i class="fa fa-gavel" style="width:30px;"></i> Auction</a>
            @if (Auth::user()->admin)
                <a class="dropdown-item" href="/admin"><i class="fa fa-user-cog" style="width:30px;"></i>Admin</a>
            @endif
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/logout"><i class="fa fa-sign-out-alt" style="width:30px;"></i> Logout</a>
        </div>
    </div>
    {{--}}
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/home">Rules</a>
            </li>
            @if (Auth::user()->admin)
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Admin</a>
                </li>
            @endif
        </ul>

    </div>--}}
</nav>

<main role="main">
    @yield('content')
</main>

@yield('page-scripts')
</body>
</html>
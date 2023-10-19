@section('header')

<style>
    .dropbtn {
        /* background-color: #04AA6D; */
        color: black;
        /* padding: 16px; */
        font-size: 15px;
        border: none;
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: #ffffff;
        min-width: 90px;
        /* box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2); */
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }

    /* .dropdown-content a:hover {
        background-color: #f1f1f1;
    } */

    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    } */
</style>

<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="border-bottom: 3px solid #38d39f;">
    <div class="container">
        <a class="navbar-brand" href="{{ url('home') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Language Dropdown -->
                <li class="nav-item dropdown d-none">
                    <div class="dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ App::getLocale() === 'en' ? __('English') : __('日本語') }}
                        </a>
                        <div class="dropdown-content">
                            <form id="toggle-language-form" action="{{ route('language.toggle', App::getLocale()) }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item" value="en">English</button>
                                <button type="submit" class="dropdown-item" value="ja">日本語</button>
                            </form>
                        </div>
                    </div>
                </li>

                @guest
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @endif

                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @endif
                @else
                <!-- <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a> -->

                <li class="nav-item dropdown">
                    <div class="dropdown" style="float:right;">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle dropbtn" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-content">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>


                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@endsection

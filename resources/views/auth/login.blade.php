@extends('layouts.app')

@section('content')


<img class="wave" src="{{ asset('image/wave.png') }}" alt="Example Image">

<div class="container_logo">
    <div class="img">
        <img src="{{ asset('image/bg.svg') }}">
    </div>
    <div class="login-content">
        <form method="POST" class="login_form" action="{{ route('login') }}">
            @csrf
            <img src="{{ asset('image/avatar.svg') }}">
            <h2 class="title">Welcome</h2>
            <div class="input-div one">
                <div class="i">
                    <i class="fas fa-user"></i>
                </div>
                <div class="div">
                    <h5>Username</h5>
                    <input id="email" type="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <br>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="input-div pass">
                <div class="i">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="div">
                    <h5>Password</h5>
                    <input id="password" type="password" class="input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <!-- <input type="password" class="input"> -->
                </div>
            </div>
            <a href="{{ route('register') }}">
                {{ __('Register') }}
            </a>

            <!-- @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
            @endif -->
            <!-- <a href="#">Forgot Password?</a> -->
            <div class="row">
                <div class="col-md-6">
                    <input type="submit" class="login_btn" value="{{ __('Login') }}">
                </div>
                <div class="col-md-3"></div>

                <div class="col-md-3"></div>
            </div>
        </form>
    </div>
</div>

<script>
    const inputs = document.querySelectorAll(".input");


    function addcl() {
        let parent = this.parentNode.parentNode;
        parent.classList.add("focus");
    }

    function remcl() {
        let parent = this.parentNode.parentNode;
        if (this.value == "") {
            parent.classList.remove("focus");
        }
    }


    inputs.forEach(input => {
        input.addEventListener("focus", addcl);
        input.addEventListener("blur", remcl);
    });
</script>
@endsection

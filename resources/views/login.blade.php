<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>
        {{ __('auth.login_title') }} | DancePair
    </title>


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="login-page">


<div class="login-wrapper">


    <div class="login-card">


        <div class="login-logo">

            <x-ui.logo />

        </div>



        <div class="login-header">

            <h1>
                {{ __('auth.welcome_back') }}
            </h1>


            <p>
                {{ __('auth.login_subtitle') }}
            </p>

        </div>



        {{-- =================================================
           STATUS
        ================================================== --}}

        @if(session('status'))

            <div class="alert alert-success">

                {{ session('status') }}

            </div>

        @endif



        {{-- =================================================
           ERRORS
        ================================================== --}}

        @if($errors->any())

            <div class="alert alert-danger">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif



        {{-- =================================================
           LOGIN FORM
        ================================================== --}}

        <form
            method="POST"
            action="{{ route('login.store') }}"
        >

            @csrf



            {{-- EMAIL --}}

            <div class="mb-3">

                <label class="form-label">

                    {{ __('auth.email') }}

                </label>


                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control login-input"
                    placeholder="{{ __('auth.email_placeholder') }}"
                    autocomplete="email"
                    required
                >

            </div>



            {{-- PASSWORD --}}

            <div class="mb-2">

                <label class="form-label">

                    {{ __('auth.password') }}

                </label>


                <input
                    type="password"
                    name="password"
                    class="form-control login-input"
                    placeholder="{{ __('auth.password_placeholder') }}"
                    autocomplete="current-password"
                    required
                >

            </div>



            {{-- FORGOT PASSWORD --}}

            <div
                style="
                    display:flex;
                    justify-content:flex-end;
                    margin-bottom:18px;
                "
            >

                <a
                    href="{{ route('password.request') }}"
                    style="
                        font-size:12px;
                        font-weight:700;
                        text-decoration:none;
                        color:#6D28D9;
                    "
                >

                    {{ app()->getLocale() === 'fr'
                        ? 'Mot de passe oublié ?'
                        : 'Forgot password?'
                    }}

                </a>

            </div>



            <button
                type="submit"
                class="login-button"
            >

                {{ __('auth.login_button') }}

            </button>


        </form>



        {{-- =================================================
           REGISTER
        ================================================== --}}

        <div class="login-register">

            <span>
                {{ __('auth.no_account') }}
            </span>


            <a href="{{ route('register') }}">

                {{ __('auth.create_account') }}

            </a>

        </div>



        {{-- =================================================
           HOME
        ================================================== --}}

        <div class="login-home">

            <a href="{{ route('home') }}">

                ← {{ __('auth.back_home') }}

            </a>

        </div>


    </div>


</div>


</body>

</html>
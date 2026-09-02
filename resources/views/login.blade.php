<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ __('auth.login_title') }} | DancePair</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-page">

<div class="login-wrapper">

    <div class="login-card">

        <div class="login-logo">
            <x-ui.logo />
        </div>

        <div class="login-header">
            <h1>{{ __('auth.welcome_back') }}</h1>

            <p>
                {{ __('auth.login_subtitle') }}
            </p>
        </div>


        @if ($errors->any())

            <div class="alert alert-danger">

                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('login.store') }}"
        >

            @csrf


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
                    required
                >

            </div>


            <div class="mb-4">

                <label class="form-label">
                    {{ __('auth.password') }}
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control login-input"
                    placeholder="{{ __('auth.password_placeholder') }}"
                >

            </div>


            <button
                type="submit"
                class="login-button"
            >
                {{ __('auth.login_button') }}
            </button>

        </form>


        <div class="login-register">

            <span>
                {{ __('auth.no_account') }}
            </span>

            <a href="{{ route('register') }}">
                {{ __('auth.create_account') }}
            </a>

        </div>


        <div class="login-home">

            <a href="{{ route('home') }}">
                ← {{ __('auth.back_home') }}
            </a>

        </div>

    </div>

</div>

</body>

</html>
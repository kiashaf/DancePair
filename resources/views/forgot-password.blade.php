<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>
        {{ app()->getLocale() === 'fr'
            ? 'Mot de passe oublié'
            : 'Forgot password'
        }}
        | DancePair
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

                {{ app()->getLocale() === 'fr'
                    ? 'Mot de passe oublié ?'
                    : 'Forgot your password?'
                }}

            </h1>


            <p>

{{ app()->getLocale() === 'fr'

    ? 'Entrez votre adresse courriel. Si un compte existe pour cette adresse, nous vous enverrons un lien sécurisé pour réinitialiser votre mot de passe.'

    : 'Enter your email address. If an account exists for this email, we’ll send you a secure password reset link.'
}}

</p>

        </div>



        @if(session('status'))

            <div class="alert alert-success">

                {{ session('status') }}

            </div>

        @endif



        @if($errors->any())

            <div class="alert alert-danger">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif



        <form
            method="POST"
            action="{{ route('password.email') }}"
        >

            @csrf



            <div class="mb-4">

                <label class="form-label">

                    {{ app()->getLocale() === 'fr'
                        ? 'Adresse courriel'
                        : 'Email'
                    }}

                </label>


                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control login-input"
                    autocomplete="email"
                    required
                >

            </div>



            <button
                type="submit"
                class="login-button"
            >

                {{ app()->getLocale() === 'fr'
                    ? 'Envoyer le lien'
                    : 'Send reset link'
                }}

            </button>


        </form>



        <div class="login-home">

            <a href="{{ route('login') }}">

                ←

                {{ app()->getLocale() === 'fr'
                    ? 'Retour à la connexion'
                    : 'Back to login'
                }}

            </a>

        </div>


    </div>


</div>


</body>

</html>
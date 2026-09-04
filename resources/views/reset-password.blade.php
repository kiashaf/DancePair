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
            ? 'Nouveau mot de passe'
            : 'Reset password'
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
                    ? 'Créez un nouveau mot de passe'
                    : 'Create a new password'
                }}

            </h1>


            <p>

                {{ app()->getLocale() === 'fr'
                    ? 'Choisissez un nouveau mot de passe pour votre compte DancePair.'
                    : 'Choose a new password for your DancePair account.'
                }}

            </p>

        </div>



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
            action="{{ route('password.update') }}"
        >

            @csrf



            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >



            {{-- EMAIL --}}

            <div class="mb-3">

                <label class="form-label">

                    {{ app()->getLocale() === 'fr'
                        ? 'Adresse courriel'
                        : 'Email'
                    }}

                </label>


                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    class="form-control login-input"
                    autocomplete="email"
                    required
                >

            </div>



            {{-- PASSWORD --}}

            <div class="mb-3">

                <label class="form-label">

                    {{ app()->getLocale() === 'fr'
                        ? 'Nouveau mot de passe'
                        : 'New password'
                    }}

                </label>


                <input
                    type="password"
                    name="password"
                    class="form-control login-input"
                    autocomplete="new-password"
                    required
                >

            </div>



            {{-- CONFIRM --}}

            <div class="mb-4">

                <label class="form-label">

                    {{ app()->getLocale() === 'fr'
                        ? 'Confirmer le mot de passe'
                        : 'Confirm password'
                    }}

                </label>


                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control login-input"
                    autocomplete="new-password"
                    required
                >

            </div>



            <button
                type="submit"
                class="login-button"
            >

                {{ app()->getLocale() === 'fr'
                    ? 'Réinitialiser le mot de passe'
                    : 'Reset password'
                }}

            </button>


        </form>


    </div>


</div>


</body>

</html>
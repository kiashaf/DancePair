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
            ? 'Vérifiez votre courriel'
            : 'Verify your email'
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
                    ? 'Vérifiez votre adresse courriel'
                    : 'Verify your email address'
                }}

            </h1>


            <p>

                {{ app()->getLocale() === 'fr'
                    ? 'Nous avons envoyé un lien de vérification à votre adresse courriel.'
                    : 'We sent a verification link to your email address.'
                }}

            </p>

        </div>



        @if(
            session('status')
            ===
            'verification-link-sent'
        )

            <div class="alert alert-success">

                {{ app()->getLocale() === 'fr'

                    ? 'Un nouveau lien de vérification a été envoyé.'

                    : 'A new verification link has been sent.'
                }}

            </div>

        @endif



        <div
            style="
                padding:16px;
                margin-bottom:18px;
                border-radius:12px;
                background:#F8FAFC;
                color:#475569;
                font-size:13px;
                line-height:1.7;
            "
        >

            {{ app()->getLocale() === 'fr'

                ? 'Vous devez vérifier votre adresse courriel avant d’utiliser les fonctionnalités protégées de DancePair.'

                : 'You must verify your email address before using protected DancePair features.'
            }}

        </div>



        {{-- RESEND --}}

        <form
            method="POST"
            action="{{ route('verification.send') }}"
        >

            @csrf


            <button
                type="submit"
                class="login-button"
            >

                {{ app()->getLocale() === 'fr'
                    ? 'Renvoyer le courriel'
                    : 'Resend verification email'
                }}

            </button>

        </form>



        {{-- LOGOUT --}}

        <form
            method="POST"
            action="{{ route('logout') }}"
            style="margin-top:14px;"
        >

            @csrf


            <button
                type="submit"
                style="
                    width:100%;
                    padding:11px 16px;
                    border:1px solid #CBD5E1;
                    border-radius:10px;
                    background:#FFFFFF;
                    color:#475569;
                    font-weight:700;
                "
            >

                {{ app()->getLocale() === 'fr'
                    ? 'Se déconnecter'
                    : 'Logout'
                }}

            </button>

        </form>


    </div>


</div>


</body>

</html>
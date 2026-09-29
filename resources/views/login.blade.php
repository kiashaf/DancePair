<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="UTF-8">

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('logo/logo.png') }}"
    >

    <link
        rel="shortcut icon"
        type="image/png"
        href="{{ asset('logo/logo.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('logo/logo.png') }}"
    >


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    >


    <title>
        {{ __('auth.login_title') }} | DancePair
    </title>


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    <style>

        /*
        |--------------------------------------------------------------------------
        | MOBILE / INSTAGRAM LOGIN FIX
        |--------------------------------------------------------------------------
        */

        html {
            min-height: 100%;
            height: auto;
        }


        body.login-page {
            min-height: 100vh;
            min-height: 100svh;
            min-height: 100dvh;

            height: auto !important;

            margin: 0;

            overflow-x: hidden !important;
            overflow-y: auto !important;

            -webkit-overflow-scrolling: touch;
            overscroll-behavior-y: contain;
        }


        .login-wrapper {
            width: 100%;

            min-height: 100vh;
            min-height: 100svh;
            min-height: 100dvh;

            height: auto !important;

            display: flex;

            align-items: center;
            justify-content: center;

            padding:
                max(24px, env(safe-area-inset-top))
                16px
                max(30px, env(safe-area-inset-bottom));

            overflow: visible !important;
        }


        .login-card {
            width: 100%;
            max-width: 460px;

            flex-shrink: 0;
        }



        /*
        |--------------------------------------------------------------------------
        | INPUTS
        |--------------------------------------------------------------------------
        */

        .login-input {
            font-size: 16px !important;
        }



        /*
        |--------------------------------------------------------------------------
        | PASSWORD FIELD
        |--------------------------------------------------------------------------
        */

        .login-password-wrapper {
            position: relative;

            width: 100%;
        }


        .login-password-wrapper .login-input {
            width: 100%;

            padding-right: 52px !important;
        }



        /*
        |--------------------------------------------------------------------------
        | HIDE BROWSER NATIVE PASSWORD EYE
        |--------------------------------------------------------------------------
        |
        | Edge / some Chromium browsers can add their own password reveal icon.
        | We hide it because DancePair uses its own eye button.
        |
        */

        .login-password-wrapper input::-ms-reveal,
        .login-password-wrapper input::-ms-clear {
            display: none !important;

            width: 0 !important;
            height: 0 !important;
        }


        .login-password-wrapper
        input::-webkit-credentials-auto-fill-button {
            visibility: hidden !important;

            display: none !important;

            pointer-events: none !important;

            position: absolute !important;

            right: 0 !important;
        }


        .login-password-wrapper
        input::-webkit-contacts-auto-fill-button {
            visibility: hidden !important;

            display: none !important;

            pointer-events: none !important;
        }


        .login-password-wrapper
        input::-webkit-textfield-decoration-container {
            visibility: hidden;

            pointer-events: none;
        }


        .login-password-wrapper input {
            appearance: none;
            -webkit-appearance: none;
        }



        /*
        |--------------------------------------------------------------------------
        | CUSTOM DANCEPAIR PASSWORD EYE
        |--------------------------------------------------------------------------
        */

        .login-password-toggle {
            position: absolute;

            top: 50%;
            right: 10px;

            transform: translateY(-50%);

            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0;

            border: 0;
            outline: 0;

            background: transparent;

            color: #777184;

            cursor: pointer;

            z-index: 20;

            -webkit-tap-highlight-color: transparent;
        }


        .login-password-toggle:hover {
            color: #6D28D9;
        }


        .login-password-toggle:focus {
            outline: none;
            box-shadow: none;
        }


        .login-password-toggle svg {
            width: 21px;
            height: 21px;

            display: block;

            pointer-events: none;
        }


        .password-eye-hidden {
            display: none !important;
        }



        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 767px) {

            html,
            body.login-page {
                width: 100%;

                min-height: 100%;
            }


            body.login-page {
                position: static !important;
            }


            .login-wrapper {
                min-height: 100dvh;

                height: auto !important;

                align-items: flex-start;

                justify-content: center;

                padding-top:
                    max(
                        18px,
                        env(safe-area-inset-top)
                    );

                /*
                 * Extra room below the form is intentional.
                 * Safari / Instagram browser needs space
                 * to scroll when the mobile keyboard opens.
                 */

                padding-bottom:
                    calc(
                        180px
                        +
                        env(safe-area-inset-bottom)
                    );

                overflow: visible !important;
            }


            .login-card {
                width: 100%;
                max-width: 460px;

                margin: 0 auto;
            }


            /*
             * 16px prevents iPhone Safari from automatically
             * zooming the page when an input receives focus.
             */

            input,
            select,
            textarea,
            button {
                font-size: 16px;
            }

        }



        /*
        |--------------------------------------------------------------------------
        | WHEN MOBILE KEYBOARD IS OPEN
        |--------------------------------------------------------------------------
        */

        body.keyboard-open .login-wrapper {
            align-items: flex-start !important;

            min-height: auto !important;

            padding-top:
                max(
                    12px,
                    env(safe-area-inset-top)
                );

            padding-bottom: 260px;
        }


        body.keyboard-open .login-card {
            margin-top: 0;
        }

    </style>

</head>


<body class="login-page">


<div class="login-wrapper">


    <div class="login-card">


        {{-- =================================================
           LOGO
        ================================================== --}}

        <div class="login-logo">

            <x-ui.logo />

        </div>



        {{-- =================================================
           HEADER
        ================================================== --}}

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



            {{-- =================================================
               EMAIL
            ================================================== --}}

            <div class="mb-3">

                <label
                    class="form-label"
                    for="login_email"
                >

                    {{ __('auth.email') }}

                </label>


                <input
                    type="email"
                    id="login_email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control login-input"
                    placeholder="{{ __('auth.email_placeholder') }}"
                    autocomplete="email"
                    autocapitalize="none"
                    inputmode="email"
                    required
                >

            </div>



            {{-- =================================================
               PASSWORD
            ================================================== --}}

            <div class="mb-2">

                <label
                    class="form-label"
                    for="login_password"
                >

                    {{ __('auth.password') }}

                </label>


                <div class="login-password-wrapper">

                    <input
                        type="password"
                        id="login_password"
                        name="password"
                        class="form-control login-input"
                        placeholder="{{ __('auth.password_placeholder') }}"
                        autocomplete="current-password"
                        autocapitalize="none"
                        spellcheck="false"
                        required
                    >


                    <button
                        type="button"
                        id="login_password_toggle"
                        class="login-password-toggle"
                        aria-label="{{ app()->getLocale() === 'fr'
                            ? 'Afficher le mot de passe'
                            : 'Show password'
                        }}"
                        aria-pressed="false"
                    >

                        {{-- EYE OPEN --}}

                        <svg
                            id="password_eye_open"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >

                            <path
                                d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"
                            />

                            <circle
                                cx="12"
                                cy="12"
                                r="3"
                            />

                        </svg>


                        {{-- EYE CLOSED --}}

                        <svg
                            id="password_eye_closed"
                            class="password-eye-hidden"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >

                            <path
                                d="M3 3l18 18"
                            />

                            <path
                                d="M10.6 10.6a2 2 0 0 0 2.8 2.8"
                            />

                            <path
                                d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18 18 0 0 1-2.1 3.2"
                            />

                            <path
                                d="M6.6 6.6C3.8 8.4 2 12 2 12s3.5 8 10 8a10.3 10.3 0 0 0 4.1-.8"
                            />

                        </svg>

                    </button>

                </div>

            </div>



            {{-- =================================================
               FORGOT PASSWORD
            ================================================== --}}

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



            {{-- =================================================
               LOGIN BUTTON
            ================================================== --}}

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



<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            /*
            |--------------------------------------------------------------------------
            | SHOW / HIDE PASSWORD
            |--------------------------------------------------------------------------
            */

            const passwordInput =
                document.getElementById(
                    'login_password'
                );


            const passwordToggle =
                document.getElementById(
                    'login_password_toggle'
                );


            const eyeOpen =
                document.getElementById(
                    'password_eye_open'
                );


            const eyeClosed =
                document.getElementById(
                    'password_eye_closed'
                );


            if (
                passwordInput
                &&
                passwordToggle
            ) {

                passwordToggle.addEventListener(
                    'click',
                    function () {

                        const isHidden =
                            passwordInput.type
                            ===
                            'password';


                        /*
                         * SHOW / HIDE VALUE
                         */

                        passwordInput.type =
                            isHidden
                                ? 'text'
                                : 'password';



                        /*
                         * SWITCH ICON
                         */

                        if (eyeOpen) {

                            eyeOpen.classList.toggle(
                                'password-eye-hidden',
                                isHidden
                            );

                        }


                        if (eyeClosed) {

                            eyeClosed.classList.toggle(
                                'password-eye-hidden',
                                !isHidden
                            );

                        }



                        /*
                         * ACCESSIBILITY
                         */

                        passwordToggle.setAttribute(
                            'aria-pressed',
                            isHidden
                                ? 'true'
                                : 'false'
                        );


                        passwordToggle.setAttribute(
                            'aria-label',

                            isHidden
                                ? @json(
                                    app()->getLocale() === 'fr'
                                        ? 'Masquer le mot de passe'
                                        : 'Hide password'
                                )
                                : @json(
                                    app()->getLocale() === 'fr'
                                        ? 'Afficher le mot de passe'
                                        : 'Show password'
                                )
                        );



                        /*
                         * Keep focus on password.
                         *
                         * This is especially useful on mobile:
                         * clicking the eye should not dismiss
                         * the software keyboard.
                         */

                        try {

                            passwordInput.focus({
                                preventScroll: true
                            });

                        } catch (error) {

                            passwordInput.focus();

                        }

                    }
                );

            }



            /*
            |--------------------------------------------------------------------------
            | INSTAGRAM / IOS / MOBILE KEYBOARD FIX
            |--------------------------------------------------------------------------
            */

            const loginInputs =
                document.querySelectorAll(
                    '.login-input'
                );


            function scrollFocusedInputIntoView(
                input
            ) {

                window.setTimeout(
                    function () {

                        if (!input) {
                            return;
                        }


                        input.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center',
                            inline: 'nearest'
                        });

                    },
                    300
                );

            }



            /*
            |--------------------------------------------------------------------------
            | INPUT FOCUS
            |--------------------------------------------------------------------------
            */

            loginInputs.forEach(
                function (input) {

                    input.addEventListener(
                        'focus',
                        function () {

                            document.body
                                .classList
                                .add(
                                    'keyboard-open'
                                );


                            scrollFocusedInputIntoView(
                                this
                            );

                        }
                    );



                    /*
                    |--------------------------------------------------------------------------
                    | INPUT BLUR
                    |--------------------------------------------------------------------------
                    */

                    input.addEventListener(
                        'blur',
                        function () {

                            window.setTimeout(
                                function () {

                                    const activeElement =
                                        document.activeElement;


                                    if (
                                        !activeElement
                                        ||
                                        !activeElement.classList.contains(
                                            'login-input'
                                        )
                                    ) {

                                        document.body
                                            .classList
                                            .remove(
                                                'keyboard-open'
                                            );

                                    }

                                },
                                150
                            );

                        }
                    );

                }
            );



            /*
            |--------------------------------------------------------------------------
            | VISUAL VIEWPORT
            |--------------------------------------------------------------------------
            |
            | Safari and Instagram in-app browser can resize only the visible
            | viewport when the mobile keyboard opens.
            |
            | visualViewport lets us detect that change and move the focused
            | field back into the visible area.
            |
            */

            if (window.visualViewport) {

                let initialViewportHeight =
                    window.visualViewport.height;


                /*
                 * Do not constantly replace the initial height while
                 * the keyboard is open. Only update it when the viewport
                 * becomes larger than our saved value.
                 */

                window.addEventListener(
                    'orientationchange',
                    function () {

                        window.setTimeout(
                            function () {

                                initialViewportHeight =
                                    window.visualViewport.height;

                            },
                            500
                        );

                    }
                );


                window.visualViewport.addEventListener(
                    'resize',
                    function () {

                        const currentHeight =
                            window.visualViewport.height;


                        if (
                            currentHeight
                            >
                            initialViewportHeight
                        ) {

                            initialViewportHeight =
                                currentHeight;

                        }


                        const keyboardLikelyOpen =
                            currentHeight
                            <
                            initialViewportHeight * 0.78;


                        document.body
                            .classList
                            .toggle(
                                'keyboard-open',
                                keyboardLikelyOpen
                            );


                        const activeElement =
                            document.activeElement;


                        if (
                            keyboardLikelyOpen
                            &&
                            activeElement
                            &&
                            activeElement.classList.contains(
                                'login-input'
                            )
                        ) {

                            window.setTimeout(
                                function () {

                                    activeElement
                                        .scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center',
                                            inline: 'nearest'
                                        });

                                },
                                120
                            );

                        }

                    }
                );



                /*
                 * Some iPhones / Instagram versions also move
                 * the viewport without a normal resize event.
                 */

                window.visualViewport.addEventListener(
                    'scroll',
                    function () {

                        const activeElement =
                            document.activeElement;


                        if (
                            document.body.classList.contains(
                                'keyboard-open'
                            )
                            &&
                            activeElement
                            &&
                            activeElement.classList.contains(
                                'login-input'
                            )
                        ) {

                            window.setTimeout(
                                function () {

                                    activeElement
                                        .scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center',
                                            inline: 'nearest'
                                        });

                                },
                                80
                            );

                        }

                    }
                );

            }



            /*
            |--------------------------------------------------------------------------
            | FALLBACK FOR MOBILE BROWSERS WITHOUT VISUAL VIEWPORT
            |--------------------------------------------------------------------------
            */

            window.addEventListener(
                'resize',
                function () {

                    const activeElement =
                        document.activeElement;


                    if (
                        activeElement
                        &&
                        activeElement.classList
                        &&
                        activeElement.classList.contains(
                            'login-input'
                        )
                    ) {

                        window.setTimeout(
                            function () {

                                activeElement
                                    .scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center',
                                        inline: 'nearest'
                                    });

                            },
                            150
                        );

                    }

                }
            );

        }
    );

</script>


</body>

</html>
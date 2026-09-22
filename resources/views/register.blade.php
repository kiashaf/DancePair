<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ __('auth.register_title') }} | DancePair</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        .register-terms {
            display: flex;
            align-items: flex-start;
            gap: 10px;

            margin-bottom: 22px;

            color: #9B95A8;

            font-size: 12px;
            line-height: 1.55;
        }

        .register-terms input {
            width: 16px;
            height: 16px;

            margin-top: 2px;

            flex: 0 0 auto;

            cursor: pointer;

            accent-color: #F72585;
        }

        .register-terms label {
            margin: 0;

            cursor: pointer;
        }

        .register-terms a {
            color: #F72585;

            font-weight: 800;

            text-decoration: none;
        }

        .register-terms a:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body class="login-page">

<div class="login-wrapper">

    <div class="login-card">

        <div class="login-logo">
            <x-ui.logo />
        </div>

        <div class="login-header">

            <h1>
                {{ __('auth.create_your_account') }}
            </h1>

            <p>
                {{ __('auth.register_subtitle') }}
            </p>

        </div>


        @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('register.store') }}"
        >

            @csrf


            <div class="mb-3">

                <label class="form-label">
                    {{ __('auth.name') }}
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control login-input"
                    placeholder="{{ __('auth.name_placeholder') }}"
                    required
                >

            </div>


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


            <div class="mb-3">

                <label class="form-label">
                    {{ __('auth.i_want_to') }}
                </label>

                <select
                    name="role"
                    class="form-select login-input"
                    required
                >

                    <option value="">
                        {{ __('auth.choose_option') }}
                    </option>

                    <option
                        value="student"
                        {{ old('role') === 'student' ? 'selected' : '' }}
                    >
                        {{ __('auth.learn_dance') }}
                    </option>

                    <option
                        value="teacher"
                        {{ old('role') === 'teacher' ? 'selected' : '' }}
                    >
                        {{ __('auth.teach_dance') }}
                    </option>

                </select>

            </div>


            <div class="mb-3">

                <label class="form-label">
                    {{ __('auth.password') }}
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control login-input"
                    placeholder="{{ __('auth.create_password') }}"
                    autocomplete="new-password"
                    required
                >

            </div>


            <div class="mb-4">

                <label class="form-label">
                    {{ __('auth.confirm_password') }}
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control login-input"
                    placeholder="{{ __('auth.confirm_password_placeholder') }}"
                    autocomplete="new-password"
                    required
                >

            </div>


            {{-- =====================================================
               TERMS & CONDITIONS
            ===================================================== --}}

            <div class="register-terms">

                <input
                    type="checkbox"
                    id="terms"
                    name="terms"
                    value="1"
                    {{ old('terms') ? 'checked' : '' }}
                    required
                >


                <label for="terms">

                    @if(app()->getLocale() === 'fr')

                        J’accepte les

                        <a
                            href="{{ route('public.terms') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Conditions générales et la Politique d’annulation et de remboursement
                        </a>.

                    @else

                        I agree to the

                        <a
                            href="{{ route('public.terms') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Terms & Conditions and Cancellation & Refund Policy
                        </a>.

                    @endif

                </label>

            </div>


            <button
                type="submit"
                class="login-button"
            >
                {{ __('auth.create_account') }}
            </button>

        </form>


        <div class="login-register">

            <span>
                {{ __('auth.already_account') }}
            </span>

            <a href="{{ route('login') }}">
                {{ __('auth.login_button') }}
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
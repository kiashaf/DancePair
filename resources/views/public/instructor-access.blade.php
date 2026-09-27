@extends('public.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Voir le profil de l’instructeur | DancePair'
        : 'View Instructor Profile | DancePair'
)

@push('styles')

<style>

    .instructor-access-page {
        min-height: 72vh;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 70px 20px;

        background:
            radial-gradient(
                circle at 20% 20%,
                rgba(247, 37, 133, .10),
                transparent 30%
            ),
            radial-gradient(
                circle at 80% 70%,
                rgba(121, 55, 255, .12),
                transparent 35%
            ),
            #080716;
    }

    .instructor-access-card {
        width: 100%;
        max-width: 760px;

        padding: 48px 42px;

        text-align: center;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 26px;

        background:
            linear-gradient(
                145deg,
                #100E25,
                #0A0918
            );

        box-shadow:
            0 30px 80px
            rgba(0,0,0,.35);
    }

    .instructor-access-kicker {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 18px;

        padding: 7px 13px;

        border:
            1px solid rgba(247,37,133,.28);

        border-radius: 999px;

        background:
            rgba(247,37,133,.08);

        color: #FF85BC;

        font-size: 10px;
        font-weight: 900;
        letter-spacing: .14em;

        text-transform: uppercase;
    }

    .instructor-access-card h1 {
        margin:
            0
            auto
            18px;

        max-width: 620px;

        color: #FFFFFF;

        font-size:
            clamp(
                32px,
                5vw,
                48px
            );

        line-height: 1.05;

        font-weight: 950;

        letter-spacing: -1.8px;
    }

    .instructor-access-card p {
        max-width: 590px;

        margin:
            0
            auto
            30px;

        color: #A9A4B5;

        font-size: 15px;
        line-height: 1.75;
    }

    .instructor-access-actions {
        display: flex;

        align-items: center;
        justify-content: center;

        gap: 12px;

        flex-wrap: wrap;
    }

    .instructor-access-button {
        min-height: 48px;

        padding:
            0
            24px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border-radius: 12px;

        text-decoration: none;

        font-size: 13px;
        font-weight: 900;

        transition:
            transform .2s ease,
            opacity .2s ease,
            border-color .2s ease;
    }

    .instructor-access-button:hover {
        transform: translateY(-2px);

        opacity: .96;
    }

    .instructor-access-button-primary {
        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7937FF
            );

        box-shadow:
            0 14px 30px
            rgba(247,37,133,.20);
    }

    .instructor-access-button-secondary {
        color: #FFFFFF;

        border:
            1px solid rgba(255,255,255,.16);

        background:
            rgba(255,255,255,.04);
    }

    .instructor-access-note {
        margin-top: 22px;

        color: #777184;

        font-size: 11px;
        line-height: 1.6;
    }

    @media(max-width: 650px) {

        .instructor-access-card {
            padding:
                36px
                22px;
        }

        .instructor-access-actions {
            flex-direction: column;
        }

        .instructor-access-button {
            width: 100%;
        }

    }

</style>

@endpush



@section('content')

<section class="instructor-access-page">

    <div class="instructor-access-card">

        <div class="instructor-access-kicker">

            DancePair

        </div>



        <h1>

            {{ app()->getLocale() === 'fr'

                ? 'Rejoignez DancePair pour voir ce profil'

                : 'Join DancePair to view this instructor'

            }}

        </h1>



        <p>

            {{ app()->getLocale() === 'fr'

                ? 'Créez un compte gratuit ou connectez-vous pour consulter le profil complet de cet instructeur, son expérience, ses tarifs, ses styles de danse et ses disponibilités.'

                : 'Create a free account or sign in to view this instructor’s full profile, experience, rates, dance styles, and availability.'

            }}

        </p>



        <div class="instructor-access-actions">

            <a
                href="{{ route('register', [
                    'redirect' => route(
                        'student.teachers.show',
                        $teacher
                    )
                ]) }}"
                class="
                    instructor-access-button
                    instructor-access-button-primary
                "
            >

                {{ app()->getLocale() === 'fr'

                    ? 'Créer un compte'

                    : 'Join DancePair'

                }}

            </a>



            <a
                href="{{ route('login', [
                    'redirect' => route(
                        'student.teachers.show',
                        $teacher
                    )
                ]) }}"
                class="
                    instructor-access-button
                    instructor-access-button-secondary
                "
            >

                {{ app()->getLocale() === 'fr'

                    ? 'Se connecter'

                    : 'Log In'

                }}

            </a>

        </div>



        <div class="instructor-access-note">

            {{ app()->getLocale() === 'fr'

                ? 'L’inscription est gratuite.'

                : 'Creating an account is free.'

            }}

        </div>

    </div>

</section>

@endsection
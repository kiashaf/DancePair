@extends('public.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Devenir professeur | DancePair'
        : 'Become a Teacher | DancePair'
)


@push('styles')
<style>

    /* =====================================================
       HERO
    ===================================================== */

    .teach-hero {
        position: relative;

        min-height: 330px;

        display: flex;
        align-items: center;

        overflow: hidden;

        background:
            linear-gradient(
                90deg,
                rgba(6,5,20,.98),
                rgba(6,5,20,.84) 52%,
                rgba(6,5,20,.25)
            ),
            url('{{ asset('images/home/hero-dance.jpg') }}');

        background-size: auto 100%;
        background-position: right bottom;
        background-repeat: no-repeat;
    }


    .teach-hero::after {
        content: "";

        position: absolute;

        inset: 0;

        background:
            radial-gradient(
                circle at 20% 45%,
                rgba(247,37,133,.18),
                transparent 30%
            ),
            radial-gradient(
                circle at 75% 40%,
                rgba(121,55,255,.17),
                transparent 30%
            );
    }


    .teach-container {
        position: relative;

        z-index: 2;

        width: min(1450px, calc(100% - 80px));

        margin: auto;
    }


    .teach-copy {
        max-width: 700px;
    }


    .teach-kicker {
        display: inline-flex;

        padding: 7px 13px;

        margin-bottom: 14px;

        border:
            1px solid rgba(247,37,133,.28);

        border-radius: 999px;

        color: #FF83BA;

        background:
            rgba(247,37,133,.08);

        font-size: 10px;
        font-weight: 900;

        letter-spacing: .15em;
    }


    .teach-copy h1 {
        margin: 0;

        color: white;

        font-size: clamp(38px,4.3vw,58px);

        line-height: .96;

        font-weight: 950;

        letter-spacing: -3px;
    }


    .teach-copy h1 span {
        display: block;

        color: transparent;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #B72EF0,
                #7937FF
            );

        background-clip: text;
        -webkit-background-clip: text;
    }


    .teach-copy p {
        max-width: 590px;

        margin: 14px 0 0;

        color: #B5AFBF;

        font-size: 13px;
        line-height: 1.55;
    }


    .teach-hero-actions {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-top: 16px;
    }


    .teach-primary {
        min-height: 42px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 0 21px;

        border-radius: 11px;

        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7937FF
            );

        text-decoration: none;

        font-size: 11px;
        font-weight: 900;

        box-shadow:
            0 14px 30px
            rgba(247,37,133,.20);
    }


    .teach-secondary {
        min-height: 42px;

        display: inline-flex;

        align-items: center;

        padding: 0 19px;

        border:
            1px solid rgba(255,255,255,.16);

        border-radius: 11px;

        color: white;

        text-decoration: none;

        font-size: 11px;
        font-weight: 800;
    }


    /* =====================================================
       BENEFITS
    ===================================================== */

    .teach-benefits {
        padding: 34px 0;
    }


    .teach-section-head {
        max-width: 650px;

        margin-bottom: 20px;
    }


    .teach-section-head small {
        color: #F72585;

        font-size: 10px;

        font-weight: 900;

        letter-spacing: .15em;

        text-transform: uppercase;
    }


    .teach-section-head h2 {
        margin: 6px 0 8px;

        color: white;

        font-size: 28px;

        font-weight: 950;

        letter-spacing: -1.5px;
    }


    .teach-section-head p {
        margin: 0;

        color: #878290;

        font-size: 12px;

        line-height: 1.55;
    }


    .teach-grid {
        display: grid;

        grid-template-columns:
            repeat(4,1fr);

        gap: 12px;
    }


    .teach-card {
        min-height: 135px;

        padding: 18px;

        border:
            1px solid rgba(255,255,255,.07);

        border-radius: 18px;

        background:
            linear-gradient(
                145deg,
                #100E25,
                #090817
            );

        transition:
            transform .25s ease;
    }


    .teach-card:hover {
        transform:
            translateY(-5px);
    }


    .teach-number {
        width: 32px;
        height: 32px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 13px;

        border-radius: 10px;

        color: #FFFFFF;

        background:
            linear-gradient(
                135deg,
                #F72585,
                #7937FF
            );

        font-size: 10px;
        font-weight: 900;
    }


    .teach-card h3 {
        margin: 0 0 6px;

        color: white;

        font-size: 14px;
        font-weight: 900;
    }


    .teach-card p {
        margin: 0;

        color: #817B8C;

        font-size: 10px;

        line-height: 1.5;
    }


    /* =====================================================
       STEPS
    ===================================================== */

    .teach-steps-section {
        padding: 34px 0;

        background:
            radial-gradient(
                circle at 75% 50%,
                rgba(121,55,255,.13),
                transparent 35%
            ),
            #090817;
    }


    .teach-steps {
        display: grid;

        grid-template-columns:
            repeat(5,1fr);

        gap: 10px;
    }


    .teach-step {
        position: relative;

        padding: 16px;

        border-radius: 16px;

        border:
            1px solid rgba(255,255,255,.07);

        background:
            rgba(255,255,255,.025);
    }


    .teach-step strong {
        display: block;

        margin-bottom: 6px;

        color: #FFFFFF;

        font-size: 11px;
    }


    .teach-step span {
        color: #797485;

        font-size: 9px;

        line-height: 1.5;
    }


    /* =====================================================
       FINAL CTA
    ===================================================== */

    .teach-final {
        padding: 34px 0;
    }


    .teach-final-box {
        position: relative;

        overflow: hidden;

        padding: 30px;

        border-radius: 22px;

        text-align: center;

        border:
            1px solid rgba(255,255,255,.08);

        background:
            radial-gradient(
                circle at 50% 120%,
                rgba(247,37,133,.30),
                transparent 45%
            ),
            radial-gradient(
                circle at 80% 20%,
                rgba(121,55,255,.27),
                transparent 32%
            ),
            #100D26;
    }


    .teach-final-box h2 {
        margin: 0;

        color: white;

        font-size: 29px;

        font-weight: 950;

        letter-spacing: -1.5px;
    }


    .teach-final-box p {
        max-width: 550px;

        margin: 9px auto 17px;

        color: #9A95A5;

        line-height: 1.55;

        font-size: 12px;
    }


    @media(max-width:1000px) {

        .teach-grid {
            grid-template-columns:
                repeat(2,1fr);
        }

        .teach-steps {
            grid-template-columns:
                repeat(2,1fr);
        }

    }


    @media(max-width:650px) {

        .teach-container {
            width: calc(100% - 28px);
        }

        .teach-hero {
            min-height: 430px;

            background-size: auto 67%;
            background-position: 80% bottom;
        }

        .teach-copy h1 {
            font-size: 40px;
            letter-spacing: -2px;
        }

        .teach-copy p {
            font-size: 12px;
        }

        .teach-hero-actions {
            align-items: flex-start;
            flex-direction: column;
        }

        .teach-grid,
        .teach-steps {
            grid-template-columns: 1fr;
        }

        .teach-final-box {
            padding: 26px 20px;
        }

        .teach-final-box h2 {
            font-size: 26px;
        }

    }

</style>
@endpush



@section('content')


{{-- =========================================================
   HERO
========================================================= --}}

<section class="teach-hero">

    <div class="teach-container">

        <div class="teach-copy">

            <div class="teach-kicker">

                {{ app()->getLocale() === 'fr'
                    ? 'ENSEIGNEZ • INSPIREZ • ÉVOLUEZ'
                    : 'TEACH • INSPIRE • GROW'
                }}

            </div>


            <h1>

                {{ app()->getLocale() === 'fr'
                    ? 'Votre talent en danse'
                    : 'Your Dance Skills'
                }}

                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'mérite d’être partagé.'
                        : 'Deserve to Be Seen.'
                    }}

                </span>

            </h1>


            <p>

                {{ app()->getLocale() === 'fr'
                    ? 'Transformez votre expérience en nouvelles occasions. Créez votre profil professionnel DancePair, rejoignez de nouveaux élèves et enseignez selon vos propres conditions.'
                    : 'Turn your experience into new opportunities. Build your professional DancePair profile, reach new students, and teach on your own terms.'
                }}

            </p>


            <div class="teach-hero-actions">

                @guest

                    <a
                        href="{{ route('register') }}"
                        class="teach-primary"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Devenir professeur'
                            : 'Become a Teacher'
                        }}

                    </a>

                @else

                    @if(auth()->user()->role === 'teacher')

                        <a
                            href="{{ route('teacher.dashboard') }}"
                            class="teach-primary"
                        >

                            {{ app()->getLocale() === 'fr'
                                ? 'Tableau de bord professeur'
                                : 'Teacher Dashboard'
                            }}

                        </a>

                    @endif

                @endguest


                <a
                    href="#how-teaching-works"
                    class="teach-secondary"
                >

                    {{ app()->getLocale() === 'fr'
                        ? 'Voir comment ça fonctionne'
                        : 'See How It Works'
                    }}

                </a>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
   BENEFITS
========================================================= --}}

<section class="teach-benefits">

    <div class="teach-container">


        <div class="teach-section-head">

            <small>

                {{ app()->getLocale() === 'fr'
                    ? 'POURQUOI DANCEPAIR'
                    : 'WHY DANCEPAIR'
                }}

            </small>


            <h2>

                {{ app()->getLocale() === 'fr'
                    ? 'Développez votre activité d’enseignement'
                    : 'Build Your Teaching Business'
                }}

            </h2>


            <p>

                {{ app()->getLocale() === 'fr'
                    ? 'DancePair offre aux professeurs de danse indépendants un espace professionnel pour présenter leur expertise, gérer leurs disponibilités et entrer en contact avec de nouveaux élèves.'
                    : 'DancePair gives independent dance teachers a professional place to showcase their expertise, manage their availability, and connect with new students.'
                }}

            </p>

        </div>


        <div class="teach-grid">


            {{-- CARD 01 --}}
            <article class="teach-card">

                <div class="teach-number">
                    01
                </div>


                <h3>

                    {{ app()->getLocale() === 'fr'
                        ? 'Fixez vos propres tarifs'
                        : 'Set Your Own Rates'
                    }}

                </h3>


                <p>

                    {{ app()->getLocale() === 'fr'
                        ? 'Déterminez la valeur de vos cours et fixez vos tarifs pour chaque style de danse que vous enseignez.'
                        : 'Decide what your lessons are worth and set your own rates for each dance style you teach.'
                    }}

                </p>

            </article>



            {{-- CARD 02 --}}
            <article class="teach-card">

                <div class="teach-number">
                    02
                </div>


                <h3>

                    {{ app()->getLocale() === 'fr'
                        ? 'Contrôlez votre horaire'
                        : 'Control Your Schedule'
                    }}

                </h3>


                <p>

                    {{ app()->getLocale() === 'fr'
                        ? 'Choisissez les jours et les heures où vous souhaitez enseigner tout en gardant le contrôle de votre emploi du temps.'
                        : 'Choose the days and times you want to teach while staying in control of your schedule.'
                    }}

                </p>

            </article>



            {{-- CARD 03 --}}
            <article class="teach-card">

                <div class="teach-number">
                    03
                </div>


                <h3>

                    {{ app()->getLocale() === 'fr'
                        ? 'Rejoignez de nouveaux élèves'
                        : 'Reach New Students'
                    }}

                </h3>


                <p>

                    {{ app()->getLocale() === 'fr'
                        ? 'Faites-vous découvrir par des élèves qui recherchent activement des professeurs de danse correspondant à leurs besoins.'
                        : 'Get discovered by students who are actively searching for dance teachers that match their needs.'
                    }}

                </p>

            </article>



            {{-- CARD 04 --}}
            <article class="teach-card">

                <div class="teach-number">
                    04
                </div>


                <h3>

                    {{ app()->getLocale() === 'fr'
                        ? 'Développez votre réputation'
                        : 'Build Your Reputation'
                    }}

                </h3>


                <p>

                    {{ app()->getLocale() === 'fr'
                        ? 'Renforcez votre profil grâce à votre expérience, vos cours réussis et aux évaluations de vos élèves.'
                        : 'Strengthen your profile through your experience, successful lessons, and genuine student reviews.'
                    }}

                </p>

            </article>


        </div>

    </div>

</section>



{{-- =========================================================
   STEPS
========================================================= --}}

<section
    class="teach-steps-section"
    id="how-teaching-works"
>

    <div class="teach-container">


        <div class="teach-section-head">

            <small>

                {{ app()->getLocale() === 'fr'
                    ? 'POUR COMMENCER'
                    : 'GETTING STARTED'
                }}

            </small>


            <h2>

                {{ app()->getLocale() === 'fr'
                    ? 'Commencez à enseigner en quelques étapes'
                    : 'Start Teaching in a Few Simple Steps'
                }}

            </h2>

        </div>


        <div class="teach-steps">


            {{-- STEP 01 --}}
            <div class="teach-step">

                <strong>

                    {{ app()->getLocale() === 'fr'
                        ? '01. Créez votre compte'
                        : '01. Create Your Account'
                    }}

                </strong>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Inscrivez-vous sur DancePair comme professeur.'
                        : 'Join DancePair and register as a teacher.'
                    }}

                </span>

            </div>



            {{-- STEP 02 --}}
            <div class="teach-step">

                <strong>

                    {{ app()->getLocale() === 'fr'
                        ? '02. Créez votre profil'
                        : '02. Build Your Profile'
                    }}

                </strong>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Ajoutez votre photo, votre présentation et votre expérience.'
                        : 'Add your photo, bio, and teaching experience.'
                    }}

                </span>

            </div>



            {{-- STEP 03 --}}
            <div class="teach-step">

                <strong>

                    {{ app()->getLocale() === 'fr'
                        ? '03. Ajoutez vos styles'
                        : '03. Add Your Dance Styles'
                    }}

                </strong>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Choisissez les styles que vous enseignez et fixez vos tarifs.'
                        : 'Choose the styles you teach and set your rates.'
                    }}

                </span>

            </div>



            {{-- STEP 04 --}}
            <div class="teach-step">

                <strong>

                    {{ app()->getLocale() === 'fr'
                        ? '04. Définissez vos disponibilités'
                        : '04. Set Your Availability'
                    }}

                </strong>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Indiquez aux élèves les moments où vous êtes disponible.'
                        : 'Let students know when you are available to teach.'
                    }}

                </span>

            </div>



            {{-- STEP 05 --}}
            <div class="teach-step">

                <strong>

                    {{ app()->getLocale() === 'fr'
                        ? '05. Commencez à enseigner'
                        : '05. Start Teaching'
                    }}

                </strong>


                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'Recevez des demandes de cours et développez votre présence sur DancePair.'
                        : 'Receive lesson requests and grow your presence on DancePair.'
                    }}

                </span>

            </div>


        </div>

    </div>

</section>



{{-- =========================================================
   FINAL CTA
========================================================= --}}

<section class="teach-final">

    <div class="teach-container">

        <div class="teach-final-box">


            <h2>

                {{ app()->getLocale() === 'fr'
                    ? 'Prêt à partager votre passion ?'
                    : 'Ready to Share Your Passion?'
                }}

            </h2>


            <p>

                {{ app()->getLocale() === 'fr'
                    ? 'Créez votre profil de professeur et commencez à rencontrer des danseurs qui recherchent exactement ce que vous enseignez.'
                    : 'Create your teacher profile and start connecting with dancers who are looking for exactly what you teach.'
                }}

            </p>


            @guest

                <a
                    href="{{ route('register') }}"
                    class="teach-primary"
                >

                    {{ app()->getLocale() === 'fr'
                        ? 'Rejoindre DancePair comme professeur'
                        : 'Join DancePair as a Teacher'
                    }}

                </a>

            @else

                @if(auth()->user()->role === 'teacher')

                    <a
                        href="{{ route('teacher.dashboard') }}"
                        class="teach-primary"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Accéder au tableau de bord'
                            : 'Go to Teacher Dashboard'
                        }}

                    </a>

                @endif

            @endguest

        </div>

    </div>

</section>


@endsection
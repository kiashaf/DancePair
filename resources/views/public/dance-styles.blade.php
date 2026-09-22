@extends('public.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Styles de danse | DancePair'
        : 'Dance Styles | DancePair'
)


@push('styles')
<style>

    /* =========================================================
       HERO
    ========================================================= */

    .styles-hero {
        position: relative;

        min-height: 330px;

        display: flex;
        align-items: center;

        overflow: hidden;

        background:
            linear-gradient(
                90deg,
                rgba(6,5,20,.98) 0%,
                rgba(6,5,20,.90) 44%,
                rgba(6,5,20,.35) 100%
            ),
            url('{{ asset('images/home/hero-dance.jpg') }}');

        background-size: auto 100%;
        background-position: right bottom;
        background-repeat: no-repeat;
    }


    .styles-hero::before {
        content: "";

        position: absolute;

        inset: 0;

        background:
            radial-gradient(
                circle at 25% 35%,
                rgba(247,37,133,.16),
                transparent 30%
            ),
            radial-gradient(
                circle at 75% 50%,
                rgba(121,55,255,.18),
                transparent 35%
            );
    }


    .styles-container {
        position: relative;

        z-index: 2;

        width: min(1450px, calc(100% - 80px));

        margin: 0 auto;
    }


    .styles-copy {
        max-width: 720px;
    }


    .styles-kicker {
        display: inline-flex;
        align-items: center;

        padding: 7px 13px;

        margin-bottom: 14px;

        border:
            1px solid rgba(247,37,133,.30);

        border-radius: 999px;

        color: #FF87BD;

        background:
            rgba(247,37,133,.08);

        font-size: 10px;
        font-weight: 900;

        letter-spacing: .15em;
    }


    .styles-copy h1 {
        margin: 0;

        color: #FFFFFF;

        font-size: clamp(38px,4.3vw,58px);
        line-height: .96;

        font-weight: 950;

        letter-spacing: -3px;
    }


    .styles-copy h1 span {
        display: block;

        color: transparent;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #B72EF0,
                #7937FF
            );

        -webkit-background-clip: text;
        background-clip: text;
    }


    .styles-copy p {
        max-width: 600px;

        margin: 14px 0 0;

        color: #B6B0C0;

        font-size: 13px;
        line-height: 1.55;
    }


    /* =========================================================
       INTRO
    ========================================================= */

    .styles-section {
        padding: 34px 0;
    }


    .styles-section-head {
        display: flex;

        align-items: end;
        justify-content: space-between;

        gap: 30px;

        margin-bottom: 20px;
    }


    .styles-section-head small {
        display: block;

        color: #F72585;

        font-size: 10px;
        font-weight: 900;

        letter-spacing: .15em;

        text-transform: uppercase;
    }


    .styles-section-head h2 {
        margin: 6px 0 0;

        color: #FFFFFF;

        font-size: 28px;
        font-weight: 950;

        letter-spacing: -1.5px;
    }


    .styles-section-head p {
        max-width: 480px;

        margin: 0;

        color: #8A8495;

        font-size: 12px;
        line-height: 1.55;
    }


    /* =========================================================
       STYLE CARDS
    ========================================================= */

    .styles-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 12px;
    }


    .style-card {
        position: relative;

        overflow: hidden;

        min-height: 175px;

        display: flex;
        align-items: flex-end;

        padding: 18px;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 18px;

        background:
            linear-gradient(
                145deg,
                #120F29,
                #090817
            );

        transition:
            transform .25s ease,
            border-color .25s ease;
    }


    .style-card::before {
        content: "";

        position: absolute;

        width: 130px;
        height: 130px;

        right: -35px;
        top: -35px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(121,55,255,.30),
                transparent 70%
            );
    }


    .style-card:nth-child(2n)::before {
        background:
            radial-gradient(
                circle,
                rgba(247,37,133,.28),
                transparent 70%
            );
    }


    .style-card:hover {
        transform:
            translateY(-5px);

        border-color:
            rgba(247,37,133,.28);
    }


    .style-card-content {
        position: relative;

        z-index: 2;
    }


    .style-card-number {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 32px;
        height: 32px;

        margin-bottom: 18px;

        padding: 0 8px;

        border-radius: 9px;

        color: #FFFFFF;

        background:
            linear-gradient(
                135deg,
                #F72585,
                #7937FF
            );

        font-size: 9px;
        font-weight: 900;
    }


    .style-card h3 {
        margin: 0 0 6px;

        color: #FFFFFF;

        font-size: 17px;
        font-weight: 900;
    }


    .style-card p {
        max-width: 330px;

        margin: 0 0 11px;

        color: #817B8E;

        font-size: 10px;
        line-height: 1.5;
    }


    .style-card a {
        color: #F72585;

        text-decoration: none;

        font-size: 10px;
        font-weight: 850;
    }


    /* =========================================================
       DYNAMIC STYLE LIST
    ========================================================= */

    .styles-all {
        padding: 34px 0;

        background:
            radial-gradient(
                circle at 80% 40%,
                rgba(121,55,255,.12),
                transparent 32%
            ),
            #090817;
    }


    .styles-tags {
        display: flex;

        flex-wrap: wrap;

        gap: 8px;

        margin-top: 18px;
    }


    .styles-tag {
        display: inline-flex;

        align-items: center;

        min-height: 34px;

        padding: 0 13px;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 999px;

        color: #D7D2DE;

        background:
            rgba(255,255,255,.035);

        text-decoration: none;

        font-size: 10px;
        font-weight: 750;

        transition:
            background .2s ease,
            border-color .2s ease,
            transform .2s ease;
    }


    .styles-tag:hover {
        transform:
            translateY(-2px);

        border-color:
            rgba(247,37,133,.30);

        background:
            rgba(247,37,133,.08);
    }


    /* =========================================================
       BOTTOM CTA
    ========================================================= */

    .styles-final {
        padding: 34px 0;
    }


    .styles-final-box {
        padding: 30px;

        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 30px;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 22px;

        background:
            radial-gradient(
                circle at 80% 30%,
                rgba(121,55,255,.28),
                transparent 35%
            ),
            radial-gradient(
                circle at 20% 100%,
                rgba(247,37,133,.16),
                transparent 35%
            ),
            #100D25;
    }


    .styles-final-box h2 {
        margin: 0 0 7px;

        color: #FFFFFF;

        font-size: 28px;
        font-weight: 950;

        letter-spacing: -1.5px;
    }


    .styles-final-box p {
        max-width: 580px;

        margin: 0;

        color: #938D9F;

        font-size: 12px;
        line-height: 1.55;
    }


    .styles-final-button {
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

        white-space: nowrap;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width:1000px) {

        .styles-grid {
            grid-template-columns:
                repeat(2,1fr);
        }

    }


    @media(max-width:650px) {

        .styles-container {
            width:
                calc(100% - 28px);
        }


        .styles-hero {
            min-height: 430px;

            background-size:
                auto 67%;

            background-position:
                80% bottom;
        }


        .styles-copy h1 {
            font-size: 40px;

            letter-spacing: -2px;
        }


        .styles-copy p {
            font-size: 12px;
        }


        .styles-grid {
            grid-template-columns: 1fr;
        }


        .styles-section-head,
        .styles-final-box {
            align-items: flex-start;

            flex-direction: column;
        }


        .styles-final-box {
            padding: 26px 20px;
        }

    }

</style>
@endpush



@section('content')


{{-- =========================================================
   HERO
========================================================= --}}

<section class="styles-hero">

    <div class="styles-container">

        <div class="styles-copy">


            <div class="styles-kicker">

                {{ app()->getLocale() === 'fr'
                    ? 'BOUGEZ • EXPRIMEZ-VOUS • DÉCOUVREZ'
                    : 'MOVE • EXPRESS • DISCOVER'
                }}

            </div>


            <h1>

                {{ app()->getLocale() === 'fr'
                    ? 'Trouvez le style'
                    : 'Find the Style'
                }}

                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'qui vous fait vibrer.'
                        : 'That Moves You.'
                    }}

                </span>

            </h1>


            <p>

                {{ app()->getLocale() === 'fr'
                    ? 'Chaque danse possède son propre rythme, son énergie et sa personnalité. Explorez différents styles et découvrez celui qui vous correspond vraiment.'
                    : 'Every dance has its own rhythm, energy, and personality. Explore different styles and discover the one that feels right for you.'
                }}

            </p>

        </div>

    </div>

</section>



{{-- =========================================================
   POPULAR STYLES
========================================================= --}}

<section class="styles-section">

    <div class="styles-container">


        <div class="styles-section-head">

            <div>

                <small>

                    {{ app()->getLocale() === 'fr'
                        ? 'EXPLOREZ LA DANSE'
                        : 'EXPLORE DANCE'
                    }}

                </small>


                <h2>

                    {{ app()->getLocale() === 'fr'
                        ? 'Des styles pour toutes les envies'
                        : 'Popular Ways to Move'
                    }}

                </h2>

            </div>


            <p>

                {{ app()->getLocale() === 'fr'
                    ? 'Que vous recherchiez la connexion, la confiance, la forme physique, la performance ou simplement le plaisir, il existe un style de danse pour vous.'
                    : 'Whether you are looking for connection, confidence, fitness, performance, or fun, there is a dance style waiting for you.'
                }}

            </p>

        </div>



        <div class="styles-grid">


            {{-- =================================================
               LATIN
            ================================================= --}}

            <article class="style-card">

                <div class="style-card-content">


                    <div class="style-card-number">
                        01
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Danse latine'
                            : 'Latin Dance'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Découvrez des danses sociales pleines de rythme, de connexion et d’énergie.'
                            : 'Discover social dance styles full of rhythm, connection, and energy.'
                        }}

                    </p>


                    <a
                        href="{{ route('public.find-teacher') }}"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Trouver un professeur de danse latine'
                            : 'Find Latin Dance Teachers'
                        }}
                        →

                    </a>

                </div>

            </article>



            {{-- =================================================
               HIP HOP
            ================================================= --}}

            <article class="style-card">

                <div class="style-card-content">


                    <div class="style-card-number">
                        02
                    </div>


                    <h3>
                        Hip Hop
                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Développez votre confiance, votre musicalité et votre mouvement grâce à des styles urbains dynamiques.'
                            : 'Build confidence, musicality, and movement through high-energy urban dance styles.'
                        }}

                    </p>


                    <a
                        href="{{ route('public.find-teacher') }}"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Trouver un professeur de Hip Hop'
                            : 'Find Hip Hop Teachers'
                        }}
                        →

                    </a>

                </div>

            </article>



            {{-- =================================================
               BALLROOM
            ================================================= --}}

            <article class="style-card">

                <div class="style-card-content">


                    <div class="style-card-number">
                        03
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Danse de salon'
                            : 'Ballroom'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Apprenez la danse en couple avec élégance, technique et confiance pour toutes les occasions.'
                            : 'Learn elegant partner dancing, technique, and confidence for any occasion.'
                        }}

                    </p>


                    <a
                        href="{{ route('public.find-teacher') }}"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Trouver un professeur de danse de salon'
                            : 'Find Ballroom Teachers'
                        }}
                        →

                    </a>

                </div>

            </article>



            {{-- =================================================
               BALLET
            ================================================= --}}

            <article class="style-card">

                <div class="style-card-content">


                    <div class="style-card-number">
                        04
                    </div>


                    <h3>
                        Ballet
                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Développez votre équilibre, votre posture, votre contrôle et une technique classique raffinée.'
                            : 'Develop balance, posture, control, and beautiful classical technique.'
                        }}

                    </p>


                    <a
                        href="{{ route('public.find-teacher') }}"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Trouver un professeur de ballet'
                            : 'Find Ballet Teachers'
                        }}
                        →

                    </a>

                </div>

            </article>



            {{-- =================================================
               CONTEMPORARY
            ================================================= --}}

            <article class="style-card">

                <div class="style-card-content">


                    <div class="style-card-number">
                        05
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Danse contemporaine'
                            : 'Contemporary'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Explorez le mouvement expressif, la musicalité et la liberté créative.'
                            : 'Explore expressive movement, musicality, and creative freedom.'
                        }}

                    </p>


                    <a
                        href="{{ route('public.find-teacher') }}"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Trouver un professeur de danse contemporaine'
                            : 'Find Contemporary Teachers'
                        }}
                        →

                    </a>

                </div>

            </article>



            {{-- =================================================
               WEDDING DANCE
            ================================================= --}}

            <article class="style-card">

                <div class="style-card-content">


                    <div class="style-card-number">
                        06
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Danse de mariage'
                            : 'Wedding Dance'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Créez une première danse mémorable grâce à un accompagnement privé et personnalisé.'
                            : 'Create a memorable first dance with personalized private instruction.'
                        }}

                    </p>


                    <a
                        href="{{ route('public.find-teacher') }}"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Trouver un professeur de danse de mariage'
                            : 'Find Wedding Dance Teachers'
                        }}
                        →

                    </a>

                </div>

            </article>


        </div>

    </div>

</section>



{{-- =========================================================
   ALL STYLES
========================================================= --}}

<section class="styles-all">

    <div class="styles-container">


        <div class="styles-section-head">

            <div>

                <small>

                    {{ app()->getLocale() === 'fr'
                        ? 'TOUS LES STYLES'
                        : 'ALL STYLES'
                    }}

                </small>


                <h2>

                    {{ app()->getLocale() === 'fr'
                        ? 'Découvrez tous les styles sur DancePair'
                        : 'Explore What DancePair Offers'
                    }}

                </h2>

            </div>


            <p>

                {{ app()->getLocale() === 'fr'
                    ? 'DancePair évolue avec ses professeurs. À mesure que de nouveaux professeurs nous rejoignent, davantage de styles deviennent disponibles pour les élèves.'
                    : 'DancePair grows with its teachers. As new teachers join, more dance styles become available for students to discover.'
                }}

            </p>

        </div>



        @isset($danceStyles)

            @if($danceStyles->count())

                <div class="styles-tags">

                    @foreach($danceStyles as $style)

                        <a
                            href="{{ route(
                                'public.find-teacher',
                                [
                                    'dance_style_id'
                                    =>
                                    $style->id
                                ]
                            ) }}"
                            class="styles-tag"
                        >
                            {{ $style->name }}
                        </a>

                    @endforeach

                </div>

            @endif

        @endisset


    </div>

</section>



{{-- =========================================================
   FINAL CTA
========================================================= --}}

<section class="styles-final">

    <div class="styles-container">

        <div class="styles-final-box">


            <div>

                <h2>

                    {{ app()->getLocale() === 'fr'
                        ? 'Vous avez trouvé un style qui vous plaît ?'
                        : 'Found a Style You Love?'
                    }}

                </h2>


                <p>

                    {{ app()->getLocale() === 'fr'
                        ? 'Trouvez un professeur spécialisé dans ce style, comparez les profils et commencez à apprendre à votre rythme.'
                        : 'Find a teacher who specializes in it, compare profiles, and start learning at your own pace.'
                    }}

                </p>

            </div>


            <a
                href="{{ route('public.find-teacher') }}"
                class="styles-final-button"
            >

                {{ app()->getLocale() === 'fr'
                    ? 'Trouver un professeur'
                    : 'Find a Teacher'
                }}

            </a>


        </div>

    </div>

</section>


@endsection
@extends('public.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Partenariats | DancePair'
        : 'Partnerships | DancePair'
)


@push('styles')

<style>

    /* =========================================================
       PARTNERSHIPS PAGE
    ========================================================= */

    .partnerships-page {
        background: #070615;
    }


    /* =========================================================
       HERO
    ========================================================= */

    .partnerships-hero {
        position: relative;

        overflow: hidden;

        padding:
            34px
            0
            38px;

        border-bottom:
            1px solid rgba(255,255,255,.06);

        background:
            linear-gradient(
                90deg,
                rgba(7,6,21,.99) 0%,
                rgba(7,6,21,.96) 35%,
                rgba(7,6,21,.78) 58%,
                rgba(7,6,21,.28) 100%
            ),
            url('{{ asset('images/home/hero-dance.jpg') }}');

        background-size:
            auto,
            auto 100%;

        background-position:
            center,
            right bottom;

        background-repeat:
            no-repeat,
            no-repeat;
    }


    .partnerships-hero::before {
        content: "";

        position: absolute;

        inset: 0;

        background:
            radial-gradient(
                circle at 18% 35%,
                rgba(247,37,133,.10),
                transparent 28%
            ),
            radial-gradient(
                circle at 76% 40%,
                rgba(121,55,255,.14),
                transparent 30%
            );

        pointer-events: none;
    }


    .partnerships-hero::after {
        content: "";

        position: absolute;

        width: 320px;
        height: 320px;

        right: -100px;
        top: -120px;

        border-radius: 50%;

        background:
            rgba(247,37,133,.06);

        filter: blur(60px);

        pointer-events: none;
    }


    .partnerships-container {
        position: relative;

        z-index: 2;

        width: min(
            1200px,
            calc(100% - 80px)
        );

        margin: 0 auto;
    }


    .partnerships-hero-content {
        max-width: 780px;
    }


    .partnerships-eyebrow {
        display: inline-flex;

        align-items: center;

        min-height: 28px;

        padding: 0 12px;

        margin-bottom: 13px;

        border:
            1px solid rgba(255,255,255,.10);

        border-radius: 999px;

        color: #F2B8D9;

        background:
            rgba(255,255,255,.06);

        font-size: 9px;
        font-weight: 850;

        letter-spacing: .11em;

        text-transform: uppercase;
    }


    .partnerships-hero h1 {
        max-width: 780px;

        margin: 0;

        color: #FFFFFF;

        font-size:
            clamp(
                38px,
                4.2vw,
                58px
            );

        line-height: 1;

        font-weight: 950;

        letter-spacing: -2.5px;
    }


    .partnerships-gradient {
        display: block;

        color: transparent;

        background:
            linear-gradient(
                90deg,
                #FF238C,
                #C52EF0,
                #7937FF
            );

        background-clip: text;
        -webkit-background-clip: text;
    }


    .partnerships-hero-description {
        max-width: 620px;

        margin: 14px 0 0;

        color: #AAA4B8;

        font-size: 13px;
        line-height: 1.55;
    }


    .partnerships-hero-action {
        margin-top: 17px;
    }


    .partnerships-primary-btn {
        min-height: 40px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 0 19px;

        border-radius: 10px;

        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7937FF
            );

        box-shadow:
            0 12px 28px
            rgba(247,37,133,.20);

        text-decoration: none;

        font-size: 11px;
        font-weight: 850;

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }


    .partnerships-primary-btn:hover {
        color: #FFFFFF;

        transform: translateY(-2px);

        box-shadow:
            0 16px 34px
            rgba(247,37,133,.26);
    }


    /* =========================================================
       PARTNERSHIP OPTIONS
    ========================================================= */

    .partnerships-section {
        padding:
            34px
            0
            36px;
    }


    .partnerships-section-header {
        max-width: 680px;

        margin-bottom: 20px;
    }


    .partnerships-section-header span {
        color: #F72585;

        font-size: 9px;
        font-weight: 850;

        letter-spacing: .10em;

        text-transform: uppercase;
    }


    .partnerships-section-header h2 {
        margin:
            6px
            0
            0;

        color: #FFFFFF;

        font-size: 27px;
        font-weight: 900;

        letter-spacing: -1px;
    }


    .partnerships-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 12px;
    }


    .partnership-card {
        min-height: 142px;

        padding: 18px;

        border:
            1px solid rgba(255,255,255,.075);

        border-radius: 16px;

        background:
            linear-gradient(
                145deg,
                rgba(255,255,255,.035),
                rgba(255,255,255,.012)
            );

        transition:
            transform .18s ease,
            border-color .18s ease;
    }


    .partnership-card:hover {
        transform: translateY(-3px);

        border-color:
            rgba(247,37,133,.28);
    }


    .partnership-card-icon {
        width: 34px;
        height: 34px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 12px;

        border-radius: 10px;

        color: #FFFFFF;

        background:
            linear-gradient(
                135deg,
                rgba(247,37,133,.25),
                rgba(121,55,255,.22)
            );

        font-size: 15px;
    }


    .partnership-card h3 {
        margin: 0;

        color: #FFFFFF;

        font-size: 14px;
        font-weight: 850;
    }


    .partnership-card p {
        margin:
            6px
            0
            0;

        color: #928C9E;

        font-size: 10px;
        line-height: 1.5;
    }


    /* =========================================================
       WHO CAN PARTNER
    ========================================================= */

    .partnership-types {
        padding:
            0
            0
            34px;
    }


    .partnership-types-box {
        padding: 22px;

        border:
            1px solid rgba(255,255,255,.07);

        border-radius: 18px;

        background: #0D0B20;
    }


    .partnership-types-box h2 {
        margin: 0;

        color: #FFFFFF;

        font-size: 22px;
        font-weight: 900;
    }


    .partnership-types-list {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 8px;

        margin-top: 16px;
    }


    .partnership-type {
        min-height: 40px;

        display: flex;

        align-items: center;

        padding: 0 12px;

        border:
            1px solid rgba(255,255,255,.065);

        border-radius: 10px;

        color: #D7D2DF;

        background:
            rgba(255,255,255,.022);

        font-size: 10px;
        font-weight: 750;
    }


    .partnership-type::before {
        content: "✓";

        margin-right: 8px;

        color: #F72585;

        font-weight: 900;
    }


    /* =========================================================
       CTA
    ========================================================= */

    .partnerships-cta {
        padding:
            0
            0
            34px;
    }


    .partnerships-cta-box {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 25px;

        padding:
            22px
            24px;

        border:
            1px solid rgba(247,37,133,.18);

        border-radius: 17px;

        background:
            linear-gradient(
                120deg,
                rgba(247,37,133,.10),
                rgba(121,55,255,.10)
            );
    }


    .partnerships-cta-copy h2 {
        margin: 0;

        color: #FFFFFF;

        font-size: 21px;
        font-weight: 900;
    }


    .partnerships-cta-copy p {
        margin:
            5px
            0
            0;

        color: #9791A5;

        font-size: 10px;
        line-height: 1.5;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 950px) {

        .partnerships-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }


        .partnership-types-list {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    @media(max-width: 720px) {

        .partnerships-container {
            width:
                calc(100% - 28px);
        }


        .partnerships-hero {
            padding:
                28px
                0
                32px;

            background:
                linear-gradient(
                    90deg,
                    rgba(7,6,21,.98) 0%,
                    rgba(7,6,21,.90) 55%,
                    rgba(7,6,21,.48) 100%
                ),
                url('{{ asset('images/home/hero-dance.jpg') }}');

            background-size:
                auto,
                auto 82%;

            background-position:
                center,
                80% bottom;

            background-repeat:
                no-repeat,
                no-repeat;
        }


        .partnerships-hero h1 {
            font-size: 38px;

            letter-spacing: -2px;
        }


        .partnerships-hero-description {
            font-size: 12px;
        }


        .partnerships-grid {
            grid-template-columns: 1fr;
        }


        .partnership-types-list {
            grid-template-columns: 1fr;
        }


        .partnerships-cta-box {
            align-items: flex-start;

            flex-direction: column;
        }


        .partnerships-primary-btn {
            width: 100%;
        }

    }

</style>

@endpush



@section('content')

<div class="partnerships-page">


    {{-- =========================================================
       HERO
    ========================================================= --}}

    <section class="partnerships-hero">

        <div class="partnerships-container">

            <div class="partnerships-hero-content">


                <div class="partnerships-eyebrow">

                    {{ app()->getLocale() === 'fr'
                        ? 'PARTENARIATS'
                        : 'PARTNERSHIPS'
                    }}

                </div>


                <h1>

                    {{ app()->getLocale() === 'fr'
                        ? 'Développez votre marque'
                        : 'Grow Your Brand'
                    }}

                    <span class="partnerships-gradient">

                        {{ app()->getLocale() === 'fr'
                            ? 'avec DancePair.'
                            : 'With DancePair.'
                        }}

                    </span>

                </h1>


                <p class="partnerships-hero-description">

                    {{ app()->getLocale() === 'fr'
                        ? 'Faites connaître votre entreprise, votre événement, votre produit ou votre service lié à la danse directement auprès de la communauté DancePair.'
                        : 'Promote your dance-related business, event, product, or service directly to the DancePair community.'
                    }}

                </p>


                <div class="partnerships-hero-action">

                    <a
                        href="{{ route('public.contact') }}"
                        class="partnerships-primary-btn"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Nous contacter'
                            : 'Contact Us'
                        }}

                    </a>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
       ADVERTISING OPTIONS
    ========================================================= --}}

    <section class="partnerships-section">

        <div class="partnerships-container">


            <div class="partnerships-section-header">

                <span>

                    {{ app()->getLocale() === 'fr'
                        ? 'PUBLICITÉ ET COLLABORATION'
                        : 'ADVERTISING & COLLABORATION'
                    }}

                </span>


                <h2>

                    {{ app()->getLocale() === 'fr'
                        ? 'Faites connaître votre entreprise sur DancePair'
                        : 'Promote Your Business on DancePair'
                    }}

                </h2>

            </div>



            <div class="partnerships-grid">


                {{-- WEBSITE ADVERTISING --}}
                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ▣
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Publicité sur le site'
                            : 'Website Advertising'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Faites la promotion de votre entreprise, de votre produit ou de votre service lié à la danse grâce à des espaces publicitaires sur DancePair.'
                            : 'Promote your dance-related business, product, or service through advertising placements on DancePair.'
                        }}

                    </p>

                </div>



                {{-- FEATURED PROMOTION --}}
                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ★
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Mise en avant'
                            : 'Featured Promotion'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Augmentez la visibilité de votre entreprise ou de vos services grâce à des emplacements mis en avant sur la plateforme.'
                            : 'Give your business or service additional visibility through featured placements across the platform.'
                        }}

                    </p>

                </div>



                {{-- EVENT PROMOTION --}}
                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ◉
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Promotion d’événements'
                            : 'Event Promotion'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Faites connaître vos événements de danse, ateliers, compétitions, festivals et autres activités liées à la danse.'
                            : 'Promote dance events, workshops, competitions, festivals, and other dance-related activities.'
                        }}

                    </p>

                </div>



                {{-- STUDIOS & SCHOOLS --}}
                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ♫
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Studios et écoles de danse'
                            : 'Dance Studios & Schools'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Présentez votre studio, votre académie ou votre école de danse à des personnes réellement intéressées par la danse.'
                            : 'Introduce your studio, academy, or dance school to people actively interested in dance.'
                        }}

                    </p>

                </div>



                {{-- PRODUCTS & SERVICES --}}
                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ◆
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Produits et services'
                            : 'Products & Services'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Faites la promotion de vêtements de danse, chaussures, accessoires, services photo, vidéo et autres produits ou services liés à la danse.'
                            : 'Promote dancewear, shoes, accessories, photography, video services, and other businesses connected to dance.'
                        }}

                    </p>

                </div>



                {{-- BUSINESS PARTNERSHIPS --}}
                <div class="partnership-card">

                    <div class="partnership-card-icon">
                        ↗
                    </div>


                    <h3>

                        {{ app()->getLocale() === 'fr'
                            ? 'Partenariats commerciaux'
                            : 'Business Partnerships'
                        }}

                    </h3>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Collaborez avec DancePair sur des possibilités de partenariat qui rapprochent votre marque de la communauté de danse.'
                            : 'Work with DancePair on partnership opportunities that connect your brand with the dance community.'
                        }}

                    </p>

                </div>


            </div>

        </div>

    </section>



    {{-- =========================================================
       WHO CAN PARTNER
    ========================================================= --}}

    <section class="partnership-types">

        <div class="partnerships-container">

            <div class="partnership-types-box">


                <h2>

                    {{ app()->getLocale() === 'fr'
                        ? 'Entreprises liées à la danse'
                        : 'Dance-Related Businesses'
                    }}

                </h2>


                <div class="partnership-types-list">


                    <div class="partnership-type">

                        {{ app()->getLocale() === 'fr'
                            ? 'Studios de danse'
                            : 'Dance Studios'
                        }}

                    </div>


                    <div class="partnership-type">

                        {{ app()->getLocale() === 'fr'
                            ? 'Écoles de danse'
                            : 'Dance Schools'
                        }}

                    </div>


                    <div class="partnership-type">

                        {{ app()->getLocale() === 'fr'
                            ? 'Vêtements et chaussures de danse'
                            : 'Dancewear & Shoes'
                        }}

                    </div>


                    <div class="partnership-type">

                        {{ app()->getLocale() === 'fr'
                            ? 'Événements de danse'
                            : 'Dance Events'
                        }}

                    </div>


                    <div class="partnership-type">

                        {{ app()->getLocale() === 'fr'
                            ? 'Compétitions'
                            : 'Competitions'
                        }}

                    </div>


                    <div class="partnership-type">

                        {{ app()->getLocale() === 'fr'
                            ? 'Ateliers'
                            : 'Workshops'
                        }}

                    </div>


                    <div class="partnership-type">

                        {{ app()->getLocale() === 'fr'
                            ? 'Photographie de danse'
                            : 'Dance Photography'
                        }}

                    </div>


                    <div class="partnership-type">

                        {{ app()->getLocale() === 'fr'
                            ? 'Services vidéo de danse'
                            : 'Dance Video Services'
                        }}

                    </div>


                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
       CTA
    ========================================================= --}}

    <section class="partnerships-cta">

        <div class="partnerships-container">

            <div class="partnerships-cta-box">


                <div class="partnerships-cta-copy">

                    <h2>

                        {{ app()->getLocale() === 'fr'
                            ? 'Vous souhaitez collaborer avec DancePair ?'
                            : 'Interested in Working With DancePair?'
                        }}

                    </h2>


                    <p>

                        {{ app()->getLocale() === 'fr'
                            ? 'Contactez-nous pour discuter de possibilités de publicité ou de partenariat.'
                            : 'Contact us to discuss advertising or partnership opportunities.'
                        }}

                    </p>

                </div>


                <a
                    href="{{ route('public.contact') }}"
                    class="partnerships-primary-btn"
                >

                    {{ app()->getLocale() === 'fr'
                        ? 'Nous contacter'
                        : 'Contact Us'
                    }}

                </a>


            </div>

        </div>

    </section>


</div>

@endsection
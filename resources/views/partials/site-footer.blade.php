<style>

    /* =========================================================
       GLOBAL DANCEPAIR FOOTER
    ========================================================= */

    .site-footer {
        width: 100%;

        padding: 32px 0;

        border-top:
            1px solid rgba(255,255,255,.07);

        background: #04030F;

        color: #FFFFFF;
    }


    .site-footer-inner {
        width: min(
            1450px,
            calc(100% - 80px)
        );

        margin: 0 auto;

        display: grid;

        grid-template-columns:
            1fr
            auto
            1fr;

        align-items: center;

        gap: 28px;
    }


    /* =========================================================
       BRAND
    ========================================================= */

    .site-footer-brand {
        display: inline-flex;

        align-items: center;

        gap: 10px;

        color: #FFFFFF;

        text-decoration: none;
    }


    .site-footer-brand img {
        width: 48px;
        height: 48px;

        object-fit: contain;
    }


    .site-footer-brand-name {
        color: #FFFFFF;

        font-size: 18px;

        font-weight: 900;

        white-space: nowrap;
    }


    .site-footer-brand-name span {
        color: #F72585;
    }


    /* =========================================================
       LINKS
    ========================================================= */

    .site-footer-links {
        display: flex;

        align-items: center;

        justify-content: center;

        flex-wrap: wrap;

        gap: 20px;
    }


    .site-footer-links a {
        color: #8E899A;

        text-decoration: none;

        white-space: nowrap;

        font-size: 11px;

        transition:
            color .2s ease;
    }


    .site-footer-links a:hover {
        color: #FFFFFF;
    }


    .site-footer-links a.site-footer-legal {
        color: #C7A8E8;
    }


    .site-footer-links a.site-footer-legal:hover {
        color: #FFFFFF;
    }


    /* =========================================================
       COPYRIGHT
    ========================================================= */

    .site-footer-copy {
        color: #625E6D;

        font-size: 10px;

        text-align: right;

        line-height: 1.6;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 1000px) {

        .site-footer-inner {
            grid-template-columns: 1fr;

            text-align: center;

            gap: 22px;
        }


        .site-footer-brand {
            justify-content: center;
        }


        .site-footer-copy {
            text-align: center;
        }

    }


    @media(max-width: 650px) {

        .site-footer {
            padding: 28px 0;
        }


        .site-footer-inner {
            width:
                calc(100% - 28px);

            align-items: flex-start;

            text-align: left;
        }


        .site-footer-brand {
            justify-content: flex-start;
        }


        .site-footer-links {
            justify-content: flex-start;

            gap: 15px 18px;
        }


        .site-footer-copy {
            text-align: left;
        }

    }

</style>


<footer class="site-footer">

    <div class="site-footer-inner">


        {{-- BRAND --}}
        <a
            href="{{ route('home') }}"
            class="site-footer-brand"
        >

            <img
                src="{{ asset('logo/logo.png') }}"
                alt="DancePair"
            >

            <span class="site-footer-brand-name">
                Dance<span>Pair</span>
            </span>

        </a>


        {{-- LINKS --}}
        <div class="site-footer-links">

            <a href="{{ route('home') }}">
                {{ app()->getLocale() === 'fr'
                    ? 'Accueil'
                    : 'Home'
                }}
            </a>


            <a href="{{ route('public.find-teacher') }}">
                {{ app()->getLocale() === 'fr'
                    ? 'Trouver un professeur'
                    : 'Find a Teacher'
                }}
            </a>


            <a href="{{ route('public.become-teacher') }}">
                {{ app()->getLocale() === 'fr'
                    ? 'Devenir professeur'
                    : 'Become a Teacher'
                }}
            </a>


            <a href="{{ route('public.dance-styles') }}">
                {{ app()->getLocale() === 'fr'
                    ? 'Styles de danse'
                    : 'Dance Styles'
                }}
            </a>


            <a href="{{ route('public.how-it-works') }}">
                {{ app()->getLocale() === 'fr'
                    ? 'Comment ça fonctionne'
                    : 'How It Works'
                }}
            </a>


            <a href="{{ route('public.about') }}">
                {{ app()->getLocale() === 'fr'
                    ? 'À propos'
                    : 'About Us'
                }}
            </a>


            <a href="{{ route('public.contact') }}">
                {{ app()->getLocale() === 'fr'
                    ? 'Contact'
                    : 'Contact'
                }}
            </a>


            <a
                href="{{ route('public.terms') }}"
                class="site-footer-legal"
            >
                {{ __('legal.cancellation_refund.title') }}
            </a>

        </div>


        {{-- COPYRIGHT --}}
        <div class="site-footer-copy">

            © {{ date('Y') }} DancePair.

            <br>

            {{ app()->getLocale() === 'fr'
                ? 'Tous droits réservés.'
                : 'All rights reserved.'
            }}

        </div>


    </div>

</footer>
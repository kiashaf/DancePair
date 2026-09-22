@extends('public.layout')

@section('title', __('legal.cancellation_refund.title') . ' | DancePair')


@push('styles')

<style>

    /* =========================================================
       LEGAL PAGE
    ========================================================= */

    .legal-page {
        padding: 55px 0 80px;

        background: #070615;
    }


    .legal-container {
        width: min(
            1380px,
            calc(100% - 80px)
        );

        margin: 0 auto;
    }


    .legal-card {
        width: 100%;

        padding: 52px 60px;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 28px;

        background:
            linear-gradient(
                135deg,
                #100E25,
                #17102F
            );
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .legal-header {
        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 30px;

        margin-bottom: 40px;
    }


    .legal-header-copy {
        min-width: 0;
    }


    .legal-title {
        margin: 0 0 12px;

        color: #FFFFFF;

        font-size: 36px;

        font-weight: 900;

        letter-spacing: -.8px;
    }


    .legal-intro {
        margin: 0;

        color: #AAA3BE;

        font-size: 16px;

        line-height: 1.7;
    }


    /* =========================================================
       LANGUAGE SWITCH
    ========================================================= */

    .legal-language-switch {
        flex: 0 0 auto;

        display: flex;

        align-items: center;

        gap: 6px;

        padding: 6px;

        border:
            1px solid rgba(255,255,255,.10);

        border-radius: 12px;

        background:
            rgba(255,255,255,.04);
    }


    .legal-language-switch a {
        min-width: 48px;

        padding: 10px 14px;

        border-radius: 8px;

        color: #AAA3BE;

        font-size: 13px;

        font-weight: 900;

        text-align: center;

        text-decoration: none;

        transition:
            background .2s ease,
            color .2s ease;
    }


    .legal-language-switch a:hover {
        color: #FFFFFF;

        background:
            rgba(255,255,255,.06);
    }


    .legal-language-switch a.active {
        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7437FF
            );
    }


    /* =========================================================
       POLICY LIST
    ========================================================= */

    .legal-list {
        margin: 0;

        padding-left: 24px;
    }


    .legal-list li {
        margin-bottom: 22px;

        padding-left: 6px;

        color: #E8E5F0;

        font-size: 16px;

        line-height: 1.7;
    }


    .legal-list li:last-child {
        margin-bottom: 0;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .legal-container {
            width:
                calc(100% - 36px);
        }


        .legal-card {
            padding: 36px 30px;
        }


        .legal-header {
            flex-direction: column;

            gap: 22px;
        }


        .legal-title {
            font-size: 30px;
        }

    }


    @media (max-width: 600px) {

        .legal-page {
            padding: 30px 0 50px;
        }


        .legal-container {
            width:
                calc(100% - 24px);
        }


        .legal-card {
            padding: 28px 22px;

            border-radius: 20px;
        }


        .legal-title {
            font-size: 26px;
        }


        .legal-intro,
        .legal-list li {
            font-size: 15px;
        }

    }

</style>

@endpush



@section('content')

<section class="legal-page">

    <div class="legal-container">

        <div class="legal-card">


            <div class="legal-header">

                <div class="legal-header-copy">

                    <h1 class="legal-title">
                        {{ __('legal.cancellation_refund.title') }}
                    </h1>


                    <p class="legal-intro">
                        {{ __('legal.cancellation_refund.intro') }}
                    </p>

                </div>


                <div class="legal-language-switch">

                    <a
                        href="{{ route('language.switch', 'en') }}"
                        class="{{ app()->getLocale() === 'en' ? 'active' : '' }}"
                    >
                        EN
                    </a>


                    <a
                        href="{{ route('language.switch', 'fr') }}"
                        class="{{ app()->getLocale() === 'fr' ? 'active' : '' }}"
                    >
                        FR
                    </a>

                </div>

            </div>


            <ul class="legal-list">

                <li>
                    {{ __('legal.cancellation_refund.online') }}
                </li>

                <li>
                    {{ __('legal.cancellation_refund.face_to_face') }}
                </li>

                <li>
                    {{ __('legal.cancellation_refund.public_place') }}
                </li>

                <li>
                    {{ __('legal.cancellation_refund.late_cancellation') }}
                </li>

                <li>
                    {{ __('legal.cancellation_refund.teacher_cancellation') }}
                </li>

                <li>
                    {{ __('legal.cancellation_refund.admin_override') }}
                </li>

                <li>
                    {{ __('legal.cancellation_refund.original_payment_method') }}
                </li>

                <li>
                    {{ __('legal.cancellation_refund.full_refund') }}
                </li>

            </ul>

        </div>

    </div>

</section>

@endsection
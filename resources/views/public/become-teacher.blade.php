@extends('public.layout')

@section('title', 'Become a Teacher | DancePair')


@push('styles')
<style>

    .teach-hero {
        position: relative;

        min-height: 650px;

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

        padding: 9px 15px;

        margin-bottom: 22px;

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

        font-size: clamp(50px,6vw,88px);

        line-height: .96;

        font-weight: 950;

        letter-spacing: -4px;
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

        margin: 25px 0;

        color: #B5AFBF;

        font-size: 17px;
        line-height: 1.75;
    }


    .teach-hero-actions {
        display: flex;

        align-items: center;

        gap: 12px;

        margin-top: 30px;
    }


    .teach-primary {
        min-height: 50px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 0 25px;

        border-radius: 12px;

        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #7937FF
            );

        text-decoration: none;

        font-size: 12px;
        font-weight: 900;

        box-shadow:
            0 14px 30px
            rgba(247,37,133,.20);
    }


    .teach-secondary {
        min-height: 50px;

        display: inline-flex;

        align-items: center;

        padding: 0 22px;

        border:
            1px solid rgba(255,255,255,.16);

        border-radius: 12px;

        color: white;

        text-decoration: none;

        font-size: 12px;
        font-weight: 800;
    }


    /* =====================================================
       BENEFITS
    ===================================================== */

    .teach-benefits {
        padding: 90px 0;
    }


    .teach-section-head {
        max-width: 650px;

        margin-bottom: 40px;
    }


    .teach-section-head small {
        color: #F72585;

        font-size: 10px;

        font-weight: 900;

        letter-spacing: .15em;

        text-transform: uppercase;
    }


    .teach-section-head h2 {
        margin: 8px 0 12px;

        color: white;

        font-size: 40px;

        font-weight: 950;

        letter-spacing: -2px;
    }


    .teach-section-head p {
        margin: 0;

        color: #878290;

        font-size: 14px;

        line-height: 1.7;
    }


    .teach-grid {
        display: grid;

        grid-template-columns:
            repeat(4,1fr);

        gap: 16px;
    }


    .teach-card {
        min-height: 220px;

        padding: 28px;

        border:
            1px solid rgba(255,255,255,.07);

        border-radius: 20px;

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
            translateY(-6px);
    }


    .teach-number {
        width: 42px;
        height: 42px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 30px;

        border-radius: 12px;

        color: #FFFFFF;

        background:
            linear-gradient(
                135deg,
                #F72585,
                #7937FF
            );

        font-size: 12px;
        font-weight: 900;
    }


    .teach-card h3 {
        margin: 0 0 9px;

        color: white;

        font-size: 18px;
        font-weight: 900;
    }


    .teach-card p {
        margin: 0;

        color: #817B8C;

        font-size: 12px;

        line-height: 1.7;
    }


    /* =====================================================
       STEPS
    ===================================================== */

    .teach-steps-section {
        padding: 85px 0;

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

        gap: 12px;
    }


    .teach-step {
        position: relative;

        padding: 25px;

        border-radius: 18px;

        border:
            1px solid rgba(255,255,255,.07);

        background:
            rgba(255,255,255,.025);
    }


    .teach-step strong {
        display: block;

        margin-bottom: 8px;

        color: #FFFFFF;

        font-size: 14px;
    }


    .teach-step span {
        color: #797485;

        font-size: 11px;

        line-height: 1.6;
    }


    /* =====================================================
       FINAL CTA
    ===================================================== */

    .teach-final {
        padding: 90px 0;
    }


    .teach-final-box {
        position: relative;

        overflow: hidden;

        padding: 70px;

        border-radius: 26px;

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

        font-size: 45px;

        font-weight: 950;

        letter-spacing: -2px;
    }


    .teach-final-box p {
        max-width: 550px;

        margin: 15px auto 28px;

        color: #9A95A5;

        line-height: 1.7;

        font-size: 14px;
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
            min-height: 600px;

            background-size: auto 65%;
            background-position: 80% bottom;
        }

        .teach-copy h1 {
            letter-spacing: -2px;
        }

        .teach-grid,
        .teach-steps {
            grid-template-columns: 1fr;
        }

        .teach-final-box {
            padding: 40px 22px;
        }

        .teach-final-box h2 {
            font-size: 35px;
        }

    }

</style>
@endpush



@section('content')


<section class="teach-hero">

    <div class="teach-container">

        <div class="teach-copy">

            <div class="teach-kicker">
                TEACH • INSPIRE • GROW
            </div>

            <h1>
                Your Dance Skills
                <span>Deserve an Audience.</span>
            </h1>

            <p>
                Turn your experience into opportunity.
                Build your professional DancePair profile,
                reach new students and teach on your own terms.
            </p>


            <div class="teach-hero-actions">

                @guest

                    <a
                        href="{{ route('register') }}"
                        class="teach-primary"
                    >
                        Become a Teacher
                    </a>

                @else

                    @if(auth()->user()->role === 'teacher')

                        <a
                            href="{{ route('teacher.dashboard') }}"
                            class="teach-primary"
                        >
                            Teacher Dashboard
                        </a>

                    @endif

                @endguest


                <a
                    href="#how-teaching-works"
                    class="teach-secondary"
                >
                    See How It Works
                </a>

            </div>

        </div>

    </div>

</section>



<section class="teach-benefits">

    <div class="teach-container">


        <div class="teach-section-head">

            <small>
                Why DancePair
            </small>

            <h2>
                Build Your Teaching Business
            </h2>

            <p>
                DancePair gives independent dance teachers
                a professional place to showcase their skills,
                manage their availability and connect with students.
            </p>

        </div>


        <div class="teach-grid">


            <article class="teach-card">

                <div class="teach-number">
                    01
                </div>

                <h3>
                    Set Your Own Rates
                </h3>

                <p>
                    Decide what your lessons are worth
                    and set rates for the dance styles
                    you teach.
                </p>

            </article>


            <article class="teach-card">

                <div class="teach-number">
                    02
                </div>

                <h3>
                    Control Your Schedule
                </h3>

                <p>
                    Choose the dates and times you want
                    to teach without giving up control
                    of your calendar.
                </p>

            </article>


            <article class="teach-card">

                <div class="teach-number">
                    03
                </div>

                <h3>
                    Reach New Students
                </h3>

                <p>
                    Get discovered by students actively
                    searching for dance teachers
                    in their area.
                </p>

            </article>


            <article class="teach-card">

                <div class="teach-number">
                    04
                </div>

                <h3>
                    Build Your Reputation
                </h3>

                <p>
                    Grow your profile through real student
                    reviews, experience and successful lessons.
                </p>

            </article>


        </div>

    </div>

</section>



<section
    class="teach-steps-section"
    id="how-teaching-works"
>

    <div class="teach-container">


        <div class="teach-section-head">

            <small>
                Getting Started
            </small>

            <h2>
                Start Teaching in a Few Steps
            </h2>

        </div>


        <div class="teach-steps">


            <div class="teach-step">

                <strong>
                    01. Create Account
                </strong>

                <span>
                    Join DancePair as a teacher.
                </span>

            </div>


            <div class="teach-step">

                <strong>
                    02. Build Profile
                </strong>

                <span>
                    Add your photo, bio and experience.
                </span>

            </div>


            <div class="teach-step">

                <strong>
                    03. Add Styles
                </strong>

                <span>
                    Choose what you teach and set rates.
                </span>

            </div>


            <div class="teach-step">

                <strong>
                    04. Set Availability
                </strong>

                <span>
                    Tell students when you're available.
                </span>

            </div>


            <div class="teach-step">

                <strong>
                    05. Start Teaching
                </strong>

                <span>
                    Receive requests and grow your profile.
                </span>

            </div>


        </div>

    </div>

</section>



<section class="teach-final">

    <div class="teach-container">

        <div class="teach-final-box">

            <h2>
                Ready to Share Your Passion?
            </h2>

            <p>
                Create your teacher profile and start
                connecting with dancers looking for
                exactly what you teach.
            </p>


            @guest

                <a
                    href="{{ route('register') }}"
                    class="teach-primary"
                >
                    Join DancePair as a Teacher
                </a>

            @else

                @if(auth()->user()->role === 'teacher')

                    <a
                        href="{{ route('teacher.dashboard') }}"
                        class="teach-primary"
                    >
                        Go to Teacher Dashboard
                    </a>

                @endif

            @endguest

        </div>

    </div>

</section>

@endsection
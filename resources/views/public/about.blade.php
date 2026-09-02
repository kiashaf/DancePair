@extends('public.layout')

@section('title', 'About Us | DancePair')


@push('styles')
<style>

    /* =========================================================
       HERO
    ========================================================= */

    .about-hero {
        position: relative;
        min-height: 640px;

        display: flex;
        align-items: center;

        overflow: hidden;

        background:
            linear-gradient(
                90deg,
                rgba(6,5,20,.98) 0%,
                rgba(6,5,20,.90) 43%,
                rgba(6,5,20,.36) 100%
            ),
            url('{{ asset('images/home/hero-dance.jpg') }}');

        background-size: auto 95%;
        background-position: right bottom;
        background-repeat: no-repeat;
    }


    .about-hero::before {
        content: "";

        position: absolute;
        inset: 0;

        background:
            radial-gradient(
                circle at 25% 38%,
                rgba(247,37,133,.17),
                transparent 30%
            ),
            radial-gradient(
                circle at 75% 48%,
                rgba(121,55,255,.18),
                transparent 34%
            );
    }


    .about-container {
        position: relative;
        z-index: 2;

        width: min(1450px, calc(100% - 80px));
        margin: 0 auto;
    }


    .about-copy {
        max-width: 730px;
    }


    .about-kicker {
        display: inline-flex;
        align-items: center;

        padding: 9px 15px;
        margin-bottom: 22px;

        border: 1px solid rgba(247,37,133,.30);
        border-radius: 999px;

        color: #FF86BC;
        background: rgba(247,37,133,.08);

        font-size: 10px;
        font-weight: 900;

        letter-spacing: .15em;
    }


    .about-copy h1 {
        margin: 0;

        color: #FFFFFF;

        font-size: clamp(50px, 6vw, 86px);
        line-height: .96;

        font-weight: 950;

        letter-spacing: -4px;
    }


    .about-copy h1 span {
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


    .about-copy p {
        max-width: 610px;

        margin: 25px 0 0;

        color: #B6B0C0;

        font-size: 17px;
        line-height: 1.75;
    }


    /* =========================================================
       STORY
    ========================================================= */

    .about-story {
        padding: 95px 0;
    }


    .about-story-grid {
        display: grid;

        grid-template-columns:
            .95fr
            1.05fr;

        gap: 80px;

        align-items: center;
    }


    .about-section-label {
        color: #F72585;

        font-size: 10px;
        font-weight: 900;

        letter-spacing: .15em;
        text-transform: uppercase;
    }


    .about-story-copy h2 {
        margin: 9px 0 18px;

        color: #FFFFFF;

        font-size: 45px;
        line-height: 1.06;

        font-weight: 950;

        letter-spacing: -2px;
    }


    .about-story-copy p {
        margin: 0 0 15px;

        color: #8A8495;

        font-size: 14px;
        line-height: 1.8;
    }


    .about-story-panel {
        position: relative;

        overflow: hidden;

        min-height: 430px;

        padding: 45px;

        display: flex;
        align-items: flex-end;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 26px;

        background:
            radial-gradient(
                circle at 75% 20%,
                rgba(121,55,255,.28),
                transparent 35%
            ),
            radial-gradient(
                circle at 20% 90%,
                rgba(247,37,133,.18),
                transparent 36%
            ),
            linear-gradient(
                145deg,
                #120F2B,
                #090817
            );
    }


    .about-story-panel::before {
        content: "";

        position: absolute;

        width: 280px;
        height: 280px;

        right: -70px;
        top: -70px;

        border-radius: 50%;

        border:
            1px solid rgba(255,255,255,.06);
    }


    .about-story-panel::after {
        content: "";

        position: absolute;

        width: 190px;
        height: 190px;

        right: -25px;
        top: -25px;

        border-radius: 50%;

        border:
            1px solid rgba(255,255,255,.08);
    }


    .about-quote {
        position: relative;
        z-index: 2;

        max-width: 500px;
    }


    .about-quote-mark {
        margin-bottom: 15px;

        color: #F72585;

        font-size: 48px;
        font-weight: 950;
        line-height: 1;
    }


    .about-quote p {
        margin: 0;

        color: #FFFFFF;

        font-size: 27px;
        line-height: 1.35;

        font-weight: 850;

        letter-spacing: -1px;
    }


    /* =========================================================
       MISSION
    ========================================================= */

    .about-mission {
        padding: 90px 0;

        background:
            radial-gradient(
                circle at 75% 40%,
                rgba(121,55,255,.12),
                transparent 34%
            ),
            #090817;
    }


    .about-heading {
        max-width: 680px;

        margin-bottom: 38px;
    }


    .about-heading h2 {
        margin: 8px 0 12px;

        color: #FFFFFF;

        font-size: 41px;
        font-weight: 950;

        letter-spacing: -2px;
    }


    .about-heading p {
        margin: 0;

        color: #898394;

        font-size: 14px;
        line-height: 1.75;
    }


    .about-values-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0,1fr));

        gap: 16px;
    }


    .about-value-card {
        min-height: 245px;

        padding: 28px;

        border:
            1px solid rgba(255,255,255,.07);

        border-radius: 20px;

        background:
            linear-gradient(
                145deg,
                #100E24,
                #090817
            );

        transition:
            transform .25s ease,
            border-color .25s ease;
    }


    .about-value-card:hover {
        transform: translateY(-5px);

        border-color:
            rgba(247,37,133,.25);
    }


    .about-value-icon {
        width: 46px;
        height: 46px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 34px;

        border-radius: 12px;

        color: #FFFFFF;

        background:
            linear-gradient(
                135deg,
                #F72585,
                #7937FF
            );

        font-size: 15px;
        font-weight: 900;
    }


    .about-value-card h3 {
        margin: 0 0 9px;

        color: #FFFFFF;

        font-size: 17px;
        font-weight: 900;
    }


    .about-value-card p {
        margin: 0;

        color: #7F7989;

        font-size: 11px;
        line-height: 1.75;
    }


    /* =========================================================
       FOR STUDENTS + TEACHERS
    ========================================================= */

    .about-community {
        padding: 95px 0;
    }


    .about-community-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0,1fr));

        gap: 18px;
    }


    .about-community-card {
        position: relative;

        overflow: hidden;

        min-height: 340px;

        padding: 38px;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 25px;

        background:
            linear-gradient(
                145deg,
                #110E27,
                #090817
            );
    }


    .about-community-card::before {
        content: "";

        position: absolute;

        width: 270px;
        height: 270px;

        right: -80px;
        top: -80px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(121,55,255,.26),
                transparent 70%
            );
    }


    .about-community-card.student::before {
        background:
            radial-gradient(
                circle,
                rgba(247,37,133,.24),
                transparent 70%
            );
    }


    .about-community-type {
        position: relative;
        z-index: 2;

        display: inline-flex;

        padding: 7px 11px;

        margin-bottom: 65px;

        border-radius: 999px;

        color: #D7D1DE;

        background:
            rgba(255,255,255,.06);

        font-size: 9px;
        font-weight: 850;

        letter-spacing: .08em;

        text-transform: uppercase;
    }


    .about-community-card h3 {
        position: relative;
        z-index: 2;

        margin: 0 0 10px;

        color: #FFFFFF;

        font-size: 29px;
        font-weight: 950;

        letter-spacing: -1px;
    }


    .about-community-card p {
        position: relative;
        z-index: 2;

        max-width: 470px;

        margin: 0 0 22px;

        color: #898394;

        font-size: 13px;
        line-height: 1.75;
    }


    .about-community-card a {
        position: relative;
        z-index: 2;

        color: #F72585;

        text-decoration: none;

        font-size: 11px;
        font-weight: 900;
    }


    /* =========================================================
       PROMISE
    ========================================================= */

    .about-promise {
        padding: 90px 0;

        background:
            radial-gradient(
                circle at 20% 50%,
                rgba(247,37,133,.10),
                transparent 30%
            ),
            #090817;
    }


    .about-promise-layout {
        display: grid;

        grid-template-columns:
            .8fr
            1.2fr;

        gap: 70px;

        align-items: center;
    }


    .about-promise-copy h2 {
        margin: 8px 0 14px;

        color: #FFFFFF;

        font-size: 42px;
        line-height: 1.08;

        font-weight: 950;

        letter-spacing: -2px;
    }


    .about-promise-copy p {
        margin: 0;

        color: #898394;

        font-size: 14px;
        line-height: 1.75;
    }


    .about-promise-list {
        display: grid;

        gap: 12px;
    }


    .about-promise-item {
        display: grid;

        grid-template-columns:
            45px
            1fr;

        gap: 14px;

        align-items: center;

        padding: 17px;

        border:
            1px solid rgba(255,255,255,.07);

        border-radius: 15px;

        background:
            rgba(255,255,255,.025);
    }


    .about-promise-check {
        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        color: #FFFFFF;

        background:
            rgba(247,37,133,.15);

        font-size: 13px;
        font-weight: 900;
    }


    .about-promise-item strong {
        display: block;

        margin-bottom: 3px;

        color: #FFFFFF;

        font-size: 13px;
    }


    .about-promise-item span {
        color: #777181;

        font-size: 10px;
        line-height: 1.5;
    }


    /* =========================================================
       FINAL CTA
    ========================================================= */

    .about-final {
        padding: 90px 0;
    }


    .about-final-box {
        position: relative;

        overflow: hidden;

        padding: 68px;

        text-align: center;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 27px;

        background:
            radial-gradient(
                circle at 20% 100%,
                rgba(247,37,133,.23),
                transparent 37%
            ),
            radial-gradient(
                circle at 80% 0%,
                rgba(121,55,255,.28),
                transparent 37%
            ),
            #100D25;
    }


    .about-final-box small {
        color: #F72585;

        font-size: 10px;
        font-weight: 900;

        letter-spacing: .15em;

        text-transform: uppercase;
    }


    .about-final-box h2 {
        margin: 10px 0 13px;

        color: #FFFFFF;

        font-size: 44px;
        font-weight: 950;

        letter-spacing: -2px;
    }


    .about-final-box p {
        max-width: 590px;

        margin: 0 auto 28px;

        color: #938D9D;

        font-size: 14px;
        line-height: 1.75;
    }


    .about-final-actions {
        display: flex;

        justify-content: center;

        gap: 11px;
    }


    .about-primary-btn,
    .about-secondary-btn {
        min-height: 50px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 0 24px;

        border-radius: 12px;

        color: #FFFFFF;

        text-decoration: none;

        font-size: 12px;
        font-weight: 900;
    }


    .about-primary-btn {
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


    .about-secondary-btn {
        border:
            1px solid rgba(255,255,255,.16);

        background:
            rgba(255,255,255,.025);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width:1050px) {

        .about-story-grid,
        .about-promise-layout {
            grid-template-columns: 1fr;
        }

        .about-values-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

    }


    @media(max-width:800px) {

        .about-community-grid {
            grid-template-columns: 1fr;
        }

    }


    @media(max-width:650px) {

        .about-container {
            width:
                calc(100% - 28px);
        }

        .about-hero {
            min-height: 590px;

            background-size: auto 68%;
            background-position: 80% bottom;
        }

        .about-copy h1 {
            letter-spacing: -2px;
        }

        .about-values-grid {
            grid-template-columns: 1fr;
        }

        .about-story-panel {
            min-height: 350px;
            padding: 30px;
        }

        .about-quote p {
            font-size: 22px;
        }

        .about-final-box {
            padding: 40px 22px;
        }

        .about-final-box h2 {
            font-size: 34px;
        }

        .about-final-actions {
            align-items: stretch;
            flex-direction: column;
        }

    }

</style>
@endpush


@section('content')


{{-- =========================================================
    HERO
========================================================= --}}

<section class="about-hero">

    <div class="about-container">

        <div class="about-copy">

            <div class="about-kicker">
                ABOUT DANCEPAIR
            </div>

            <h1>
                Dance Is Personal.
                <span>Your Journey Should Be Too.</span>
            </h1>

            <p>
                DancePair was created to make finding and teaching
                dance feel easier, more personal and more connected.
                One place where students and teachers can find
                the right match.
            </p>

        </div>

    </div>

</section>



{{-- =========================================================
    OUR STORY
========================================================= --}}

<section class="about-story">

    <div class="about-container">

        <div class="about-story-grid">


            <div class="about-story-copy">

                <div class="about-section-label">
                    Why DancePair Exists
                </div>

                <h2>
                    Finding the Right Dance Teacher Shouldn't Feel Difficult.
                </h2>

                <p>
                    Learning dance is deeply personal.
                    The style you love, the way you learn,
                    your schedule, your goals and even the personality
                    of your teacher can completely change the experience.
                </p>

                <p>
                    DancePair brings those pieces together.
                    Instead of searching everywhere, students can discover
                    teachers, explore their experience and styles,
                    compare rates and reviews, and choose someone
                    who actually feels right for them.
                </p>

                <p>
                    At the same time, independent teachers get a place
                    to present their skills professionally and connect
                    with people actively looking to learn.
                </p>

            </div>



            <div class="about-story-panel">

                <div class="about-quote">

                    <div class="about-quote-mark">
                        “
                    </div>

                    <p>
                        The right teacher can turn a first step
                        into a lifelong passion.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
    MISSION
========================================================= --}}

<section class="about-mission">

    <div class="about-container">

        <div class="about-heading">

            <div class="about-section-label">
                Our Mission
            </div>

            <h2>
                Make Dance More Accessible, Personal and Connected.
            </h2>

            <p>
                DancePair is built around a simple idea:
                give students better ways to discover teachers
                and give teachers better ways to be discovered.
            </p>

        </div>


        <div class="about-values-grid">


            <article class="about-value-card">

                <div class="about-value-icon">
                    01
                </div>

                <h3>
                    Better Discovery
                </h3>

                <p>
                    Help students find teachers based on
                    the information that actually matters:
                    style, experience, location, availability
                    and real feedback.
                </p>

            </article>


            <article class="about-value-card">

                <div class="about-value-icon">
                    02
                </div>

                <h3>
                    More Choice
                </h3>

                <p>
                    Give dancers the freedom to explore
                    different teachers and styles instead
                    of limiting them to one traditional path.
                </p>

            </article>


            <article class="about-value-card">

                <div class="about-value-icon">
                    03
                </div>

                <h3>
                    Teacher Independence
                </h3>

                <p>
                    Give teachers a professional space to
                    showcase their skills, set their rates
                    and choose when they want to teach.
                </p>

            </article>


            <article class="about-value-card">

                <div class="about-value-icon">
                    04
                </div>

                <h3>
                    Stronger Connections
                </h3>

                <p>
                    Create better matches between people
                    who want to learn and people passionate
                    about sharing what they know.
                </p>

            </article>


        </div>

    </div>

</section>



{{-- =========================================================
    STUDENTS + TEACHERS
========================================================= --}}

<section class="about-community">

    <div class="about-container">

        <div class="about-heading">

            <div class="about-section-label">
                Built for the Dance Community
            </div>

            <h2>
                Different Goals. One Place to Connect.
            </h2>

            <p>
                DancePair is designed around both sides
                of the learning experience.
            </p>

        </div>


        <div class="about-community-grid">


            <article class="about-community-card student">

                <div class="about-community-type">
                    For Students
                </div>

                <h3>
                    Find Someone Who Gets Your Goals.
                </h3>

                <p>
                    Whether you're learning your first dance,
                    preparing for a wedding, improving your technique
                    or simply looking for something fun,
                    DancePair helps you find the right person to learn from.
                </p>

                <a href="{{ route('public.find-teacher') }}">
                    Find Your Teacher →
                </a>

            </article>



            <article class="about-community-card">

                <div class="about-community-type">
                    For Teachers
                </div>

                <h3>
                    Let Your Experience Find New Students.
                </h3>

                <p>
                    Build a professional profile around what you teach,
                    show your experience, set your rates and availability,
                    and become discoverable by students looking
                    for your exact skills.
                </p>

                <a href="{{ route('public.become-teacher') }}">
                    Become a Teacher →
                </a>

            </article>


        </div>

    </div>

</section>



{{-- =========================================================
    OUR PROMISE
========================================================= --}}

<section class="about-promise">

    <div class="about-container">

        <div class="about-promise-layout">


            <div class="about-promise-copy">

                <div class="about-section-label">
                    What We're Building
                </div>

                <h2>
                    A Better Way to Find and Teach Dance.
                </h2>

                <p>
                    Every part of DancePair is being designed
                    to make the relationship between students
                    and teachers clearer, simpler and more useful.
                </p>

            </div>



            <div class="about-promise-list">


                <div class="about-promise-item">

                    <div class="about-promise-check">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Clear Teacher Profiles
                        </strong>

                        <span>
                            Relevant information before a student chooses.
                        </span>

                    </div>

                </div>


                <div class="about-promise-item">

                    <div class="about-promise-check">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Real Student Feedback
                        </strong>

                        <span>
                            Reviews help future dancers make better decisions.
                        </span>

                    </div>

                </div>


                <div class="about-promise-item">

                    <div class="about-promise-check">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Flexible Teaching
                        </strong>

                        <span>
                            Teachers control their styles, rates and availability.
                        </span>

                    </div>

                </div>


                <div class="about-promise-item">

                    <div class="about-promise-check">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Simple Connections
                        </strong>

                        <span>
                            Search, discovery and booking live in one experience.
                        </span>

                    </div>

                </div>


            </div>

        </div>

    </div>

</section>



{{-- =========================================================
    FINAL CTA
========================================================= --}}

<section class="about-final">

    <div class="about-container">

        <div class="about-final-box">

            <small>
                Join the Movement
            </small>

            <h2>
                Wherever You Are in Your Dance Journey, Start Here.
            </h2>

            <p>
                Discover someone who can help you grow,
                or bring your own experience to DancePair
                and help someone else discover what dance can become.
            </p>


            <div class="about-final-actions">

                <a
                    href="{{ route('public.find-teacher') }}"
                    class="about-primary-btn"
                >
                    Find a Teacher
                </a>


                <a
                    href="{{ route('public.become-teacher') }}"
                    class="about-secondary-btn"
                >
                    Become a Teacher
                </a>

            </div>

        </div>

    </div>

</section>

@endsection
@extends('public.layout')

@section('title', 'Contact Us | DancePair')


@push('styles')

<style>

    /* =========================================================
       CONTACT PAGE
    ========================================================= */

    .contact-page {
        padding: 55px 0 70px;

        background: #070615;
    }


    .contact-container {
        width: min(
            1050px,
            calc(100% - 40px)
        );

        margin: 0 auto;
    }


    /* =========================================================
       FORM CARD
    ========================================================= */

    .contact-form-card {
        padding: 34px;

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


    .contact-form-card h1 {
        margin: 0;

        color: #FFFFFF;

        font-size: 30px;
        font-weight: 900;

        letter-spacing: -.7px;
    }


    .contact-form-subtitle {
        margin: 7px 0 0;

        color: #918BA5;

        font-size: 14px;
        line-height: 1.6;
    }


    /* =========================================================
       FORM
    ========================================================= */

    .contact-form {
        margin-top: 36px;
    }


    .contact-form-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 20px;
    }


    .contact-field {
        min-width: 0;
    }


    .contact-field-full {
        grid-column: 1 / -1;
    }


    .contact-field label {
        display: block;

        margin-bottom: 9px;

        color: #AAA3BE;

        font-size: 13px;
        font-weight: 800;
    }


    .contact-field input,
    .contact-field select,
    .contact-field textarea {
        width: 100%;

        border:
            1px solid rgba(255,255,255,.10);

        outline: none;

        border-radius: 15px;

        color: #FFFFFF;

        background: #070615;

        font-size: 14px;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }


    .contact-field input,
    .contact-field select {
        height: 62px;

        padding: 0 18px;
    }


    .contact-field textarea {
        min-height: 195px;

        padding: 18px;

        resize: vertical;

        line-height: 1.6;
    }


    .contact-field input::placeholder,
    .contact-field textarea::placeholder {
        color: #7E788F;
    }


    .contact-field select {
        cursor: pointer;
    }


    .contact-field select option {
        color: #FFFFFF;

        background: #070615;
    }


    .contact-field input:focus,
    .contact-field select:focus,
    .contact-field textarea:focus {
        border-color: #F72585;

        box-shadow:
            0 0 0 3px rgba(247,37,133,.09);
    }


    /* =========================================================
       FORM FOOTER
    ========================================================= */

    .contact-form-footer {
        margin-top: 28px;

        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }


    .contact-form-note {
        margin: 0;

        color: #777184;

        font-size: 11px;
        line-height: 1.5;
    }


    .contact-submit-btn {
        min-width: 170px;
        height: 48px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 0 25px;

        border: 0;
        border-radius: 12px;

        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #8338EC
            );

        box-shadow:
            0 10px 25px rgba(247,37,133,.18);

        font-size: 13px;
        font-weight: 850;

        cursor: pointer;

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }


    .contact-submit-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 14px 30px rgba(247,37,133,.25);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 720px) {

        .contact-page {
            padding:
                30px
                0
                50px;
        }


        .contact-container {
            width:
                calc(100% - 28px);
        }


        .contact-form-card {
            padding: 24px 18px;

            border-radius: 20px;
        }


        .contact-form-card h1 {
            font-size: 25px;
        }


        .contact-form-grid {
            grid-template-columns: 1fr;
        }


        .contact-field-full {
            grid-column: auto;
        }


        .contact-form-footer {
            align-items: stretch;

            flex-direction: column;
        }


        .contact-submit-btn {
            width: 100%;
        }

    }

</style>

@endpush



@section('content')

<section class="contact-page">

    <div class="contact-container">

        <div class="contact-form-card">


            <h1>
                Send a Message
            </h1>


            <p class="contact-form-subtitle">
                Fill out the form below and tell us how we can help.
            </p>



            <form
                method="POST"
                action="#"
                class="contact-form"
            >

                @csrf


                <div class="contact-form-grid">


                    {{-- FIRST NAME --}}
                    <div class="contact-field">

                        <label for="first_name">
                            First Name
                        </label>

                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            placeholder="Your first name"
                            required
                        >

                    </div>



                    {{-- LAST NAME --}}
                    <div class="contact-field">

                        <label for="last_name">
                            Last Name
                        </label>

                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            placeholder="Your last name"
                            required
                        >

                    </div>



                    {{-- EMAIL --}}
                    <div class="contact-field">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email ?? '') }}"
                            placeholder="you@example.com"
                            required
                        >

                    </div>



                    {{-- TOPIC --}}
                    <div class="contact-field">

                        <label for="topic">
                            Topic
                        </label>

                        <select
                            id="topic"
                            name="topic"
                            required
                        >

                            <option
                                value=""
                                disabled
                                {{ old('topic') ? '' : 'selected' }}
                            >
                                Choose a topic
                            </option>

                            <option
                                value="account"
                                {{ old('topic') === 'account' ? 'selected' : '' }}
                            >
                                Account
                            </option>

                            <option
                                value="teacher"
                                {{ old('topic') === 'teacher' ? 'selected' : '' }}
                            >
                                Teacher
                            </option>

                            <option
                                value="booking"
                                {{ old('topic') === 'booking' ? 'selected' : '' }}
                            >
                                Booking
                            </option>

                            <option
                                value="payment"
                                {{ old('topic') === 'payment' ? 'selected' : '' }}
                            >
                                Payment
                            </option>

                            <option
                                value="technical"
                                {{ old('topic') === 'technical' ? 'selected' : '' }}
                            >
                                Technical Issue
                            </option>

                            <option
                                value="other"
                                {{ old('topic') === 'other' ? 'selected' : '' }}
                            >
                                Other
                            </option>

                        </select>

                    </div>



                    {{-- SUBJECT --}}
                    <div class="contact-field contact-field-full">

                        <label for="subject">
                            Subject
                        </label>

                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            value="{{ old('subject') }}"
                            placeholder="What can we help you with?"
                            required
                        >

                    </div>



                    {{-- MESSAGE --}}
                    <div class="contact-field contact-field-full">

                        <label for="message">
                            Message
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            placeholder="Tell us more about your question..."
                            required
                        >{{ old('message') }}</textarea>

                    </div>


                </div>



                <div class="contact-form-footer">

                    <p class="contact-form-note">
                        Please don't include passwords or sensitive payment information.
                    </p>


                    <button
                        type="submit"
                        class="contact-submit-btn"
                    >
                        Send Message
                    </button>

                </div>


            </form>


        </div>

    </div>

</section>

@endsection
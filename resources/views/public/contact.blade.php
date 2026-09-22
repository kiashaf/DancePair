@extends('public.layout')

@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Nous contacter | DancePair'
        : 'Contact Us | DancePair'
)


@push('styles')

<style>

    /* =========================================================
       CONTACT PAGE
    ========================================================= */

    .contact-page {
        padding: 32px 0 36px;

        background:
            radial-gradient(
                circle at 80% 10%,
                rgba(121,55,255,.08),
                transparent 30%
            ),
            radial-gradient(
                circle at 15% 30%,
                rgba(247,37,133,.06),
                transparent 26%
            ),
            #070615;
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
        padding: 24px 26px;

        border:
            1px solid rgba(255,255,255,.08);

        border-radius: 22px;

        background:
            linear-gradient(
                135deg,
                #100E25,
                #17102F
            );

        box-shadow:
            0 20px 60px rgba(0,0,0,.18);
    }


    .contact-form-card h1 {
        margin: 0;

        color: #FFFFFF;

        font-size: 27px;
        font-weight: 900;

        letter-spacing: -.7px;
    }


    .contact-form-subtitle {
        margin: 5px 0 0;

        color: #918BA5;

        font-size: 12px;
        line-height: 1.55;
    }


    /* =========================================================
       SUCCESS MESSAGE
    ========================================================= */

    .contact-success {
        margin-top: 16px;

        padding: 12px 14px;

        border:
            1px solid rgba(34,197,94,.28);

        border-radius: 12px;

        color: #DDFCE7;

        background:
            rgba(34,197,94,.10);

        font-size: 12px;
        font-weight: 700;

        line-height: 1.5;
    }


    /* =========================================================
       FORM
    ========================================================= */

    .contact-form {
        margin-top: 22px;
    }


    .contact-form-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 14px;
    }


    .contact-field {
        min-width: 0;
    }


    .contact-field-full {
        grid-column: 1 / -1;
    }


    .contact-field label {
        display: block;

        margin-bottom: 6px;

        color: #AAA3BE;

        font-size: 11px;
        font-weight: 800;
    }


    .contact-field input,
    .contact-field select,
    .contact-field textarea {
        width: 100%;

        border:
            1px solid rgba(255,255,255,.10);

        outline: none;

        border-radius: 11px;

        color: #FFFFFF;

        background: #070615;

        font-size: 12px;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }


    .contact-field input,
    .contact-field select {
        height: 48px;

        padding: 0 14px;
    }


    .contact-field textarea {
        min-height: 130px;

        padding: 14px;

        resize: vertical;

        line-height: 1.55;
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
        margin-top: 18px;

        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }


    .contact-form-note {
        margin: 0;

        color: #777184;

        font-size: 10px;
        line-height: 1.5;
    }


    .contact-submit-btn {
        min-width: 150px;
        height: 42px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 0 20px;

        border: 0;

        border-radius: 10px;

        color: #FFFFFF;

        background:
            linear-gradient(
                90deg,
                #F72585,
                #8338EC
            );

        box-shadow:
            0 10px 25px
            rgba(247,37,133,.18);

        font-size: 11px;
        font-weight: 850;

        cursor: pointer;

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }


    .contact-submit-btn:hover {
        transform:
            translateY(-2px);

        box-shadow:
            0 14px 30px
            rgba(247,37,133,.25);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 720px) {

        .contact-page {
            padding:
                22px
                0
                30px;
        }


        .contact-container {
            width:
                calc(100% - 28px);
        }


        .contact-form-card {
            padding:
                20px 16px;

            border-radius: 18px;
        }


        .contact-form-card h1 {
            font-size: 24px;
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

                {{ app()->getLocale() === 'fr'
                    ? 'Envoyez-nous un message'
                    : 'Send Us a Message'
                }}

            </h1>


            <p class="contact-form-subtitle">

                {{ app()->getLocale() === 'fr'
                    ? 'Remplissez le formulaire ci-dessous et dites-nous comment nous pouvons vous aider.'
                    : 'Fill out the form below and let us know how we can help.'
                }}

            </p>



            @if (session('success'))

                <div
                    class="contact-success"
                    role="alert"
                >

                    {{ app()->getLocale() === 'fr'
                        ? 'Merci ! Votre message a bien été envoyé. Nous vous répondrons dès que possible.'
                        : 'Thank you! Your message has been sent successfully. We’ll get back to you as soon as possible.'
                    }}

                </div>

            @endif



            <form
                method="POST"
                action="{{ route('public.contact.send') }}"
                class="contact-form"
            >

                @csrf


                <div class="contact-form-grid">


                    {{-- =====================================================
                       FIRST NAME
                    ===================================================== --}}

                    <div class="contact-field">

                        <label for="first_name">

                            {{ app()->getLocale() === 'fr'
                                ? 'Prénom'
                                : 'First Name'
                            }}

                        </label>


                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            placeholder="{{ app()->getLocale() === 'fr'
                                ? 'Votre prénom'
                                : 'Your first name'
                            }}"
                            required
                        >

                    </div>



                    {{-- =====================================================
                       LAST NAME
                    ===================================================== --}}

                    <div class="contact-field">

                        <label for="last_name">

                            {{ app()->getLocale() === 'fr'
                                ? 'Nom'
                                : 'Last Name'
                            }}

                        </label>


                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            placeholder="{{ app()->getLocale() === 'fr'
                                ? 'Votre nom'
                                : 'Your last name'
                            }}"
                            required
                        >

                    </div>



                    {{-- =====================================================
                       EMAIL
                    ===================================================== --}}

                    <div class="contact-field">

                        <label for="email">
                            Email
                        </label>


                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old(
                                'email',
                                auth()->user()->email ?? ''
                            ) }}"
                            placeholder="you@example.com"
                            required
                        >

                    </div>



                    {{-- =====================================================
                       TOPIC
                    ===================================================== --}}

                    <div class="contact-field">

                        <label for="topic">

                            {{ app()->getLocale() === 'fr'
                                ? 'Sujet de la demande'
                                : 'Topic'
                            }}

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

                                {{ app()->getLocale() === 'fr'
                                    ? 'Choisissez un sujet'
                                    : 'Choose a topic'
                                }}

                            </option>


                            <option
                                value="account"
                                {{ old('topic') === 'account'
                                    ? 'selected'
                                    : ''
                                }}
                            >

                                {{ app()->getLocale() === 'fr'
                                    ? 'Compte'
                                    : 'Account'
                                }}

                            </option>


                            <option
                                value="teacher"
                                {{ old('topic') === 'teacher'
                                    ? 'selected'
                                    : ''
                                }}
                            >

                                {{ app()->getLocale() === 'fr'
                                    ? 'Professeur'
                                    : 'Teacher'
                                }}

                            </option>


                            <option
                                value="booking"
                                {{ old('topic') === 'booking'
                                    ? 'selected'
                                    : ''
                                }}
                            >

                                {{ app()->getLocale() === 'fr'
                                    ? 'Réservation'
                                    : 'Booking'
                                }}

                            </option>


                            <option
                                value="payment"
                                {{ old('topic') === 'payment'
                                    ? 'selected'
                                    : ''
                                }}
                            >

                                {{ app()->getLocale() === 'fr'
                                    ? 'Paiement'
                                    : 'Payment'
                                }}

                            </option>


                            <option
                                value="technical"
                                {{ old('topic') === 'technical'
                                    ? 'selected'
                                    : ''
                                }}
                            >

                                {{ app()->getLocale() === 'fr'
                                    ? 'Problème technique'
                                    : 'Technical Issue'
                                }}

                            </option>


                            <option
                                value="other"
                                {{ old('topic') === 'other'
                                    ? 'selected'
                                    : ''
                                }}
                            >

                                {{ app()->getLocale() === 'fr'
                                    ? 'Autre'
                                    : 'Other'
                                }}

                            </option>

                        </select>

                    </div>



                    {{-- =====================================================
                       SUBJECT
                    ===================================================== --}}

                    <div class="contact-field contact-field-full">

                        <label for="subject">

                            {{ app()->getLocale() === 'fr'
                                ? 'Objet'
                                : 'Subject'
                            }}

                        </label>


                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            value="{{ old('subject') }}"
                            placeholder="{{ app()->getLocale() === 'fr'
                                ? 'Comment pouvons-nous vous aider ?'
                                : 'What can we help you with?'
                            }}"
                            required
                        >

                    </div>



                    {{-- =====================================================
                       MESSAGE
                    ===================================================== --}}

                    <div class="contact-field contact-field-full">

                        <label for="message">

                            {{ app()->getLocale() === 'fr'
                                ? 'Message'
                                : 'Message'
                            }}

                        </label>


                        <textarea
                            id="message"
                            name="message"
                            placeholder="{{ app()->getLocale() === 'fr'
                                ? 'Donnez-nous plus de détails sur votre demande...'
                                : 'Tell us more about your question...'
                            }}"
                            required
                        >{{ old('message') }}</textarea>

                    </div>


                </div>



                {{-- =====================================================
                   FORM FOOTER
                ===================================================== --}}

                <div class="contact-form-footer">


                    <p class="contact-form-note">

                        {{ app()->getLocale() === 'fr'
                            ? 'N’incluez jamais de mots de passe ni de renseignements de paiement sensibles.'
                            : 'Please do not include passwords or sensitive payment information.'
                        }}

                    </p>


                    <button
                        type="submit"
                        class="contact-submit-btn"
                    >

                        {{ app()->getLocale() === 'fr'
                            ? 'Envoyer le message'
                            : 'Send Message'
                        }}

                    </button>


                </div>


            </form>


        </div>

    </div>

</section>


@endsection
@extends('admin.layout')


@section(
    'title',
    app()->getLocale() === 'fr'
        ? 'Conversations clients'
        : 'Client Conversations'
)


@section(
    'page-title',
    app()->getLocale() === 'fr'
        ? 'Conversations clients'
        : 'Client Conversations'
)


@section('content')

@php
    $fr = app()->getLocale() === 'fr';
@endphp


<style>

.admin-conversations-page {
    max-width: 1200px;
    margin: 0 auto;
}


/* HEADER */

.conversation-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 20px;
}

.conversation-top h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
}

.conversation-top p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 12px;
}


/* STATS */

.conversation-stats {
    display: flex;
    gap: 10px;
}

.conversation-stat {
    min-width: 115px;
    padding: 10px 13px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #ffffff;
}

.conversation-stat span {
    display: block;
    color: #94a3b8;
    font-size: 9px;
    font-weight: 700;
}

.conversation-stat strong {
    display: block;
    margin-top: 3px;
    color: #0f172a;
    font-size: 18px;
}


/* SEARCH */

.conversation-search {
    margin-bottom: 16px;
    padding: 14px;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #ffffff;
}

.conversation-search form {
    display: flex;
    gap: 8px;
}

.conversation-search input {
    flex: 1;
    height: 38px;
    padding: 0 12px;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    font-size: 11px;
}

.conversation-search button {
    height: 38px;
    padding: 0 18px;
    border: 0;
    border-radius: 9px;
    background: #0284c7;
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
}


/* LIST */

.conversation-list {
    overflow: hidden;
    border: 1px solid #dce6ed;
    border-radius: 15px;
    background: #ffffff;
}

.conversation-head,
.conversation-row {
    display: grid;

    grid-template-columns:
        90px
        1fr
        1fr
        150px
        80px
        90px;

    gap: 12px;
    align-items: center;
}

.conversation-head {
    min-height: 42px;
    padding: 0 15px;

    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;

    color: #64748b;

    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
}

.conversation-row {
    min-height: 76px;
    padding: 11px 15px;

    border-bottom: 1px solid #edf2f7;
}

.conversation-row:last-child {
    border-bottom: 0;
}

.conversation-id {
    font-size: 10px;
    font-weight: 800;
    color: #475569;
}

.conversation-person strong {
    display: block;

    color: #0f172a;

    font-size: 11px;
    font-weight: 800;
}

.conversation-person span {
    display: block;

    margin-top: 3px;

    color: #94a3b8;

    font-size: 8.5px;
}

.conversation-last {
    color: #64748b;
    font-size: 9px;
}

.conversation-last strong {
    display: block;

    overflow: hidden;

    margin-bottom: 3px;

    color: #334155;

    text-overflow: ellipsis;
    white-space: nowrap;
}

.conversation-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 28px;
    height: 24px;

    padding: 0 7px;

    border-radius: 999px;

    background: #eef6fb;

    color: #0369a1;

    font-size: 9px;
    font-weight: 800;
}

.conversation-open {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 30px;

    padding: 0 11px;

    border: 1px solid #0284c7;
    border-radius: 8px;

    color: #0284c7;

    font-size: 9px;
    font-weight: 800;

    text-decoration: none;
}

.conversation-open:hover {
    background: #0284c7;
    color: #ffffff;
}


.conversation-empty {
    padding: 40px 20px;

    text-align: center;

    color: #94a3b8;

    font-size: 11px;
}


.conversation-pagination {
    margin-top: 16px;
}


/* MOBILE */

@media(max-width: 900px) {

    .conversation-top {
        flex-direction: column;
    }

    .conversation-head {
        display: none;
    }

    .conversation-row {
        grid-template-columns: 1fr 1fr;
    }
}

</style>



<div class="admin-conversations-page">


    <div class="conversation-top">

        <div>

            <h2>
                {{ $fr
                    ? 'Surveillance des conversations'
                    : 'Conversation Monitor'
                }}
            </h2>

            <p>
                {{ $fr
                    ? 'Consultez les conversations entre professeurs et étudiants et intervenez si nécessaire.'
                    : 'Review Teacher and Student conversations and intervene when needed.'
                }}
            </p>

        </div>


        <div class="conversation-stats">

            <div class="conversation-stat">

                <span>
                    {{ $fr ? 'CONVERSATIONS' : 'CONVERSATIONS' }}
                </span>

                <strong>
                    {{ number_format($conversationCount) }}
                </strong>

            </div>


            <div class="conversation-stat">

                <span>
                    {{ $fr ? 'MESSAGES' : 'MESSAGES' }}
                </span>

                <strong>
                    {{ number_format($messageCount) }}
                </strong>

            </div>

        </div>

    </div>



    <div class="conversation-search">

        <form
            method="GET"
            action="{{ route('admin.conversations') }}"
        >

            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="{{ $fr
                    ? 'Nom, courriel, message ou numéro de réservation...'
                    : 'Name, email, message or booking number...'
                }}"
            >


            <button type="submit">

                {{ $fr
                    ? 'Rechercher'
                    : 'Search'
                }}

            </button>

        </form>

    </div>



    <div class="conversation-list">


        <div class="conversation-head">

            <div>
                Booking
            </div>

            <div>
                {{ $fr ? 'Professeur' : 'Teacher' }}
            </div>

            <div>
                {{ $fr ? 'Étudiant' : 'Student' }}
            </div>

            <div>
                {{ $fr ? 'Dernier message' : 'Last Message' }}
            </div>

            <div>
                Messages
            </div>

            <div></div>

        </div>



        @forelse($conversations as $booking)

            @php

                $lastMessage =
                    $booking->messages
                        ->sortByDesc('created_at')
                        ->first();


                $lastSender = null;


                if ($lastMessage) {

                    if (
                        optional($lastMessage->sender)->role
                        ===
                        'admin'
                    ) {

                        $lastSender =
                            'DancePair Support';

                    } else {

                        $lastSender =
                            optional(
                                $lastMessage->sender
                            )->name;
                    }
                }

            @endphp


            <div class="conversation-row">


                <div class="conversation-id">

                    #{{ $booking->id }}

                    @if($booking->danceStyle)

                        <div
                            style="
                                margin-top:3px;
                                color:#94a3b8;
                                font-weight:500;
                            "
                        >
                            {{ $booking->danceStyle->name }}
                        </div>

                    @endif

                </div>



                <div class="conversation-person">

                    <strong>
                        {{ $booking->teacher->user->name ?? '—' }}
                    </strong>

                    <span>
                        {{ $booking->teacher->user->email ?? '' }}
                    </span>

                </div>



                <div class="conversation-person">

                    <strong>
                        {{ $booking->student->user->name ?? '—' }}
                    </strong>

                    <span>
                        {{ $booking->student->user->email ?? '' }}
                    </span>

                </div>



                <div class="conversation-last">

                    @if($lastMessage)

                        <strong>
                            {{ $lastSender }}
                        </strong>

                        {{ \Illuminate\Support\Str::limit(
                            $lastMessage->message,
                            55
                        ) }}

                    @else

                        —

                    @endif

                </div>



                <div>

                    <span class="conversation-count">

                        {{ $booking->messages_count }}

                    </span>

                </div>



                <div>

                    <a
                        href="{{ route(
                            'admin.conversations.show',
                            $booking
                        ) }}"
                        class="conversation-open"
                    >

                        {{ $fr
                            ? 'Ouvrir'
                            : 'Open'
                        }}

                    </a>

                </div>


            </div>


        @empty


            <div class="conversation-empty">

                {{ $fr
                    ? 'Aucune conversation trouvée.'
                    : 'No conversations found.'
                }}

            </div>


        @endforelse


    </div>



    @if($conversations->hasPages())

        <div class="conversation-pagination">

            {{ $conversations->links() }}

        </div>

    @endif


</div>

@endsection
@auth

@if(
    in_array(
        auth()->user()->role,
        [
            'teacher',
            'student',
        ],
        true
    )
)

@php

    $dpWidgetFr =
        app()->getLocale()
        ===
        'fr';

@endphp


<style>

/* =========================================================
   DANCEPAIR FLOATING BUTTON
========================================================= */

.dp-message-launcher {

    position: fixed;

    right: 24px;
    bottom: 24px;

    z-index: 90000;

    width: 68px;
    height: 68px;

    padding: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid rgba(2, 132, 199, .18);
    border-radius: 22px;

    background: #ffffff;

    box-shadow:
        0 14px 38px rgba(15, 23, 42, .18),
        0 4px 12px rgba(15, 23, 42, .08);

    cursor: pointer;

    transition:
        transform .15s ease,
        box-shadow .15s ease;
}


.dp-message-launcher:hover {

    transform: translateY(-2px);

    box-shadow:
        0 18px 42px rgba(15, 23, 42, .22),
        0 5px 14px rgba(15, 23, 42, .09);
}


.dp-message-launcher img {

    width: 56px;
    height: 56px;

    display: block;

    object-fit: contain;
}


/* =========================================================
   BADGE
========================================================= */

.dp-message-count {

    display: none;

    position: absolute;

    top: -7px;
    right: -7px;

    min-width: 24px;
    height: 24px;

    padding: 0 7px;

    align-items: center;
    justify-content: center;

    border: 3px solid #ffffff;
    border-radius: 999px;

    background: #dc2626;

    color: #ffffff;

    font-size: 9px;
    font-weight: 900;

    line-height: 1;
}


.dp-message-count.visible {

    display: inline-flex;
}


/* =========================================================
   INBOX PANEL
========================================================= */

.dp-inbox-panel {

    display: none;

    position: fixed;

    right: 24px;
    bottom: 102px;

    z-index: 89999;

    width: 360px;
    max-width:
        calc(100vw - 32px);

    overflow: hidden;

    border: 1px solid #dce6ed;
    border-radius: 18px;

    background: #ffffff;

    box-shadow:
        0 20px 55px rgba(15, 23, 42, .20),
        0 5px 16px rgba(15, 23, 42, .07);
}


.dp-inbox-panel.open {

    display: block;

    animation:
        dpInboxOpen
        .15s
        ease-out;
}


@keyframes dpInboxOpen {

    from {

        opacity: 0;

        transform:
            translateY(6px);
    }

    to {

        opacity: 1;

        transform:
            translateY(0);
    }
}


/* =========================================================
   INBOX HEADER
========================================================= */

.dp-inbox-header {

    min-height: 68px;

    padding: 11px 14px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 10px;

    border-bottom: 1px solid #edf2f6;

    background: #f8fbfd;
}


.dp-inbox-brand {

    display: flex;
    align-items: center;

    gap: 10px;
}


.dp-inbox-brand img {

    width: 44px;
    height: 44px;

    object-fit: contain;
}


.dp-inbox-brand-text strong {

    display: block;

    color: #0f172a;

    font-size: 12px;
    font-weight: 850;
}


.dp-inbox-brand-text span {

    display: block;

    margin-top: 1px;

    color: #94a3b8;

    font-size: 8.5px;
}


.dp-inbox-close {

    width: 30px;
    height: 30px;

    padding: 0;

    border: 0;
    border-radius: 8px;

    background: transparent;

    color: #64748b;

    font-size: 19px;

    cursor: pointer;
}


.dp-inbox-close:hover {

    background: #eef2f6;

    color: #0f172a;
}


/* =========================================================
   INBOX LIST
========================================================= */

.dp-inbox-list {

    max-height: 370px;

    overflow-y: auto;

    padding: 7px;
}


.dp-inbox-message {

    width: 100%;

    padding: 10px;

    display: flex;
    align-items: flex-start;

    gap: 10px;

    border: 0;
    border-radius: 11px;

    background: transparent;

    text-align: left;

    cursor: pointer;

    transition:
        background .12s ease;
}


.dp-inbox-message:hover {

    background: #f4f8fb;
}


.dp-inbox-message.unread {

    background: #f0f9ff;
}


/* =========================================================
   MESSAGE IMPORTANCE COLORS - WIDGET RECORDS
========================================================= */

/* NORMAL */
.dp-inbox-message.dp-normal {

    background: #eaf4ff;
}

.dp-inbox-message.dp-normal:hover {

    background: #dbeafe;
}


/* IMPORTANT */
.dp-inbox-message.dp-important {

    background: #fff3e0;
}

.dp-inbox-message.dp-important:hover {

    background: #ffead0;
}


/* CRITICAL */
.dp-inbox-message.dp-critical {

    background: #ffe5e5;
}

.dp-inbox-message.dp-critical:hover {

    background: #ffd6d6;
}


/* UNREAD DOT MATCHES IMPORTANCE */

.dp-inbox-message.dp-normal.unread .dp-inbox-dot {

    background: #0284c7;
}


.dp-inbox-message.dp-important.unread .dp-inbox-dot {

    background: #f59e0b;
}


.dp-inbox-message.dp-critical.unread .dp-inbox-dot {

    background: #dc2626;
}


.dp-inbox-dot {

    width: 8px;
    height: 8px;

    flex: 0 0 8px;

    margin-top: 5px;

    border-radius: 50%;

    background: transparent;
}


.dp-inbox-message.unread
.dp-inbox-dot {

    background: #0284c7;
}


.dp-inbox-message-copy {

    min-width: 0;

    flex: 1;
}


.dp-inbox-message-title {

    display: block;

    overflow: hidden;

    color: #0f172a;

    font-size: 10.5px;
    font-weight: 800;

    text-overflow: ellipsis;
    white-space: nowrap;
}


.dp-inbox-message-date {

    display: block;

    margin-top: 3px;

    color: #94a3b8;

    font-size: 8px;
}


/* =========================================================
   EMPTY
========================================================= */

.dp-inbox-empty {

    padding: 30px 16px;

    text-align: center;

    color: #94a3b8;

    font-size: 10px;
}


/* =========================================================
   OVERLAY
========================================================= */

.dp-message-overlay {

    display: none;

    position: fixed;

    inset: 0;

    z-index: 999999;

    padding: 20px;

    align-items: center;
    justify-content: center;

    background:
        rgba(15, 23, 42, .55);

    backdrop-filter:
        blur(4px);
}


.dp-message-overlay.open {

    display: flex;
}


/* =========================================================
   CLIENT MESSAGE BOX
========================================================= */

.dp-client-message {

    width: 100%;
    max-width: 520px;

    overflow: hidden;

    border: 2px solid #0284c7;
    border-top-width: 5px;
    border-radius: 18px;

    background: #ffffff;

    box-shadow:
        0 28px 80px rgba(15, 23, 42, .30);
}


/* =========================================================
   POPUP BORDER COLOR BY IMPORTANCE
========================================================= */


/* NORMAL */

.dp-client-message.dp-normal {

    border-color: #0284c7;
}


/* IMPORTANT */

.dp-client-message.dp-important {

    border-color: #f59e0b;

    box-shadow:
        0 28px 80px rgba(245, 158, 11, .18);
}


/* CRITICAL */

.dp-client-message.dp-critical {

    border-color: #dc2626;

    box-shadow:
        0 28px 80px rgba(220, 38, 38, .22);
}


/*
 * IMPORTANT:
 *
 * Client must NOT see the words:
 *
 * Normal
 * Important
 * Critical
 *
 * Importance changes only:
 *
 * - Popup border color
 * - Widget record background
 * - Auto-show behavior
 */


/* =========================================================
   MESSAGE HEADER
========================================================= */

.dp-client-header {

    min-height: 82px;

    padding: 12px 20px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 14px;

    border-bottom: 1px solid #edf2f7;

    background: #f8fbfd;
}


.dp-client-brand {

    display: flex;
    align-items: center;

    gap: 13px;
}


.dp-client-logo {

    width: 64px;
    height: 64px;

    flex: 0 0 64px;

    display: flex;
    align-items: center;
    justify-content: center;
}


.dp-client-logo img {

    width: 100%;
    height: 100%;

    display: block;

    object-fit: contain;
}


.dp-client-brand strong {

    color: #0f172a;

    font-size: 18px;
    font-weight: 850;
}


.dp-client-x {

    width: 32px;
    height: 32px;

    padding: 0;

    border: 0;
    border-radius: 8px;

    background: transparent;

    color: #64748b;

    font-size: 21px;

    cursor: pointer;
}


.dp-client-x:hover {

    background: #eef2f6;

    color: #0f172a;
}


/* =========================================================
   MESSAGE CONTENT
========================================================= */

.dp-client-content {

    padding: 24px 24px 20px;
}


.dp-client-title {

    margin: 0 0 11px;

    color: #0f172a;

    font-size: 20px;
    font-weight: 850;

    line-height: 1.35;
}


.dp-client-text {

    color: #475569;

    font-size: 12px;

    line-height: 1.75;

    white-space: pre-line;

    overflow-wrap: anywhere;
}


/* =========================================================
   MESSAGE FOOTER
========================================================= */

.dp-client-footer {

    padding:
        0
        24px
        20px;

    display: flex;
    justify-content: flex-end;
}


.dp-client-close {

    min-width: 96px;
    height: 37px;

    padding: 0 16px;

    border: 1px solid #cbd5e1;
    border-radius: 8px;

    background: #ffffff;

    color: #475569;

    font-size: 9px;
    font-weight: 700;

    cursor: pointer;
}


.dp-client-close:hover {

    background: #f8fafc;

    border-color: #94a3b8;

    color: #0f172a;
}


/* =========================================================
   MOBILE
========================================================= */

@media(max-width: 600px) {

    .dp-message-launcher {

        right: 14px;
        bottom: 14px;

        width: 60px;
        height: 60px;

        border-radius: 19px;
    }


    .dp-message-launcher img {

        width: 49px;
        height: 49px;
    }


    .dp-inbox-panel {

        right: 14px;
        bottom: 84px;

        width:
            calc(100vw - 28px);
    }


    .dp-client-logo {

        width: 54px;
        height: 54px;

        flex-basis: 54px;
    }


    .dp-client-brand strong {

        font-size: 16px;
    }


    .dp-client-content {

        padding:
            20px
            17px
            17px;
    }


    .dp-client-footer {

        padding:
            0
            17px
            17px;
    }
}

</style>



{{-- =========================================================
   FLOATING LOGO
========================================================= --}}

<button
    type="button"
    id="dpMessageLauncher"
    class="dp-message-launcher"
    aria-label="DancePair Messages"
>

    <img
        src="{{ asset('logo/logo.png') }}"
        alt="DancePair"
    >


    <span
        id="dpMessageCount"
        class="dp-message-count"
    >
        0
    </span>

</button>



{{-- =========================================================
   INBOX
========================================================= --}}

<div
    id="dpInboxPanel"
    class="dp-inbox-panel"
>


    <div class="dp-inbox-header">


        <div class="dp-inbox-brand">


            <img
                src="{{ asset('logo/logo.png') }}"
                alt="DancePair"
            >


            <div class="dp-inbox-brand-text">

                <strong>
                    DancePair
                </strong>


                <span>

                    {{ $dpWidgetFr
                        ? 'Messages'
                        : 'Messages'
                    }}

                </span>

            </div>


        </div>



        <button
            type="button"
            id="dpInboxClose"
            class="dp-inbox-close"
            aria-label="Close"
        >
            ×
        </button>


    </div>



    <div
        id="dpInboxList"
        class="dp-inbox-list"
    >

        <div class="dp-inbox-empty">

            {{ $dpWidgetFr
                ? 'Chargement...'
                : 'Loading...'
            }}

        </div>

    </div>


</div>



{{-- =========================================================
   MESSAGE POPUP
========================================================= --}}

<div
    id="dpMessageOverlay"
    class="dp-message-overlay"
    aria-hidden="true"
>


    <div
        id="dpClientMessageBox"
        class="dp-client-message"
    >


        {{-- HEADER --}}

        <div class="dp-client-header">


            <div class="dp-client-brand">


                <div class="dp-client-logo">

                    <img
                        src="{{ asset('logo/logo.png') }}"
                        alt="DancePair"
                    >

                </div>


                <strong>
                    DancePair
                </strong>


            </div>



            <button
                type="button"
                id="dpMessageCloseX"
                class="dp-client-x"
                aria-label="Close"
            >
                ×
            </button>


        </div>



        {{-- CONTENT --}}

        <div class="dp-client-content">


            <h3
                id="dpMessageTitle"
                class="dp-client-title"
            ></h3>


            <div
                id="dpMessageText"
                class="dp-client-text"
            ></div>


        </div>



        {{-- FOOTER --}}

        <div class="dp-client-footer">


            <button
                type="button"
                id="dpMessageClose"
                class="dp-client-close"
            >

                {{ $dpWidgetFr
                    ? 'Fermer'
                    : 'Close'
                }}

            </button>


        </div>


    </div>


</div>



<script>

(function () {

    function initDancePairMessages()
    {
        /* =====================================================
           ELEMENT GUARD
        ====================================================== */

        const launcher =
            document.getElementById(
                'dpMessageLauncher'
            );


        const badge =
            document.getElementById(
                'dpMessageCount'
            );


        const inboxPanel =
            document.getElementById(
                'dpInboxPanel'
            );


        const inboxList =
            document.getElementById(
                'dpInboxList'
            );


        const inboxClose =
            document.getElementById(
                'dpInboxClose'
            );


        const overlay =
            document.getElementById(
                'dpMessageOverlay'
            );


        const messageBox =
            document.getElementById(
                'dpClientMessageBox'
            );


        const messageTitle =
            document.getElementById(
                'dpMessageTitle'
            );


        const messageText =
            document.getElementById(
                'dpMessageText'
            );


        const messageCloseX =
            document.getElementById(
                'dpMessageCloseX'
            );


        const messageClose =
            document.getElementById(
                'dpMessageClose'
            );


        /*
         * Widget HTML is not available.
         */

        if (
            !launcher
            ||
            !inboxPanel
            ||
            !inboxList
            ||
            !overlay
            ||
            !messageBox
            ||
            !messageTitle
            ||
            !messageText
        ) {

            console.error(
                'DancePair message widget HTML was not found.'
            );

            return;
        }


        /*
         * Do not bind the same DOM widget twice.
         *
         * If another page/navigation replaces the widget DOM,
         * the new launcher will not have this flag and can
         * initialize normally.
         */

        if (
            launcher.dataset.dpMessagesInitialized
            ===
            '1'
        ) {

            return;
        }


        launcher.dataset.dpMessagesInitialized =
            '1';



        /* =====================================================
           CONFIG
        ====================================================== */

        const inboxUrl =
            @json(
                route(
                    'platform-messages.inbox'
                )
            );


        const messageBaseUrl =
            @json(
                url(
                    '/platform-messages'
                )
            );


        const csrfToken =
            @json(
                csrf_token()
            );



        /* =====================================================
           STATE
        ====================================================== */

        let messages =
            [];


        let currentMessage =
            null;


        let unreadCount =
            0;


        /*
         * Prevent automatic popup from reopening repeatedly
         * on the SAME rendered page.
         *
         * Normal:
         * Backend returns it only until first display.
         *
         * Important:
         * Backend returns it once per login/session.
         *
         * Critical:
         * Backend returns it on every page.
         */

        let autoMessageOpenedOnThisPage =
            false;



        /* =====================================================
           ESCAPE HTML
        ====================================================== */

        function escapeHtml(value)
        {
            const div =
                document.createElement(
                    'div'
                );


            div.textContent =
                value ?? '';


            return div.innerHTML;
        }



        /* =====================================================
           BADGE
        ====================================================== */

        function updateBadge()
        {
            if (!badge) {
                return;
            }


            badge.textContent =
                unreadCount > 99
                    ? '99+'
                    : String(unreadCount);


            if (unreadCount > 0) {

                badge.classList.add(
                    'visible'
                );

            } else {

                badge.classList.remove(
                    'visible'
                );
            }
        }



        /* =====================================================
           RENDER INBOX
        ====================================================== */

        function renderInbox()
        {
            if (
                !Array.isArray(messages)
                ||
                messages.length === 0
            ) {

                inboxList.innerHTML =
                    `
                        <div class="dp-inbox-empty">
                            ${
                                @json(
                                    $dpWidgetFr
                                        ? 'Aucun message DancePair.'
                                        : 'No DancePair messages.'
                                )
                            }
                        </div>
                    `;


                return;
            }


            inboxList.innerHTML =
                messages
                    .map(
                        function (message) {

                            return `
                                <button
                                    type="button"
                                    class="
                                        dp-inbox-message

                                        ${
                                            message.is_read
                                                ? ''
                                                : 'unread'
                                        }

                                        ${
                                            message.importance === 'critical'
                                                ? 'dp-critical'
                                                : (
                                                    message.importance === 'important'
                                                        ? 'dp-important'
                                                        : 'dp-normal'
                                                )
                                        }
                                    "
                                    data-recipient-id="${message.recipient_id}"
                                >

                                    <span class="dp-inbox-dot"></span>


                                    <span class="dp-inbox-message-copy">

                                        <span class="dp-inbox-message-title">
                                            ${escapeHtml(message.title)}
                                        </span>


                                        <span class="dp-inbox-message-date">
                                            ${escapeHtml(message.created_at_formatted)}
                                        </span>

                                    </span>

                                </button>
                            `;
                        }
                    )
                    .join('');


            inboxList
                .querySelectorAll(
                    '.dp-inbox-message'
                )
                .forEach(
                    function (button) {

                        button.addEventListener(
                            'click',
                            function () {

                                const recipientId =
                                    Number(
                                        button.dataset.recipientId
                                    );


                                const selected =
                                    messages.find(
                                        function (message) {

                                            return (
                                                Number(
                                                    message.recipient_id
                                                )
                                                ===
                                                recipientId
                                            );
                                        }
                                    );


                                if (selected) {

                                    openMessage(
                                        selected
                                    );
                                }
                            }
                        );
                    }
                );
        }



        /* =====================================================
           INBOX OPEN / CLOSE
        ====================================================== */

        function toggleInbox()
        {
            inboxPanel.classList.toggle(
                'open'
            );
        }


        function closeInbox()
        {
            inboxPanel.classList.remove(
                'open'
            );
        }



        /* =====================================================
           POST HELPER
        ====================================================== */

        async function postAction(
            recipientId,
            action
        ) {

            try {

                const response =
                    await fetch(
                        `${messageBaseUrl}/${recipientId}/${action}`,
                        {

                            method:
                                'POST',

                            credentials:
                                'same-origin',

                            cache:
                                'no-store',

                            headers: {

                                'X-CSRF-TOKEN':
                                    csrfToken,

                                'Accept':
                                    'application/json',

                                'Content-Type':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },

                            body:
                                JSON.stringify({}),
                        }
                    );


                if (!response.ok) {

                    console.error(
                        'DancePair message action failed:',
                        action,
                        response.status
                    );
                }

            } catch (error) {

                console.error(
                    'DancePair message action failed:',
                    error
                );
            }
        }



        /* =====================================================
           OPEN MESSAGE
        ====================================================== */

        function openMessage(message)
        {
            if (!message) {
                return;
            }


            currentMessage =
                message;


            messageTitle.textContent =
                message.title
                ?? '';


            messageText.textContent =
                message.message
                ?? '';


            /* =================================================
               POPUP COLOR BY IMPORTANCE
            ================================================= */

            messageBox.classList.remove(
                'dp-normal',
                'dp-important',
                'dp-critical'
            );


            if (
                message.importance
                ===
                'critical'
            ) {

                messageBox.classList.add(
                    'dp-critical'
                );

            } else if (
                message.importance
                ===
                'important'
            ) {

                messageBox.classList.add(
                    'dp-important'
                );

            } else {

                messageBox.classList.add(
                    'dp-normal'
                );
            }


            /*
             * IMPORTANT:
             *
             * Client must NEVER see:
             *
             * Normal
             * Important
             * Critical
             *
             * Importance is used only for backend/display logic.
             */


            closeInbox();


            /*
             * Force the popup visible.
             *
             * Inline !important makes sure no other CSS rule
             * can accidentally keep the overlay hidden.
             */

            overlay.classList.add(
                'open'
            );


            overlay.style.setProperty(
                'display',
                'flex',
                'important'
            );


            overlay.setAttribute(
                'aria-hidden',
                'false'
            );


            document.body.style.overflow =
                'hidden';


            /*
             * Mark local copy as read immediately.
             */

            if (!message.is_read) {

                message.is_read =
                    true;


                unreadCount =
                    Math.max(
                        0,
                        unreadCount - 1
                    );


                updateBadge();

                renderInbox();
            }


            /*
             * Tell backend that the message was displayed/read.
             */

            postAction(
                message.recipient_id,
                'shown'
            );
        }



        /* =====================================================
           CLOSE MESSAGE
        ====================================================== */

        function closeMessage()
        {
            overlay.classList.remove(
                'open'
            );


            overlay.style.removeProperty(
                'display'
            );


            overlay.setAttribute(
                'aria-hidden',
                'true'
            );


            document.body.style.overflow =
                '';


            /*
             * Closing does NOT remove the message from widget.
             */

            if (currentMessage) {

                postAction(
                    currentMessage.recipient_id,
                    'dismiss'
                );
            }


            currentMessage =
                null;
        }



        /* =====================================================
           AUTOMATIC MESSAGE
        ====================================================== */

        function openAutomaticMessage(data)
        {
            if (
                autoMessageOpenedOnThisPage
                ||
                !data
            ) {

                return;
            }


            /*
             * Primary source:
             * Backend auto_recipient_id.
             *
             * This applies the exact rules:
             *
             * Normal    = first time only
             * Important = once per login/session
             * Critical  = every page
             */

            let automaticRecipientId =
                data.auto_recipient_id
                ?? null;


            /*
             * Critical fallback:
             *
             * If a Critical message exists in the returned list
             * but auto_recipient_id is unexpectedly missing,
             * Critical still must open on this page.
             */

            if (!automaticRecipientId) {

                const criticalMessage =
                    messages.find(
                        function (message) {

                            return (
                                message.importance
                                ===
                                'critical'
                            );
                        }
                    );


                if (criticalMessage) {

                    automaticRecipientId =
                        criticalMessage.recipient_id;
                }
            }


            if (!automaticRecipientId) {
                return;
            }


            const autoMessage =
                messages.find(
                    function (message) {

                        return (
                            Number(
                                message.recipient_id
                            )
                            ===
                            Number(
                                automaticRecipientId
                            )
                        );
                    }
                );


            if (!autoMessage) {

                console.error(
                    'DancePair automatic message was not found in inbox:',
                    automaticRecipientId
                );

                return;
            }


            autoMessageOpenedOnThisPage =
                true;


            /*
             * Open immediately after inbox data is received.
             */

            openMessage(
                autoMessage
            );
        }



        /* =====================================================
           LOAD MESSAGES
        ====================================================== */

        async function loadMessages()
        {
            try {

                const response =
                    await fetch(
                        inboxUrl,
                        {

                            method:
                                'GET',

                            credentials:
                                'same-origin',

                            cache:
                                'no-store',

                            headers: {

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },
                        }
                    );


                if (!response.ok) {

                    console.error(
                        'DancePair inbox failed:',
                        response.status
                    );

                    return;
                }


                const data =
                    await response.json();


                messages =
                    Array.isArray(
                        data.messages
                    )
                        ? data.messages
                        : [];


                unreadCount =
                    Number(
                        data.unread_count
                        ?? 0
                    );


                updateBadge();

                renderInbox();


                /*
                 * Auto-open after data has rendered.
                 */

                openAutomaticMessage(
                    data
                );

            } catch (error) {

                console.error(
                    'DancePair messages could not load:',
                    error
                );
            }
        }



        /* =====================================================
           EVENTS
        ====================================================== */

        launcher.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();

                toggleInbox();
            }
        );


        if (inboxClose) {

            inboxClose.addEventListener(
                'click',
                closeInbox
            );
        }


        if (messageCloseX) {

            messageCloseX.addEventListener(
                'click',
                closeMessage
            );
        }


        if (messageClose) {

            messageClose.addEventListener(
                'click',
                closeMessage
            );
        }


        overlay.addEventListener(
            'click',
            function (event) {

                if (
                    event.target
                    ===
                    overlay
                ) {

                    closeMessage();
                }
            }
        );


        document.addEventListener(
            'click',
            function (event) {

                if (
                    !inboxPanel.contains(
                        event.target
                    )
                    &&
                    !launcher.contains(
                        event.target
                    )
                ) {

                    closeInbox();
                }
            }
        );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key
                    ===
                    'Escape'
                ) {

                    if (
                        overlay.classList.contains(
                            'open'
                        )
                        ||
                        overlay.style.display
                        ===
                        'flex'
                    ) {

                        closeMessage();

                    } else {

                        closeInbox();
                    }
                }
            }
        );



        /* =====================================================
           FIRST LOAD
        ====================================================== */

        loadMessages();



        /* =====================================================
           BACK / FORWARD CACHE
        ====================================================== */

        window.addEventListener(
            'pageshow',
            function (event) {

                /*
                 * Browser Back/Forward may restore a page
                 * from memory instead of rebuilding it.
                 *
                 * Treat restored page as a newly entered page.
                 *
                 * Critical:
                 * opens again.
                 *
                 * Important / Normal:
                 * backend continues to enforce their rules.
                 */

                if (event.persisted) {

                    autoMessageOpenedOnThisPage =
                        false;


                    loadMessages();
                }
            }
        );
    }



    /* =========================================================
       INITIALIZE
    ========================================================= */

    /*
     * Works whether the DOM is still loading
     * or already ready.
     */

    if (
        document.readyState
        ===
        'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initDancePairMessages,
            {
                once: true
            }
        );

    } else {

        initDancePairMessages();
    }

})();

</script>


@endif

@endauth
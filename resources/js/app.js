import 'bootstrap';


document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD MOBILE SIDEBAR
    |--------------------------------------------------------------------------
    |
    | Used by:
    |
    | - Admin
    | - Student
    | - Teacher
    |
    */

    const sidebar =
        document.querySelector(
            '[data-mobile-sidebar]'
        );

    const sidebarOverlay =
        document.querySelector(
            '[data-mobile-sidebar-overlay]'
        );

    const sidebarOpenButtons =
        document.querySelectorAll(
            '[data-mobile-sidebar-open]'
        );

    const sidebarCloseButtons =
        document.querySelectorAll(
            '[data-mobile-sidebar-close]'
        );


    const openSidebar = () => {

        if (!sidebar) {
            return;
        }

        sidebar.classList.add(
            'mobile-sidebar-open'
        );

        sidebarOverlay?.classList.add(
            'mobile-sidebar-overlay-open'
        );

        document.body.classList.add(
            'mobile-menu-is-open'
        );


        sidebarOpenButtons.forEach(
            button => {

                button.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }
        );
    };


    const closeSidebar = () => {

        if (!sidebar) {
            return;
        }

        sidebar.classList.remove(
            'mobile-sidebar-open'
        );

        sidebarOverlay?.classList.remove(
            'mobile-sidebar-overlay-open'
        );

        document.body.classList.remove(
            'mobile-menu-is-open'
        );


        sidebarOpenButtons.forEach(
            button => {

                button.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }
        );
    };


    sidebarOpenButtons.forEach(
        button => {

            button.addEventListener(
                'click',
                openSidebar
            );

        }
    );


    sidebarCloseButtons.forEach(
        button => {

            button.addEventListener(
                'click',
                closeSidebar
            );

        }
    );


    sidebarOverlay?.addEventListener(
        'click',
        closeSidebar
    );


    /*
    |--------------------------------------------------------------------------
    | CLOSE DASHBOARD MENU AFTER CLICKING A LINK
    |--------------------------------------------------------------------------
    |
    | Mobile + Tablet drawer is active below 992px.
    |
    */

    sidebar
        ?.querySelectorAll('a')
        .forEach(
            link => {

                link.addEventListener(
                    'click',
                    () => {

                        if (
                            window.innerWidth
                            < 992
                        ) {

                            closeSidebar();

                        }

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | PUBLIC MOBILE MENU
    |--------------------------------------------------------------------------
    */

    const publicMenu =
        document.querySelector(
            '[data-public-mobile-menu]'
        );

    const publicMenuOverlay =
        document.querySelector(
            '[data-public-mobile-overlay]'
        );

    const publicOpenButtons =
        document.querySelectorAll(
            '[data-public-mobile-open]'
        );

    const publicCloseButtons =
        document.querySelectorAll(
            '[data-public-mobile-close]'
        );


    const openPublicMenu = () => {

        if (!publicMenu) {
            return;
        }

        publicMenu.classList.add(
            'public-mobile-menu-open'
        );

        publicMenuOverlay?.classList.add(
            'public-mobile-overlay-open'
        );

        document.body.classList.add(
            'mobile-menu-is-open'
        );


        publicOpenButtons.forEach(
            button => {

                button.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }
        );
    };


    const closePublicMenu = () => {

        if (!publicMenu) {
            return;
        }

        publicMenu.classList.remove(
            'public-mobile-menu-open'
        );

        publicMenuOverlay?.classList.remove(
            'public-mobile-overlay-open'
        );

        document.body.classList.remove(
            'mobile-menu-is-open'
        );


        publicOpenButtons.forEach(
            button => {

                button.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }
        );
    };


    publicOpenButtons.forEach(
        button => {

            button.addEventListener(
                'click',
                openPublicMenu
            );

        }
    );


    publicCloseButtons.forEach(
        button => {

            button.addEventListener(
                'click',
                closePublicMenu
            );

        }
    );


    publicMenuOverlay?.addEventListener(
        'click',
        closePublicMenu
    );


    /*
    |--------------------------------------------------------------------------
    | CLOSE PUBLIC MENU AFTER CLICKING A LINK
    |--------------------------------------------------------------------------
    |
    | The user should not remain with the drawer open after navigation.
    |
    */

    publicMenu
        ?.querySelectorAll('a')
        .forEach(
            link => {

                link.addEventListener(
                    'click',
                    () => {

                        if (
                            window.innerWidth
                            < 992
                        ) {

                            closePublicMenu();

                        }

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | ESC KEY
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        event => {

            if (
                event.key !==
                'Escape'
            ) {
                return;
            }

            closeSidebar();

            closePublicMenu();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | WINDOW RESIZE
    |--------------------------------------------------------------------------
    |
    | Prevent mobile drawer state from remaining active
    | when switching back to desktop.
    |
    */

    window.addEventListener(
        'resize',
        () => {

            if (
                window.innerWidth
                >= 992
            ) {

                closeSidebar();

                closePublicMenu();

            }

        }
    );

});
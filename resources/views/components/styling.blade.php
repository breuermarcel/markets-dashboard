<style>

body {
    font-size: 16px;
    font-family: 'Noto Sans JP', sans-serif;
}

.cursor {
    cursor: pointer;
}

.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar.active {
    color: #007bff;
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
}

.sidebar .sidebar-heading {
    font-size: .75rem;
    text-transform: uppercase;
}

.navbar .navbar-brand {
    padding-top: .75rem;
    padding-bottom: .75rem;
    font-size: 1rem;
    background-color: rgba(0, 0, 0, .25);
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
}

.navbar .navbar-toggler {
    top: .25rem;
    right: 1rem;
}

.navbar .form-control {
    padding: .75rem 1rem;
    border-width: 0;
    border-radius: 0;
}

.navbar .form-control-dark {
    color: #fff;
    background-color: rgba(255, 255, 255, .1);
    border-color: rgba(255, 255, 255, .1);
}

.navbar .form-control-dark:focus {
    border-color: transparent;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
}

.breadcrumb-nav .breadcrumb .breadcrumb-item:first-of-type {
    font-weight: bold;
}

.breadcrumb-nav .breadcrumb .breadcrumb-item a {
    color: #333;
    text-decoration: none;
}

.breadcrumb-nav .breadcrumb .breadcrumb-item.active a {
    color: rgba(51, 51, 51, 0.8);
}


.stretched-link, .stretched-link:hover {
    color: inherit;
    text-decoration: none;
}


@media (min-width: 1200px) {
    .mw-75 {
        max-width: 75%;
    }

    .mw-50 {
        max-width: 50%;
    }
}

@media (max-width: 768px) {
    .sidebar {
        top: 5rem;
    }
}
</style>

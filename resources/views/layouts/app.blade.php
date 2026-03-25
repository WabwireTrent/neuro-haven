<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Neuro Haven') }} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        body {
            position: relative;
            overflow-x: hidden;
        }
        .page-wrapper {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="dashboard-page" data-page="@yield('page', '')">
    <div class="page-wrapper">
        <header class="site-header">
            <div class="container site-header__row">
                <a class="brand" href="{{ url('/') }}" aria-label="Neuro Haven home">
                    <img src="{{ asset('assets/images/best logo.png') }}" alt="Neuro Haven" class="brand__logo">
                </a>
                <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" data-nav-toggle>
                    <svg aria-hidden="true" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="3" y1="5" x2="17" y2="5" />
                        <line x1="3" y1="10" x2="17" y2="10" />
                        <line x1="3" y1="15" x2="17" y2="15" />
                    </svg>
                </button>

                <div class="site-nav-right">
                    <nav class="site-nav" aria-label="Primary">
                        <a class="nav-link" href="{{ route('dashboard') }}" data-nav-link="dashboard">Dashboard</a>
                        
                        <!-- Therapy & Progress -->
                        <a class="nav-link" href="{{ route('mood.tracker') }}" data-nav-link="mood">Mood Tracker</a>
                        <a class="nav-link" href="{{ route('progress.tracking') }}" data-nav-link="progress">Progress</a>
                        
                        <!-- VR Features -->
                        <a class="nav-link" href="{{ route('vr.assets') }}" data-nav-link="vr">VR Assets</a>
                        <a class="nav-link" href="{{ route('vr.analytics') }}" data-nav-link="analytics">VR Analytics</a>
                        
                        @auth
                            <!-- Therapist & Admin Links -->
                            @if(auth()->user()->isTherapist() || auth()->user()->isAdmin())
                                <div style="height: 1px; background: rgba(29, 159, 118, 0.2); margin: 0 0.5rem;"></div>
                                <a class="nav-link" href="{{ route('therapist.dashboard') }}" data-nav-link="therapist">👨‍⚕️ Therapist</a>
                            @endif
                            
                            @if(auth()->user()->isAdmin())
                                <a class="nav-link" href="{{ route('admin.dashboard') }}" data-nav-link="admin">⚙️ Admin</a>
                            @endif
                        @endauth
                    </nav>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
            <nav class="mobile-nav" data-mobile-menu aria-label="Mobile navigation">
                <div class="container mobile-nav__list">
                    <a class="nav-link" href="{{ route('dashboard') }}" data-nav-link="dashboard">Dashboard</a>
                    
                    <p style="margin: 0.5rem 0 0; font-size: 0.75rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase;">Therapy & Progress</p>
                    <a class="nav-link" href="{{ route('mood.tracker') }}" data-nav-link="mood">Mood Tracker</a>
                    <a class="nav-link" href="{{ route('progress.tracking') }}" data-nav-link="progress">Progress Tracking</a>
                    
                    <p style="margin: 1rem 0 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase;">VR Features</p>
                    <a class="nav-link" href="{{ route('vr.assets') }}" data-nav-link="vr">VR Assets</a>
                    <a class="nav-link" href="{{ route('vr.analytics') }}" data-nav-link="analytics">VR Analytics</a>
                    
                    @auth
                        @if(auth()->user()->isTherapist() || auth()->user()->isAdmin())
                            <p style="margin: 1rem 0 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase;">Therapist</p>
                            <a class="nav-link" href="{{ route('therapist.dashboard') }}" data-nav-link="therapist">Therapist Dashboard</a>
                            <a class="nav-link" href="{{ route('therapist.patients') }}" data-nav-link="patients">My Patients</a>
                        @endif
                        
                        @if(auth()->user()->isAdmin())
                            <p style="margin: 1rem 0 0.5rem; font-size: 0.75rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase;">Admin</p>
                            <a class="nav-link" href="{{ route('admin.dashboard') }}" data-nav-link="admin">Admin Panel</a>
                        @endif
                    @endauth
                </div>
            </nav>
        </header>

        <main class="page-main">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>
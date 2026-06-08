<!doctype html>
<html lang="en" @if(auth()->check() && auth()->user()->theme === 'dark') data-theme="dark" @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Neuro Haven') }} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    @stack('styles')
</head>
<body data-page="@yield('page', '')" data-sidebar-state="expanded">
    <div class="dashboard-shell">
        {{-- Sidebar --}}
        <aside class="sidebar" data-sidebar>
            <div class="sidebar__brand">
                <a href="{{ auth()->user()->isTherapist() ? route('therapist.dashboard') : (auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard')) }}" class="sidebar__logo" aria-label="Neuro Haven">
                    <span class="sidebar__logo-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </span>
                    <span class="sidebar__logo-text">Neuro Haven</span>
                </a>
                <button class="sidebar__collapse" data-sidebar-toggle aria-label="Toggle sidebar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                </button>
            </div>

            <nav class="sidebar__nav" aria-label="Sidebar navigation">
                @auth
                    @if(auth()->user()->isTherapist() || auth()->user()->isAdmin())
                        {{-- ======= THERAPIST / ADMIN SIDEBAR ======= --}}
                        @if(auth()->user()->isTherapist())
                            <div class="sidebar__section">
                                <span class="sidebar__section-title">Overview</span>
                                <a href="{{ route('therapist.dashboard') }}" class="sidebar__link" data-nav-link="therapist">
                                    <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg></span>
                                    <span class="sidebar__label">Dashboard</span>
                                </a>
                            </div>
                        @endif

                        <div class="sidebar__section">
                            <span class="sidebar__section-title">Clinical</span>
                            @if(auth()->user()->isTherapist())
                                <a href="{{ route('therapist.patients') }}" class="sidebar__link" data-nav-link="patients">
                                    <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                                    <span class="sidebar__label">Patients</span>
                                </a>
                                <a href="{{ route('therapist.assignments.index') }}" class="sidebar__link" data-nav-link="assignments">
                                    <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                    <span class="sidebar__label">Assignments</span>
                                </a>
                                <a href="{{ route('therapist.reports') }}" class="sidebar__link" data-nav-link="reports">
                                    <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 13 12 15 14 13"/></svg></span>
                                    <span class="sidebar__label">Reports</span>
                                </a>
                            @endif
                            <a href="{{ route('therapist.treatment-plans.index') }}" class="sidebar__link" data-nav-link="treatment-plans">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                <span class="sidebar__label">Care Plans</span>
                            </a>
                            <a href="{{ route('outcomes.index') }}" class="sidebar__link" data-nav-link="outcomes">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
                                <span class="sidebar__label">Outcomes</span>
                            </a>
                            @if(auth()->user()->isTherapist())
                                <a href="{{ route('crisis-alerts.index') }}" class="sidebar__link" data-nav-link="crisis-alerts">
                                    <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
                                    <span class="sidebar__label">Crisis Alerts</span>
                                </a>
                            @endif
                        </div>

                        @if(auth()->user()->isAdmin())
                            <div class="sidebar__section">
                                <span class="sidebar__section-title">Administration</span>
                                <a href="{{ route('admin.dashboard') }}" class="sidebar__link" data-nav-link="admin">
                                    <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg></span>
                                    <span class="sidebar__label">Admin Panel</span>
                                </a>
                                <a href="{{ route('admin.users') }}" class="sidebar__link" data-nav-link="therapists">
                                    <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                                    <span class="sidebar__label">Staff</span>
                                </a>
                                <a href="{{ route('crisis-alerts.index') }}" class="sidebar__link" data-nav-link="crisis-alerts">
                                    <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
                                    <span class="sidebar__label">Crisis Alerts</span>
                                </a>
                            </div>
                        @endif
                    @else
                        {{-- ======= PATIENT SIDEBAR ======= --}}
                        <div class="sidebar__section">
                            <span class="sidebar__section-title">Overview</span>
                            <a href="{{ route('dashboard') }}" class="sidebar__link" data-nav-link="dashboard">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg></span>
                                <span class="sidebar__label">Dashboard</span>
                            </a>
                            <a href="{{ route('vr.analytics') }}" class="sidebar__link" data-nav-link="analytics">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/></svg></span>
                                <span class="sidebar__label">Analytics</span>
                            </a>
                            <a href="{{ route('progress.tracking') }}" class="sidebar__link" data-nav-link="progress">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                <span class="sidebar__label">Progress</span>
                            </a>
                        </div>

                        <div class="sidebar__section">
                            <span class="sidebar__section-title">Therapy</span>
                            <a href="{{ route('patient.therapist') }}" class="sidebar__link" data-nav-link="my-therapist">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                                <span class="sidebar__label">My Therapist</span>
                            </a>
                            <a href="{{ route('therapy.sessions') }}" class="sidebar__link" data-nav-link="sessions">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg></span>
                                <span class="sidebar__label">Sessions</span>
                            </a>
                            <a href="{{ route('assessments.index') }}" class="sidebar__link" data-nav-link="assessments">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></span>
                                <span class="sidebar__label">Assessments</span>
                            </a>
                            <a href="{{ route('patient.treatment-plans') }}" class="sidebar__link" data-nav-link="treatment-plans">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                <span class="sidebar__label">Care Plans</span>
                            </a>
                            <a href="{{ route('outcomes.index') }}" class="sidebar__link" data-nav-link="outcomes">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
                                <span class="sidebar__label">Outcomes</span>
                            </a>
                        </div>

                        <div class="sidebar__section">
                            <span class="sidebar__section-title">VR Therapy</span>
                            <a href="{{ route('vr.assets') }}" class="sidebar__link" data-nav-link="vr">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2"/><path d="M10 16l2-2 2 2"/><path d="M8 12h8"/></svg></span>
                                <span class="sidebar__label">VR Library</span>
                            </a>
                            <a href="{{ route('session') }}" class="sidebar__link" data-nav-link="session">
                                <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg></span>
                                <span class="sidebar__label">VR Session</span>
                            </a>
                        </div>
                    @endif
                @endauth

                <div class="sidebar__section">
                    <span class="sidebar__section-title">System</span>
                    <a href="{{ route('notifications.index') }}" class="sidebar__link" data-nav-link="notifications">
                        <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></span>
                        <span class="sidebar__label">Notifications</span>
                        @if(auth()->check() && auth()->user()->unreadNotifications()->count() > 0)
                            <span class="sidebar__badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </a>
                    <a href="{{ route('settings') }}" class="sidebar__link" data-nav-link="settings">
                        <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>
                        <span class="sidebar__label">Settings</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar__footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar__link sidebar__link--logout">
                        <span class="sidebar__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
                        <span class="sidebar__label">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay for mobile --}}
        <div class="sidebar-overlay" data-sidebar-overlay></div>

        {{-- Main Content Area --}}
        <div class="main-area">
            {{-- Top Navbar --}}
            <header class="topbar" data-topbar>
                <div class="topbar__left">
                    <button class="topbar__mobile-menu" data-mobile-menu-toggle aria-label="Open menu">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                    <div class="topbar__search" data-search>
                        <svg class="topbar__search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" class="topbar__search-input" placeholder="Search patients, sessions, resources..." aria-label="Search">
                        <span class="topbar__search-hint">Ctrl+K</span>
                    </div>
                </div>

                <div class="topbar__right">
                    {{-- Theme Toggle --}}
                    <button class="topbar__btn" data-theme-toggle aria-label="Toggle theme" title="Toggle theme">
                        <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                        <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    </button>

                    {{-- VR Headset Status --}}
                    <div id="vr-headset-status" class="topbar__vr-status vr-status vr-status--unknown" title="VR headset detection">
                        <span class="vr-status__dot"></span>
                        <span class="vr-status__text">Checking VR Headset...</span>
                    </div>

                    {{-- System Status --}}
                    <div class="topbar__status" title="System status">
                        <span class="topbar__status-dot"></span>
                        <span class="topbar__status-text">Online</span>
                    </div>

                    {{-- Notifications --}}
                    <div class="topbar__notif" data-notif-toggle>
                        <button class="topbar__btn" aria-label="Notifications">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                            @if(auth()->check() && auth()->user()->unreadNotifications()->count() > 0)
                                <span class="topbar__notif-badge" data-notif-badge>{{ auth()->user()->unreadNotifications()->count() }}</span>
                            @endif
                        </button>
                    </div>

                    {{-- User Profile --}}
                    <div class="topbar__profile" data-profile-toggle>
                        <button class="topbar__profile-btn" aria-label="User menu">
                            <div class="topbar__avatar">
                                <span>{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                            </div>
                            <div class="topbar__profile-info">
                                <span class="topbar__profile-name">{{ auth()->user()->name ?? 'User' }}</span>
                                <span class="topbar__profile-role">{{ auth()->user()->isAdmin() ? 'Administrator' : (auth()->user()->isTherapist() ? 'Therapist' : 'Patient') }}</span>
                            </div>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div class="topbar__profile-menu" data-profile-menu hidden>
                            <a href="{{ route('settings') }}" class="topbar__profile-item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                Settings
                            </a>
                            <hr class="topbar__profile-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="topbar__profile-item topbar__profile-item--danger">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="page-content">
                @if(session('success'))
                    <div class="alert alert--success slide-up">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span>{{ session('success') }}</span>
                        <button class="alert__close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert--danger slide-up">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <span>{{ session('error') }}</span>
                        <button class="alert__close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vr-detection.js') }}"></script>
    @yield('scripts')
    
    @auth
        @include('components.notification-widget')
    @endauth
</body>
</html>

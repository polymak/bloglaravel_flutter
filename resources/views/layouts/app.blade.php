<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<!-- Google Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-slate-950 font-display text-slate-900 dark:text-slate-100 antialiased">
    <div class="relative flex min-h-screen w-full flex-col">
        <!-- Navigation Bar -->
        <header class="sticky top-0 z-50 w-full border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-8">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <h1 class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">Blog<span class="text-primary">Laravel</span></h1>
                    </div>
                    
                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex items-center gap-8">
                        <a class="text-sm font-semibold text-primary" href="{{ url('/') }}">Accueil</a>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="{{ url('/admin/dashboard') }}">Admin</a>
                            @endif
                        @endauth
                    </nav>
                    
                    <!-- Profile -->
                    <div class="flex items-center gap-4 flex-1 justify-end">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                                Login
                            </a>
                        @else
                            <span class="hidden md:inline-flex items-center px-3 py-2 text-sm text-slate-600 dark:text-slate-400">
                                Welcome, {{ Auth::user()->name }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
                                    Logout
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="flex items-center">
                        <h2 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Blog Laravel</h2>
                    </div>
                    <p class="text-sm text-slate-400">© {{ date('Y') }} Blog Laravel. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

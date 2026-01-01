<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Santrix Owner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-slate-800">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-72 bg-slate-900 text-white flex flex-col shadow-xl z-20">
            <!-- Brand -->
            <div class="h-20 flex items-center px-8 border-b border-slate-800 bg-slate-900">
                <div class="flex items-center space-x-3">
                    <div class="flex flex-col">
                        <h1 class="text-2xl font-bold tracking-tight text-white leading-none">SANTRIX</h1>
                        <p class="text-[10px] text-slate-400 font-medium tracking-widest uppercase">by Velora</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 py-8 space-y-2">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Overview</p>
                
                <a href="{{ route('owner.dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('owner.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-8 mb-4">Management</p>
                
                <a href="{{ route('owner.pesantren.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.pesantren.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('owner.pesantren.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="font-medium">Data Pesantren</span>
                </a>

                <a href="{{ route('owner.withdrawal.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.withdrawal.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('owner.withdrawal.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 01-2 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">Pencairan Dana</span>
                </a>
                
                <a href="{{ route('owner.logs') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('owner.logs') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('owner.logs') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Activity Logs</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-slate-800">
                 <div class="bg-slate-800 rounded-xl p-4 flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xs">
                            OP
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Super Owner' }}</p>
                            <p class="text-xs text-slate-400">Administrator</p>
                        </div>
                    </div>
                 </div>
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center px-4 py-2 text-sm text-red-400 hover:bg-slate-800 hover:text-red-300 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col relative overflow-hidden bg-gray-50/50">
            <!-- Glass Topbar -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-200/50 flex items-center justify-between px-8 sticky top-0 z-10 w-full">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                        @yield('title', 'Dashboard')
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">@yield('subtitle', 'Welcome back, Owner!')</p>
                </div>
                <div class="flex items-center space-x-6">
                    <button class="p-2 rounded-full text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-red-500 border-2 border-white"></span>
                    </button>
                    <div class="h-8 w-px bg-gray-200"></div>
                     <span class="text-sm font-medium text-slate-600">{{ now()->format('l, d F Y') }}</span>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto p-8 relative">
                <!-- Background decorative elements -->
                <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-indigo-50/50 to-transparent pointer-events-none -z-10"></div>
                
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-sm" role="alert">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HoursTrack - Enterprise Time Tracking</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-gray-300 font-sans antialiased selection:bg-teal-500/30">

    <!-- Navigation -->
    <nav class="border-b border-gray-900 bg-black/80 backdrop-blur-md fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <!-- Logo and Name -->
            <div class="flex items-center space-x-3">
                <!-- App Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" class="w-10 h-10">
                    <rect width="40" height="40" rx="10" fill="#000000" stroke="#111111" stroke-width="1"/>
                    <rect x="8" y="11" width="5" height="18" rx="2.5" fill="#111111" />
                    <rect x="27" y="11" width="5" height="18" rx="2.5" fill="#111111" />
                    <circle cx="20" cy="20" r="7.5" fill="#000000" stroke="#2DD4BF" stroke-width="1.5" />
                    <line x1="20" y1="20" x2="20" y2="14.5" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" />
                    <line x1="20" y1="20" x2="24.5" y2="20" stroke="#FB923C" stroke-width="1.5" stroke-linecap="round" />
                    <circle cx="20" cy="20" r="1" fill="#2DD4BF" />
                </svg>
                <!-- Wordmark -->
                <span class="text-2xl font-bold tracking-tight text-white">Hours<span class="text-teal-400">Track</span></span>
            </div>

            <!-- Nav Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-sm font-medium text-gray-400 hover:text-white transition">Features</a>
                <a href="#security" class="text-sm font-medium text-gray-400 hover:text-white transition">Security</a>
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-white transition">Log In</a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-teal-500 text-black text-sm font-bold rounded-lg hover:bg-teal-400 transition shadow-[0_0_20px_rgba(45,212,191,0.3)]">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-40 pb-24 px-6 text-center relative overflow-hidden">
        <!-- Subtle Background Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[600px] bg-teal-500/5 rounded-full blur-[120px] -z-10 pointer-events-none"></div>

        <div class="max-w-4xl mx-auto">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full border border-gray-800 bg-gray-900/50 text-teal-400 text-xs font-bold tracking-wide uppercase mb-8">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400 mr-2 animate-pulse"></span>
                Now in Public Beta
            </div>

            <h1 class="text-6xl md:text-7xl font-extrabold text-white tracking-tight mb-8 leading-[1.1]">
                Track Time. <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-cyan-400">Eliminate Fraud.</span>
            </h1>

            <p class="text-xl text-gray-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                The immutable, multi-tenant time tracking system built for modern teams. Prevent buddy punching, automate payroll, and scale without the overhead.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-5">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-teal-500 text-black font-bold rounded-lg hover:bg-teal-400 transition shadow-[0_0_30px_rgba(45,212,191,0.2)]">
                    Start Free Trial
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-gray-900 text-white font-medium rounded-lg border border-gray-800 hover:bg-gray-800 transition">
                    View Features
                </a>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-24 px-6 border-t border-gray-900 bg-black">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Enterprise Features, SaaS Simplicity</h2>
                <p class="text-gray-500 max-w-xl mx-auto">Everything you need to manage hours, approvals, and payroll in one dark-themed dashboard.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-950 border border-gray-900 rounded-2xl p-8 hover:border-teal-500/30 transition group">
                    <div class="w-12 h-12 bg-teal-500/10 rounded

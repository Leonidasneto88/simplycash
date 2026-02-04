<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SimplyCa$h - Gestão Inteligente</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <aside class="w-full md:w-72 bg-white border-r border-emerald-100 flex flex-col shadow-sm h-screen sticky top-0">
            
            <div class="p-6 flex items-center gap-3 border-b border-emerald-50">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 flex-shrink-0">
                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                        <circle cx="12" cy="5" r="2"></circle>
                        <path d="M12 7v4"></path>
                        <line x1="8" y1="16" x2="8" y2="16"></line>
                        <line x1="16" y1="16" x2="16" y2="16"></line>
                    </svg>
                </div>
                <h1 class="text-xl font-black text-emerald-900 tracking-tight italic">SimplyCa$h</h1>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'text-gray-500 hover:bg-emerald-50/50 hover:text-emerald-600' }} rounded-2xl font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    Painel Geral
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-emerald-50/50 hover:text-emerald-600 rounded-2xl font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Bot SimplyCa$h
                </a>

                <div class="pt-4 pb-2 px-4 text-[10px] font-black text-emerald-300 uppercase tracking-[0.2em]">Gestão</div>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-emerald-50/50 hover:text-emerald-600 rounded-2xl font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Dívidas
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-emerald-50/50 hover:text-emerald-600 rounded-2xl font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Investimentos
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-emerald-50/50 hover:text-emerald-600 rounded-2xl font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Histórico
                </a>

                <div class="pt-4 pb-2 px-4 text-[10px] font-black text-emerald-300 uppercase tracking-[0.2em]">Inteligência</div>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-emerald-50/50 hover:text-emerald-600 rounded-2xl font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    Dicas e Insights
                </a>

                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-emerald-50/50 hover:text-emerald-600 rounded-2xl font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Categorias
                </a>
            </nav>

            <div class="p-6 border-t border-emerald-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors">
                        Sair do Sistema
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-x-hidden min-h-screen">
            
            <header class="bg-white/80 backdrop-blur-md border-b border-emerald-50 p-4 sticky top-0 z-40 hidden md:block">
                <div class="flex justify-between items-center">
                    <h2 class="text-emerald-900 font-bold italic pl-4">SimplyCa$h - Minhas Finanças</h2>
                    
                    <div class="flex items-center gap-4 border-l border-emerald-100 pl-4">
                        <div class="text-right">
                            <p class="text-xs font-bold text-emerald-900">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-emerald-500 font-black uppercase tracking-tighter text-right italic">Membro Founder</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-emerald-600 border-2 border-white shadow-md flex items-center justify-center text-white font-black">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 lg:p-10">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
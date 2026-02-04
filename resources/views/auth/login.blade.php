<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SimplyCa$h - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* CSS para o fundo de pontinhos (Option 3) */
        .bg-dots {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-dots text-gray-800">
    
    <div class="min-h-screen flex flex-col justify-center items-center p-4">

        <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] border border-gray-100 relative">
            
            <div class="mb-10 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-emerald-600 rounded-[22px] flex items-center justify-center mb-4 shadow-lg shadow-emerald-200">
                    <svg class="w-12 h-12 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                        <circle cx="12" cy="5" r="2"></circle>
                        <path d="M12 7v4"></path>
                        <line x1="8" y1="16" x2="8" y2="16" stroke-width="3"></line>
                        <line x1="16" y1="16" x2="16" y2="16" stroke-width="3"></line>
                    </svg>
                </div>

                <h1 class="text-3xl font-black text-emerald-900 italic tracking-tighter uppercase">
                    SimplyCa$h
                </h1>
                <div class="h-1 w-12 bg-emerald-500 rounded-full mt-1"></div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block font-black text-[10px] text-emerald-600 uppercase tracking-[0.2em] mb-2 ml-1">E-mail de Acesso</label>
                    <input id="email" class="block w-full bg-gray-50 border-gray-100 text-gray-800 px-5 py-4 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all outline-none shadow-sm" 
                           type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label class="block font-black text-[10px] text-emerald-600 uppercase tracking-[0.2em] mb-2 ml-1">Sua Senha</label>
                    <input id="password" class="block w-full bg-gray-50 border-gray-100 text-gray-800 px-5 py-4 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all outline-none shadow-sm" 
                           type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" name="remember">
                        <span class="ms-2 text-xs font-bold text-gray-400 uppercase">Manter conectado</span>
                    </label>
                    <a class="text-xs font-black text-emerald-600 hover:text-emerald-800 uppercase" href="{{ route('password.request') }}">
                        Esqueceu?
                    </a>
                </div>

                <button type="submit" class="w-full py-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-100 active:scale-[0.98]">
                    Acessar Painel
                </button>
            </form>

            <div class="mt-10 text-center border-t border-gray-50 pt-6">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">
                    Novo por aqui? 
                    <a href="{{ route('register') }}" class="text-emerald-600 hover:underline">Criar Conta</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
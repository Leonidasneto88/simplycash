<nav x-data="{ open: false }" class="bg-white border-b border-emerald-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                         <span class="font-black text-xl tracking-tighter text-emerald-900 italic uppercase">SimplyCa$h</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold uppercase italic text-xs">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')" class="font-bold uppercase italic text-xs">
                        Categorias
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-4 px-3 py-2 border border-transparent rounded-2xl hover:bg-emerald-50 transition-all">
                            <div class="text-right border-r border-emerald-100 pr-4">
                                <div class="text-2xl font-black text-emerald-900 leading-none uppercase italic tracking-tighter">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mt-1">
                                    MEMBRO FOUNDER
                                </div>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-emerald-600 flex items-center justify-center text-white font-black text-xl shadow-lg border-2 border-white">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-bold uppercase text-xs italic">
                            Perfil
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="font-bold uppercase text-xs italic text-red-500">
                                Sair
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-emerald-600">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
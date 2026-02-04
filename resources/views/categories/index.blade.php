<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black text-emerald-900 italic uppercase tracking-tighter">
                    Minhas Categorias
                </h2>
                
                <button onclick="document.getElementById('form-categoria').classList.toggle('hidden')" 
                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2.5 rounded-2xl flex items-center transition-all shadow-md active:scale-95 font-bold text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nova Categoria
                </button>
            </div>

            <div id="form-categoria" class="hidden bg-white p-8 rounded-[2.5rem] border border-emerald-100 shadow-xl mb-8 transition-all animate-in fade-in slide-in-from-top-4">
                <form action="{{ route('categories.store') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                    @csrf
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Nome da Categoria</label>
                        <input type="text" name="name" required placeholder="Ex: Aluguel, Lanches..." 
                               class="w-full border-2 border-emerald-50 rounded-2xl h-12 px-5 font-bold focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Cor</label>
                        <input type="color" name="color" value="#10b981" 
                               class="w-20 h-12 p-1 bg-white border-2 border-emerald-50 rounded-2xl cursor-pointer">
                    </div>
                    <button type="submit" class="bg-emerald-600 text-white h-12 px-8 rounded-2xl font-black hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 uppercase text-xs italic">
                        Salvar
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-emerald-100 shadow-sm overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-emerald-50/50">
                        <tr>
                            <th class="px-8 py-5 text-left text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Categoria</th>
                            <th class="px-8 py-5 text-right text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @forelse($categories as $cat)
                        <tr class="hover:bg-emerald-50/20 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full shadow-sm border border-black/5" style="background-color: {{ $cat->color }}"></div>
                                    <span class="font-bold text-emerald-900">{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Isso afetará as transações vinculadas. Confirmar?')">
                                    @csrf @method('DELETE')
                                    <button class="text-[10px] font-black text-gray-400 hover:text-red-500 uppercase transition-all tracking-tighter">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-8 py-10 text-center text-gray-400 italic font-medium">
                                Nenhuma categoria cadastrada ainda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
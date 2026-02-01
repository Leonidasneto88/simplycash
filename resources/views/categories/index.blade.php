<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-black text-emerald-900 italic mb-8 uppercase tracking-tighter">Minhas Categorias</h2>

            <div class="bg-white p-8 rounded-[2.5rem] border border-emerald-100 shadow-sm mb-8">
                <form action="{{ route('categories.store') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                    @csrf
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Nome da Categoria</label>
                        <input type="text" name="name" required placeholder="Ex: Aluguel, Lanches..." class="w-full border-2 border-emerald-50 rounded-2xl h-12 px-5 font-bold focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Cor</label>
                        <input type="color" name="color" value="#10b981" class="w-20 h-12 p-1 bg-white border-2 border-emerald-50 rounded-2xl cursor-pointer">
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
                            <th class="px-8 py-5 text-left text-[10px] font-bold text-emerald-800 uppercase">Categoria</th>
                            <th class="px-8 py-5 text-center text-[10px] font-bold text-emerald-800 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @foreach($categories as $cat)
                        <tr class="hover:bg-emerald-50/20 transition-colors">
                            <td class="px-8 py-4 flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $cat->color }}"></div>
                                <span class="font-bold text-emerald-900">{{ $cat->name }}</span>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Isso afetará as transações vinculadas. Confirmar?')">
                                    @csrf @method('DELETE')
                                    <button class="text-[10px] font-black text-gray-300 hover:text-red-500 uppercase transition-all">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
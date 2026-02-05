<x-app-layout>
    @if(session('success'))
        <div id="flash-message" class="fixed top-5 right-5 z-[100] bg-emerald-600 text-white px-6 py-3 rounded-2xl shadow-2xl font-bold animate-bounce uppercase text-xs tracking-widest flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => { document.getElementById('flash-message')?.remove(); }, 4000);
        </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black text-emerald-900 italic uppercase tracking-tighter">Categorias</h2>
                <button onclick="openModalForCreate()" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl shadow-lg font-bold flex items-center gap-2 hover:bg-emerald-700 transition-all active:scale-95 uppercase text-xs tracking-widest">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/></svg>
                    Nova Categoria
                </button>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-emerald-100 shadow-sm overflow-hidden">
                <table class="min-w-full border-collapse">
                    <thead class="bg-emerald-50/50">
                        <tr>
                            <th class="px-8 py-5 text-left text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Nome</th>
                            <th class="px-8 py-5 text-left text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Tipo</th>
                            <th class="px-8 py-5 text-center text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @forelse($categories as $cat)
                        <tr class="hover:bg-emerald-50/20 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full shadow-sm shrink-0" style="background-color: {{ $cat->color }}"></div>
                                    <span class="text-sm font-bold text-emerald-900">{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <span class="inline-flex px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $cat->type == 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $cat->type == 'income' ? 'Ganho' : 'Gasto' }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center justify-center gap-4"> 
                                    <button onclick='openModalForEdit(@json($cat))' 
                                            class="text-[10px] font-black text-emerald-600 hover:text-emerald-800 uppercase transition-all tracking-widest leading-none">
                                        Editar
                                    </button>
                                    
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Excluir esta categoria?')" class="m-0 p-0 flex items-center">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="text-[10px] font-black text-gray-300 hover:text-red-500 uppercase transition-all tracking-widest leading-none">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-8 py-10 text-center text-gray-400 text-sm italic">Nenhuma categoria encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="categoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-emerald-900/60 backdrop-blur-sm" onclick="toggleModal()"></div>
            
            <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 border border-emerald-50">
                <button onclick="toggleModal()" class="absolute top-6 right-6 text-gray-400 hover:text-emerald-600 transition-colors p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>

                <h3 id="modalTitle" class="text-xl font-black text-emerald-900 italic uppercase mb-6 text-center tracking-tighter">Nova Categoria</h3>
                
                <form id="categoryForm" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div>
                        <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Nome da Categoria</label>
                        <input type="text" name="name" id="catName" required class="w-full border-2 border-emerald-50 rounded-2xl h-12 px-5 font-bold focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Tipo</label>
                            <select name="type" id="catType" required class="w-full border-2 border-emerald-50 rounded-2xl h-12 px-4 font-bold text-emerald-900 focus:ring-emerald-500">
                                <option value="expense">Gasto</option>
                                <option value="income">Ganho</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Cor</label>
                            <input type="color" name="color" id="catColor" value="#10b981" class="w-full h-12 border-2 border-emerald-50 rounded-2xl p-1 bg-white cursor-pointer">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white h-14 rounded-2xl font-black text-lg hover:bg-emerald-700 transition-all shadow-lg mt-4 uppercase italic">Salvar Categoria</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.toggle('hidden');
            document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
        }

        function openModalForCreate() {
            const form = document.getElementById('categoryForm');
            form.action = "{{ route('categories.store') }}";
            document.getElementById('formMethod').value = "POST";
            document.getElementById('modalTitle').innerText = "Nova Categoria";
            
            document.getElementById('catName').value = "";
            document.getElementById('catType').value = "expense";
            document.getElementById('catColor').value = "#10b981";
            
            toggleModal();
        }

        function openModalForEdit(cat) {
            const form = document.getElementById('categoryForm');
            form.action = "/categories/" + cat.id;
            document.getElementById('formMethod').value = "PUT";
            document.getElementById('modalTitle').innerText = "Editar Categoria";
            
            document.getElementById('catName').value = cat.name;
            document.getElementById('catType').value = cat.type;
            document.getElementById('catColor').value = cat.color;
            
            toggleModal();
        }
    </script>
</x-app-layout>
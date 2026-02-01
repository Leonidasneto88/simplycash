<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @if(session('success'))
        <div id="flash-message" class="fixed top-5 right-5 z-[100] bg-emerald-600 text-white px-6 py-3 rounded-2xl shadow-2xl font-bold animate-bounce uppercase text-xs tracking-widest flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => { 
                const msg = document.getElementById('flash-message');
                if(msg) msg.style.display = 'none';
            }, 4000);
        </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row justify-between items-center mb-8 gap-4">
                <div class="relative w-full lg:w-96">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-emerald-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"/>
                        </svg>
                    </span>
                    <input type="text" id="tableSearch" placeholder="Pesquisar transações..." 
                           class="pl-11 border-emerald-100 rounded-2xl w-full border-2 bg-white h-12 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm">
                </div>
                
                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <button onclick="openModalForCreate()" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl shadow-md font-bold flex items-center gap-2 hover:bg-emerald-700 transition-all active:scale-95 flex-1 lg:flex-none justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                        Lançar
                    </button>
                    
                    <button onclick="window.print()" class="bg-white text-emerald-700 border-2 border-emerald-100 px-5 py-2.5 rounded-xl shadow-sm font-bold flex items-center gap-2 hover:bg-emerald-50 transition-all flex-1 lg:flex-none justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/>
                        </svg>
                        Relatório
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm transition-transform hover:scale-[1.02]">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Receitas</p>
                    <p class="text-2xl font-black text-emerald-600">R$ {{ number_format($income, 2, ',', '.') }}</p>
                </div>
                
                <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm transition-transform hover:scale-[1.02]">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Despesas</p>
                    <p class="text-2xl font-black text-red-600">R$ {{ number_format($expense, 2, ',', '.') }}</p>
                </div>
                
                <div class="p-6 rounded-[2rem] shadow-xl {{ $balance >= 0 ? 'bg-emerald-900' : 'bg-red-900' }} text-white transition-all duration-500 hover:scale-[1.02]">
                    <p class="text-[10px] font-black text-white/60 uppercase tracking-widest mb-1">Saldo Atual</p>
                    <p class="text-2xl font-black italic">R$ {{ number_format($balance, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-emerald-800 p-8 rounded-[3rem] text-white relative overflow-hidden flex flex-col justify-center">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-400">SimplyCa$h AI</span>
                        </div>
                        <h4 class="text-xl font-bold mb-3 italic">Fala, {{ Auth::user()->name }}!</h4>
                        <p class="text-emerald-100 text-sm leading-relaxed">
                            @if($expense > 0)
                                Seu maior volume de gastos está concentrado. Clique em <strong>"Relatório"</strong> para uma análise detalhada ou use o botão <strong>"Lançar"</strong> para atualizar seu caixa.
                            @else
                                Dashboard pronto! Que tal começar lançando sua primeira receita?
                            @endif
                        </p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[3rem] border border-emerald-100 shadow-sm">
                    <h3 class="text-xs font-bold text-emerald-900 mb-6 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        Distribuição de Gastos
                    </h3>
                    <div class="relative" style="height: 250px; width: 100%;">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-emerald-100 shadow-sm overflow-hidden">
                <table class="min-w-full" id="transactionsTable">
                    <thead class="bg-emerald-50/50">
                        <tr>
                            <th class="px-8 py-5 text-left text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Descrição</th>
                            <th class="px-8 py-5 text-left text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Valor</th>
                            <th class="px-8 py-5 text-center text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-emerald-50/20 transition-colors">
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-emerald-900">{{ $t->description }}</p>
                                <span class="text-[10px] font-black uppercase" style="color: {{ $t->category->color ?? '#10b981' }}">
                                    {{ $t->category->name ?? 'Geral' }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-sm font-black {{ $t->type == 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $t->type == 'income' ? '+' : '-' }} R$ {{ number_format($t->amount, 2, ',', '.') }}
                            </td>
                            <td class="px-8 py-4 text-center">
                                <div class="flex items-center justify-center gap-4">
                                    <button onclick='openModalForEdit(@json($t))' class="text-[10px] font-black text-emerald-600 hover:text-emerald-800 uppercase transition-all">Editar</button>
                                    <form action="{{ route('transactions.destroy', $t) }}" method="POST" onsubmit="return confirm('Confirmar exclusão?')">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-black text-gray-300 hover:text-red-500 uppercase transition-all">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-8 py-10 text-center text-gray-400 text-sm italic">Nenhuma transação encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-emerald-900/60 backdrop-blur-sm" onclick="toggleModal()"></div>
            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl p-8 border border-emerald-50">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-xl font-black text-emerald-900 italic uppercase">NOVO LANÇAMENTO</h3>
                    <button onclick="toggleModal()" class="text-gray-300 hover:text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5"/></svg>
                    </button>
                </div>

                <form id="transactionForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="space-y-5">
                        <div class="flex p-1 bg-emerald-50 rounded-2xl">
                            <label class="flex-1 text-center py-2.5 rounded-xl cursor-pointer transition-all has-[:checked]:bg-white has-[:checked]:text-emerald-700">
                                <input type="radio" name="type" value="income" id="radioIncome" class="hidden">
                                <span class="font-bold text-[10px] tracking-widest uppercase">Ganho</span>
                            </label>
                            <label class="flex-1 text-center py-2.5 rounded-xl cursor-pointer transition-all has-[:checked]:bg-emerald-600 has-[:checked]:text-white">
                                <input type="radio" name="type" value="expense" id="radioExpense" class="hidden" checked>
                                <span class="font-bold text-[10px] tracking-widest uppercase">Gasto</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Valor</label>
                            <input type="number" step="0.01" name="amount" id="modalAmount" required class="w-full border-2 border-emerald-50 rounded-2xl h-14 px-5 text-2xl font-black focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Descrição</label>
                            <input type="text" name="description" id="modalDescription" required class="w-full border-2 border-emerald-50 rounded-2xl h-12 px-5 font-bold focus:ring-emerald-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Categoria</label>
                                <select name="category_id" id="modalCategory" required class="w-full border-2 border-emerald-50 rounded-2xl h-12 px-4 font-bold text-emerald-900 focus:ring-emerald-500">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-emerald-800 uppercase mb-2 ml-2">Data</label>
                                <input type="date" name="date" id="modalDate" required class="w-full border-2 border-emerald-50 rounded-2xl h-12 px-4 font-bold focus:ring-emerald-500">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 text-white h-14 rounded-2xl font-black text-lg hover:bg-emerald-700 transition-all shadow-lg mt-4 uppercase italic">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal() {
            document.getElementById('transactionModal').classList.toggle('hidden');
        }

        function openModalForCreate() {
            const form = document.getElementById('transactionForm');
            form.action = "{{ route('transactions.store') }}";
            document.getElementById('formMethod').value = "POST";
            document.getElementById('modalTitle').innerText = "NOVO LANÇAMENTO";
            form.reset();
            toggleModal();
        }

        function openModalForEdit(t) {
            const form = document.getElementById('transactionForm');
            form.action = "/transactions/" + t.id;
            document.getElementById('formMethod').value = "PUT";
            document.getElementById('modalTitle').innerText = "EDITAR LANÇAMENTO";
            
            document.getElementById('modalAmount').value = t.amount;
            document.getElementById('modalDescription').value = t.description;
            document.getElementById('modalCategory').value = t.category_id;
            document.getElementById('modalDate').value = t.date;
            
            if(t.type === 'income') {
                document.getElementById('radioIncome').checked = true;
            } else {
                document.getElementById('radioExpense').checked = true;
            }
            
            document.getElementById('transactionModal').classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Busca
            const searchInput = document.getElementById('tableSearch');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    let term = this.value.toLowerCase();
                    document.querySelectorAll("#transactionsTable tbody tr").forEach(row => {
                        row.style.display = row.innerText.toLowerCase().includes(term) ? "" : "none";
                    });
                });
            }

            // Gráfico
            const chartDataRaw = {!! json_encode($chartData) !!};
            const ctx = document.getElementById('expenseChart');
            if (ctx && chartDataRaw.length > 0) {
                new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: chartDataRaw.map(d => d.label),
                        datasets: [{
                            data: chartDataRaw.map(d => d.value),
                            backgroundColor: chartDataRaw.map(d => d.color),
                            borderWidth: 0,
                            spacing: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '80%',
                        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, font: { weight: 'bold' } } } }
                    }
                });
            }
        });
    </script>
</x-app-layout>
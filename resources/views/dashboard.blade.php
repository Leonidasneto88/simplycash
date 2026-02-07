<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- LÓGICA DO ROBOZINHO ALEATÓRIO --}}
    @php
        $posicoes = [
            'bottom: -20px; right: -20px; transform: rotate(-10deg);',
            'top: -20px; right: 10px; transform: rotate(15deg);',
            'bottom: -10px; left: -20px; transform: rotate(10deg);',
            'top: 50%; right: -30px; transform: translateY(-50%) rotate(-90deg);'
        ];
        $estiloRobo = $posicoes[array_rand($posicoes)];
    @endphp

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
            
            {{-- BARRA DE TOPO --}}
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
                    <button onclick="openModalForCreate()" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl shadow-md font-bold flex items-center gap-2 hover:bg-emerald-700 transition-all active:scale-95 flex-1 lg:flex-none justify-center uppercase text-xs tracking-widest">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                        Lançar
                    </button>
                    <button onclick="window.print()" class="bg-white text-emerald-700 border-2 border-emerald-100 px-5 py-2.5 rounded-xl shadow-sm font-bold flex items-center gap-2 hover:bg-emerald-50 transition-all flex-1 lg:flex-none justify-center uppercase text-xs tracking-widest">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/>
                        </svg>
                        Relatório
                    </button>
                </div>
            </div>

            {{-- NAVEGADOR DE MESES --}}
            <div class="flex justify-center mb-8">
                <form action="{{ route('dashboard') }}" method="GET" id="dateFilterForm" class="flex items-center gap-4 bg-white p-2 rounded-2xl border-2 border-emerald-50 shadow-sm">
                    <select name="month" onchange="this.form.submit()" class="border-none bg-transparent font-black text-emerald-900 focus:ring-0 cursor-pointer uppercase text-xs tracking-widest">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ sprintf('%02d', $i) }}" {{ $month == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <div class="w-[2px] h-4 bg-emerald-100"></div>
                    <select name="year" onchange="this.form.submit()" class="border-none bg-transparent font-black text-emerald-900 focus:ring-0 cursor-pointer text-xs tracking-widest">
                        @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </form>
            </div>

            {{-- CARDS DE RESUMO --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm transition-transform hover:scale-[1.02]">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Receitas Reais</p>
                    <p class="text-2xl font-black text-emerald-600 italic">R$ {{ number_format($incomeReal, 2, ',', '.') }}</p>
                    <div class="mt-2 pt-2 border-t border-gray-100">
                        <p class="text-[9px] font-bold text-gray-400 uppercase">Total Previsto: R$ {{ number_format($income, 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm transition-transform hover:scale-[1.02]">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Despesas Reais</p>
                    <p class="text-2xl font-black text-red-600 italic">R$ {{ number_format($expenseReal, 2, ',', '.') }}</p>
                    <div class="mt-2 pt-2 border-t border-gray-100">
                        <p class="text-[9px] font-bold text-gray-400 uppercase">Total Previsto: R$ {{ number_format($expense, 2, ',', '.') }}</p>
                    </div>
                </div>
                <div class="p-6 rounded-[2rem] shadow-xl {{ $balanceReal >= 0 ? 'bg-emerald-900' : 'bg-red-900' }} text-white transition-all duration-500 hover:scale-[1.02]">
                    <p class="text-[10px] font-black text-white/60 uppercase tracking-widest mb-1">Saldo em Caixa</p>
                    <p class="text-2xl font-black italic">R$ {{ number_format($balanceReal, 2, ',', '.') }}</p>
                    <div class="mt-2 pt-2 border-t border-white/10">
                        <p class="text-[9px] font-bold text-white/50 uppercase">Final do Mês: R$ {{ number_format($balancePrevisto, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="space-y-8">
                    {{-- CARD AI COM ROBOZINHO ALEATÓRIO --}}
                    <div class="bg-emerald-800 p-8 rounded-[3rem] text-white relative overflow-hidden flex flex-col justify-center min-h-[220px]">
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-400">SimplyCa$h AI</span>
                            </div>
                            <h4 class="text-xl font-bold mb-3 italic uppercase tracking-tight">Fala, {{ Auth::user()->name }}!</h4>
                            <p class="text-emerald-100 text-sm leading-relaxed max-w-[75%] font-medium">
                                @if($expense > 0)
                                    Seu gasto está em R$ {{ number_format($expense, 2, ',', '.') }}. Mantenha o foco!
                                @else
                                    Que tal começar lançando sua primeira receita?
                                @endif
                            </p>
                        </div>
                        {{-- Ícone do Robô com posição dinâmica --}}
                        <div class="absolute opacity-20 transition-all duration-1000" style="{{ $estiloRobo }}">
                            <svg class="w-48 h-48 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2a2 2 0 0 1 2 2v1h1a3 3 0 0 1 3 3v2.09l1.45 1.45c.34.34.55.8.55 1.31v1.15c0 .51-.21.97-.55 1.31L18 14.76V18a3 3 0 0 1-3 3H9a3 3 0 0 1-3-3v-3.24l-1.45-1.45c-.34-.34-.55-.8-.55-1.31v-1.15c0-.51.21-.97.55-1.31L6 9.09V7a3 3 0 0 1 3-3h1V4a2 2 0 0 1 2-2m0 11a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m-4-4a1 1 0 1 0 0 2 1 1 0 0 0 0-2m8 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- RESUMO DE PENDÊNCIAS (SUGESTÃO) --}}
                    <div class="bg-white p-8 rounded-[3rem] border-2 border-orange-50 shadow-sm relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xs font-black text-orange-600 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-2 bg-orange-500 rounded-full animate-ping"></span>
                                Pendências do Mês
                            </h3>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Aguardando Pagamento</span>
                        </div>
                        <div class="space-y-4">
                            @php $pendentes = $transactions->where('paid', false)->take(3); @endphp
                            @forelse($pendentes as $p)
                                <div class="flex items-center justify-between p-4 bg-orange-50/50 rounded-2xl border border-orange-100/50 hover:scale-[1.01] transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full {{ $p->type == 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $p->type == 'income' ? 'M5 10l7-7 7 7M12 3v18' : 'M19 14l-7 7-7-7M12 21V3' }}" stroke-width="3"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-gray-800">{{ $p->description }}</p>
                                            <p class="text-[9px] font-bold text-orange-400 uppercase">{{ \Carbon\Carbon::parse($p->date)->format('d/m') }}</p>
                                        </div>
                                    </div>
                                    <p class="text-xs font-black {{ $p->type == 'income' ? 'text-emerald-600' : 'text-red-600' }}">R$ {{ number_format($p->amount, 2, ',', '.') }}</p>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-xs font-bold text-gray-300 italic uppercase">Tudo em dia por aqui! 🙌</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- GRÁFICO --}}
                <div class="bg-white p-8 rounded-[3rem] border border-emerald-100 shadow-sm h-full">
                    <h3 class="text-xs font-bold text-emerald-900 mb-6 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        Distribuição de Gastos
                    </h3>
                    <div class="relative" style="height: 350px; width: 100%;">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- TABELA DE TRANSAÇÕES --}}
            <div class="bg-white rounded-[2.5rem] border border-emerald-100 shadow-sm overflow-hidden">
                <table class="min-w-full" id="transactionsTable">
                    <thead class="bg-emerald-50/50">
                        <tr>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-emerald-800 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-emerald-800 uppercase tracking-widest">Descrição</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-emerald-800 uppercase tracking-widest">Valor</th>
                            <th class="px-8 py-5 text-center text-[10px] font-black text-emerald-800 uppercase tracking-widest">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-emerald-50/20 transition-colors">
                            <td class="px-8 py-4">
                                @if($t->paid)
                                    <span class="bg-emerald-100 text-emerald-700 text-[9px] font-black px-2 py-1 rounded-full uppercase">Efetivado</span>
                                @else
                                    <span class="bg-orange-100 text-orange-600 text-[9px] font-black px-2 py-1 rounded-full uppercase">Pendente</span>
                                @endif
                            </td>
                            <td class="px-8 py-4">
                                <p class="text-sm font-black text-emerald-900">{{ $t->description }}</p>
                                <span class="text-[10px] font-black uppercase" style="color: {{ $t->category->color ?? '#10b981' }}">
                                    {{ $t->category->name ?? 'Geral' }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-sm font-black {{ $t->type == 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $t->type == 'income' ? '+' : '-' }} R$ {{ number_format($t->amount, 2, ',', '.') }}
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center justify-center gap-6">
                                    <button onclick='openModalForEdit(@json($t))' class="text-[10px] font-black text-emerald-600 hover:text-emerald-800 uppercase transition-all">Editar</button>
                                    <form action="{{ route('transactions.destroy', $t) }}" method="POST" onsubmit="return confirm('Confirmar exclusão?')" class="inline-flex">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-black text-gray-300 hover:text-red-500 uppercase transition-all">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-8 py-10 text-center text-gray-400 text-sm italic">Nenhuma transação encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-emerald-900/60 backdrop-blur-sm" onclick="toggleModal()"></div>
            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl p-8 border border-emerald-50">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-xl font-black text-emerald-900 italic uppercase tracking-tighter">NOVO LANÇAMENTO</h3>
                    <button onclick="toggleModal()" class="text-gray-300 hover:text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5"/></svg>
                    </button>
                </div>
                <form id="transactionForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="space-y-5">
                        <div class="flex p-1 bg-gray-100 rounded-2xl border-2 border-emerald-50/50">
                            <label class="flex-1 text-center py-3 rounded-xl cursor-pointer transition-all duration-300 has-[:checked]:bg-emerald-500 has-[:checked]:text-white text-gray-400">
                                <input type="radio" name="type" value="income" id="radioIncome" class="hidden">
                                <span class="font-black text-[10px] tracking-widest uppercase">Ganho</span>
                            </label>
                            <label class="flex-1 text-center py-3 rounded-xl cursor-pointer transition-all duration-300 has-[:checked]:bg-red-600 has-[:checked]:text-white text-gray-400">
                                <input type="radio" name="type" value="expense" id="radioExpense" class="hidden" checked>
                                <span class="font-black text-[10px] tracking-widest uppercase">Gasto</span>
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
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-2xl border border-emerald-50 mt-4">
                            <span class="text-[10px] font-black text-emerald-800 uppercase ml-2">Status da Transação</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="paid" id="modalPaid" value="1" class="sr-only peer" checked onchange="updatePaidLabel()">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                <span class="ml-3 text-[10px] font-black uppercase text-gray-400 peer-checked:text-emerald-600" id="paidText">Efetivado</span>
                            </label>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 text-white h-14 rounded-2xl font-black text-lg hover:bg-emerald-700 transition-all shadow-lg mt-4 uppercase italic">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal() { document.getElementById('transactionModal').classList.toggle('hidden'); }
        
        function updatePaidLabel() {
            const cb = document.getElementById('modalPaid');
            const txt = document.getElementById('paidText');
            txt.innerText = cb.checked ? "Efetivado" : "Pendente";
            txt.className = "ml-3 text-[10px] font-black uppercase " + (cb.checked ? "text-emerald-600" : "text-orange-400");
        }

        function openModalForCreate() {
            const form = document.getElementById('transactionForm');
            form.action = "{{ route('transactions.store') }}";
            document.getElementById('formMethod').value = "POST";
            document.getElementById('modalTitle').innerText = "NOVO LANÇAMENTO";
            form.reset();
            document.getElementById('modalPaid').checked = true;
            updatePaidLabel();
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
            document.getElementById('modalPaid').checked = (t.paid == 1);
            updatePaidLabel();
            document.getElementById(t.type === 'income' ? 'radioIncome' : 'radioExpense').checked = true;
            document.getElementById('transactionModal').classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Busca dinâmica
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
                            hoverOffset: 30,
                            borderWidth: 4,
                            borderColor: '#ffffff',
                            spacing: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 30, font: { weight: 'black', size: 11 } } }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
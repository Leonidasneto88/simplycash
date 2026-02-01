<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="flex flex-col lg:flex-row justify-between items-center mb-8 gap-4">
        <div class="relative w-full lg:w-96">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-emerald-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2"/></svg>
            </span>
            <input type="text" id="tableSearch" placeholder="Pesquisar nas transações..." 
                   class="pl-10 border-emerald-100 rounded-2xl w-full border-2 bg-white h-12 focus:ring-emerald-500">
        </div>
        
        <button onclick="window.print()" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl shadow-lg font-bold flex items-center gap-2 hover:bg-emerald-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/></svg>
            Relatório
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Receitas</p>
            <p class="text-2xl font-black text-emerald-600">R$ {{ number_format($income, 2, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Despesas</p>
            <p class="text-2xl font-black text-red-600">R$ {{ number_format($expense, 2, ',', '.') }}</p>
        </div>
        <div class="p-6 rounded-[2rem] shadow-xl {{ $balance >= 0 ? 'bg-emerald-900' : 'bg-red-900' }} text-white">
            <p class="text-[10px] font-black text-white/60 uppercase tracking-widest">Saldo Atual</p>
            <p class="text-2xl font-black">R$ {{ number_format($balance, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-emerald-800 p-8 rounded-[2rem] text-white relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">SimplyCa$h Insights</span>
                </div>
                <h4 class="text-xl font-bold mb-3 italic">"Fala, {{ Auth::user()->name }}!"</h4>
                <p class="text-emerald-100 text-sm leading-relaxed">
                    @php $topCat = $chartData->sortDesc()->keys()->first() ?? 'nada'; @endphp
                    @if($expense > 0)
                        Notei que <strong class="text-white underline">{{ $topCat }}</strong> está pesando no seu bolso. 
                        Reduzir um pouco aqui pode te ajudar a bater sua meta mais rápido!
                    @else
                        Ainda estou analisando seus dados. Comece a lançar seus gastos para eu te dar dicas!
                    @endif
                </p>
            </div>
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-white/5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a2 2 0 012 2v1h2a2 2 0 012 2v2h1a2 2 0 012 2v4a2 2 0 01-2 2h-1v2a2 2 0 01-2 2h-2v1a2 2 0 01-2 2h-4a2 2 0 01-2-2v-1H7a2 2 0 01-2-2v-2H4a2 2 0 01-2-2v-4a2 2 0 012-2h1V7a2 2 0 012-2h2V4a2 2 0 012-2h4zm0 2h-4v1h4V4zM7 7v10h10V7H7z"/></svg>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-emerald-100">
            <h3 class="text-xs font-bold text-emerald-900 mb-6 uppercase tracking-widest">Análise de Gastos</h3>
            <div style="height: 250px;">
                <canvas id="expenseChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-emerald-100 overflow-hidden">
        <table class="min-w-full" id="transactionsTable">
            <thead class="bg-emerald-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-emerald-800 uppercase">Descrição</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-emerald-800 uppercase">Valor</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-emerald-800 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-emerald-50">
                @foreach($transactions as $t)
                <tr class="hover:bg-emerald-50/20 transition">
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-emerald-900">{{ $t->description }}</p>
                        <span class="text-[10px] text-emerald-400 font-bold uppercase">{{ $t->category }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm font-black {{ $t->type == 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                        R$ {{ number_format($t->amount, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('transactions.destroy', $t) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-gray-300 hover:text-red-500 transition">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica da Busca
            document.getElementById('tableSearch').addEventListener('keyup', function() {
                let term = this.value.toLowerCase();
                document.querySelectorAll("#transactionsTable tbody tr").forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(term) ? "" : "none";
                });
            });

            // Lógica do Gráfico
            const ctx = document.getElementById('expenseChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chartData->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($chartData->values()) !!},
                        backgroundColor: ['#059669', '#10b981', '#34d399', '#6ee7b7', '#a7f3d0'],
                        borderWidth: 0
                    }]
                },
                options: { cutout: '75%', plugins: { legend: { position: 'bottom' } } }
            });
        });
    </script>
</x-app-layout>
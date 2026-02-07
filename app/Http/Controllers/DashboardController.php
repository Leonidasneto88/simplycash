<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        // Filtros de Data: Captura do Request ou assume o mês/ano atual
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // --- EXPLICAÇÃO DA MUDANÇA AQUI ---
        // Alteramos a consulta para buscar:
        // 1. O que pertence ao mês selecionado.
        // 2. OU o que é de meses anteriores e ainda está como 'pendente' (paid = false).
        
        $transactions = $user->transactions()
            ->with('category') 
            ->where(function($query) use ($month, $year) {
                // Condição 1: Mesma data do filtro
                $query->whereMonth('date', $month)
                      ->whereYear('date', $year)
                      // Condição 2: Pendências de meses anteriores
                      ->orWhere(function($q) use ($month, $year) {
                          $inicioDoMesFiltro = Carbon::create($year, $month, 1)->startOfMonth();
                          $q->where('date', '<', $inicioDoMesFiltro)
                            ->where('paid', false);
                      });
            })
            ->orderBy('date', 'desc')
            ->get();

        // --- O RESTANTE DOS CÁLCULOS CONTINUA IGUAL ---
        // Como agora a coleção $transactions inclui as pendências antigas,
        // todos os cálculos abaixo (Real e Previsto) já considerarão esses valores automaticamente.

        // 1. Valores REAIS (Apenas o que já foi Efetivado/Pago)
        $incomeReal = $transactions->where('type', 'income')->where('paid', true)->sum('amount');
        $expenseReal = $transactions->where('type', 'expense')->where('paid', true)->sum('amount');
        $balanceReal = $incomeReal - $expenseReal;

        // 2. Valores TOTAIS (Tudo o que está lançado: Efetivado + Pendente)
        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $balancePrevisto = $income - $expense;

        // --- GRÁFICO ---
        $chartData = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($group) {
                $transaction = $group->first();
                $category = $transaction->relationLoaded('category') ? $transaction->getRelation('category') : null;

                return [
                    'label' => $category ? $category->name : 'Geral',
                    'value' => (float) $group->sum('amount'),
                    'color' => $category ? $category->color : '#10b981',
                ];
            })->values();

        $categories = $user->categories()->orderBy('name')->get();    

        return view('dashboard', compact(
            'transactions', 
            'income',        
            'expense',       
            'incomeReal',    
            'expenseReal',   
            'balanceReal',   
            'balancePrevisto', 
            'chartData', 
            'categories', 
            'month', 
            'year'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // Trazemos o relacionamento 'category' para otimizar a consulta
        $transactions = $user->transactions()
            ->with('category') 
            ->orderBy('date', 'desc')
            ->get();

        // Cálculos dos cards
        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        // GRÁFICO: Ajuste Técnico para resolver o conflito String vs Objeto
        $chartData = $transactions->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($group) {
                $transaction = $group->first();
                
                // [FIX SENIOR] Usamos getRelation para garantir que estamos pegando o Objeto Categoria
                // e não o texto da coluna antiga 'category' que pode existir no banco.
                $category = $transaction->relationLoaded('category') ? $transaction->getRelation('category') : null;

                return [
                    'label' => $category ? $category->name : 'Geral',
                    'value' => (float) $group->sum('amount'),
                    'color' => $category ? $category->color : '#10b981',
                ];
            })->values();

        $categories = $user->categories()->orderBy('name')->get();    

        return view('dashboard', compact('transactions', 'income', 'expense', 'balance', 'chartData', 'categories'));
    }
}
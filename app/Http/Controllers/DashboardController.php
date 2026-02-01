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

        // Pegamos as transações do banco através do relacionamento
        $transactions = $user->transactions()->orderBy('date', 'desc')->get();

        // Cálculos para os cards de resumo
        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        // Agrupamento para o Gráfico de Pizza (apenas despesas)
        $chartData = $transactions->where('type', 'expense')
            ->groupBy('category')
            ->map(fn($group) => $group->sum('amount'));

        return view('dashboard', compact('transactions', 'income', 'expense', 'balance', 'chartData'));
    }
}
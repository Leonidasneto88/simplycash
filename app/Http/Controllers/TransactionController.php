<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; // <-- ESSA LINHA É FUNDAMENTAL

class TransactionController extends Controller
{
    /**
     * Salva uma nova transação no banco de dados.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'type'        => 'required|in:income,expense',
            'category'    => 'required|string',
            'date'        => 'required|date',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->transactions()->create($request->all());

        return redirect()->route('dashboard')->with('success', 'Transação adicionada com sucesso!');
    }

    /**
     * Remove uma transação do banco de dados.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        // Verificação de segurança: o usuário só deleta o que pertence a ele
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('dashboard')->with('success', 'Transação removida!');
    }
}
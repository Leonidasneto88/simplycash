<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'type'        => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id', // Agora é obrigatório
            'date'        => 'required|date',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $user->transactions()->create($request->all());

        return redirect()->route('dashboard')->with('success', 'Lançamento processado!');
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        // Segurança: Impede que um usuário edite a transação de outro
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'type'        => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
        ]);

        // Mapeamento explícito para garantir a persistência da categoria
        $transaction->update([
            'description' => $request->description,
            'amount'      => $request->amount,
            'type'        => $request->type,
            'category_id' => $request->category_id,
            'date'        => $request->date,
        ]);

        return redirect()->route('dashboard')->with('success', 'Lançamento atualizado com sucesso!');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        $transaction->delete();
        return redirect()->route('dashboard')->with('success', 'Transação removida!');
    }
}
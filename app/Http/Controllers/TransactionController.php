<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // 1. Mudança: Adicionamos o 'paid' na validação como opcional (boolean)
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'type'        => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
            'paid'        => 'nullable|boolean', // Aceita se vier, ignora se não
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // 2. Mudança: Em vez de $request->all(), usamos os dados validados + o status do checkbox
        // Isso evita que alguém mal-intencionado tente injetar campos extras no seu banco.
        $validated['paid'] = $request->has('paid'); 

        $user->transactions()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Lançamento realizado com sucesso!');
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'type'        => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
            'paid'        => 'nullable|boolean',
        ]);

        // 3. Mudança: Atualizamos usando os dados validados + o status do checkbox
        $validated['paid'] = $request->has('paid');
        $transaction->update($validated);

        return redirect()->route('dashboard')->with('success', 'Lançamento atualizado!');
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
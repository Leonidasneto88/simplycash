<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Buscamos e separamos para a View
        $incomeCategories = $user->categories()->where('type', 'income')->get();
        $expenseCategories = $user->categories()->where('type', 'expense')->get();
        $allCategories = $user->categories()->get();
    
        return view('categories.index', [
            'income' => $incomeCategories,
            'expense' => $expenseCategories,
            'categories' => $allCategories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'color' => 'required|string|max:7',
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => $request->type,
            'color' => $request->color,
            'icon' => 'tag',
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'color' => 'required|string|max:7',
        ]);

        $category->update($request->only(['name', 'type', 'color']));

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada!');
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria excluída!');
    }
}
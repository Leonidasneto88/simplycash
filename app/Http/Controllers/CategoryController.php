<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        // Busca categorias do usuário logado
        $categories = Category::where('user_id', Auth::id())->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return redirect()->back()->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy(Category $category)
    {
        if ($category->user_id === Auth::id()) {
            $category->delete();
        }
        return redirect()->back()->with('success', 'Categoria removida!');
    }
}
<div class="bg-white rounded-[2.5rem] p-6 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 relative group transition-all hover:shadow-xl hover:-translate-y-2">
    <div class="absolute top-6 right-6 w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $category->color }};"></div>

    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gray-50 text-emerald-600 shadow-inner">
            <span class="text-xs font-black uppercase">{{ substr($category->icon, 0, 3) }}</span>
        </div>
        <div>
            <h4 class="text-lg font-black text-gray-800 tracking-tight leading-tight">{{ $category->name }}</h4>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ativa</p>
        </div>
    </div>

    <div class="flex gap-2 mt-4 pt-4 border-t border-gray-50">
        <a href="{{ route('categories.edit', $category) }}" class="flex-1 bg-gray-50 hover:bg-emerald-50 text-gray-400 hover:text-emerald-600 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all text-center">
            Editar
        </a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-1">
            @csrf
            @method('DELETE')
            <button class="w-full bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-600 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                Excluir
            </button>
        </form>
    </div>
</div>
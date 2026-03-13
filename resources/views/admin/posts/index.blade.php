@extends('layouts.admin')

@section('content')
<!-- Header Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Gestion des Articles</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Gérez, éditez et publiez votre contenu rédactionnel en toute simplicité.</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/25 hover:bg-primary/90 transition-all active:scale-95">
        <span class="material-symbols-outlined">add</span>
        Ajouter un Article
    </a>
</div>

@if(session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 px-4 py-3 rounded mb-8">
        {{ session('success') }}
    </div>
@endif

@if($posts->isEmpty())
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-primary/10 p-8 text-center">
        <p class="text-slate-500 dark:text-slate-400">Aucun article pour le moment.</p>
    </div>
@else
    <!-- Content Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-primary/10 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-primary/10">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Image</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Titre</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Contenu</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    @foreach($posts as $post)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="size-12 rounded-lg bg-slate-100 dark:bg-slate-800 overflow-hidden border border-primary/5">
                                    @if($post->image)
                                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <span class="material-symbols-outlined">image</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900 dark:text-white truncate max-w-[200px]">{{ $post->title }}</div>
                                <div class="text-[10px] text-primary font-bold uppercase mt-0.5 tracking-tighter">Article</div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-1 max-w-[300px]">
                                    {{ Str::limit($post->content, 100) }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Modifier">
                                        <span class="material-symbols-outlined text-xl">edit_note</span>
                                    </a>
                                    <a href="{{ route('posts.show', $post->id) }}" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors" title="Aperçu">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-primary/10 flex items-center justify-between">
            <p class="text-sm text-slate-500 dark:text-slate-400">Affichage de 1 à {{ $posts->count() }} sur {{ $posts->count() }} articles</p>
            <div class="flex items-center gap-1">
                <button class="size-8 flex items-center justify-center rounded-lg border border-primary/10 text-slate-400 hover:bg-white dark:hover:bg-slate-800 transition-colors disabled:opacity-50" disabled="">
                    <span class="material-symbols-outlined text-lg">chevron_left</span>
                </button>
                <button class="size-8 flex items-center justify-center rounded-lg bg-primary text-white font-bold text-sm">1</button>
                <button class="size-8 flex items-center justify-center rounded-lg border border-primary/10 text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 transition-colors font-medium text-sm">2</button>
                <span class="px-2 text-slate-400">...</span>
                <button class="size-8 flex items-center justify-center rounded-lg border border-primary/10 text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 transition-colors font-medium text-sm">3</button>
                <button class="size-8 flex items-center justify-center rounded-lg border border-primary/10 text-slate-400 hover:bg-white dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endif

<!-- Secondary Info Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-primary/10 flex items-center gap-4">
        <div class="size-12 rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 flex items-center justify-center">
            <span class="material-symbols-outlined">trending_up</span>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Articles</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $posts->count() }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-primary/10 flex items-center gap-4">
        <div class="size-12 rounded-xl bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 flex items-center justify-center">
            <span class="material-symbols-outlined">check_circle</span>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Articles Publiés</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $posts->count() }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-primary/10 flex items-center gap-4">
        <div class="size-12 rounded-xl bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 flex items-center justify-center">
            <span class="material-symbols-outlined">pending</span>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">En Révision</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">0</p>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<!-- Title & Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Gestion des Utilisateurs</h2>
        <p class="text-slate-500 mt-1">Gérez les accès, les rôles et les permissions de votre équipe.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg font-semibold flex items-center justify-center gap-2 hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-lg">person_add</span>
        <span>Ajouter un Utilisateur</span>
    </a>
</div>

@if(session('success'))
    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 px-4 py-3 rounded mb-8">
        {{ session('success') }}
    </div>
@endif

@if($users->isEmpty())
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-primary/5 shadow-sm p-8 text-center">
        <p class="text-slate-500 dark:text-slate-400">Aucun utilisateur pour le moment.</p>
    </div>
@else
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-primary/5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-slate-500 text-sm font-medium">Total Utilisateurs</span>
                <span class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">group</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold">{{ $users->count() }}</span>
                <span class="text-emerald-500 text-xs font-semibold flex items-center">
                    <span class="material-symbols-outlined text-xs">trending_up</span> 12%
                </span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-primary/5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-slate-500 text-sm font-medium">Rôles Admin</span>
                <span class="size-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">verified_user</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold">{{ $users->where('role', 'admin')->count() }}</span>
                <span class="text-slate-400 text-xs font-semibold">0% cette semaine</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-primary/5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-slate-500 text-sm font-medium">Utilisateurs Actifs</span>
                <span class="size-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">bolt</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold">{{ $users->count() }}</span>
                <span class="text-emerald-500 text-xs font-semibold flex items-center">
                    <span class="material-symbols-outlined text-xs">trending_up</span> 5%
                </span>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex border-b border-primary/10 mb-6 overflow-x-auto whitespace-nowrap scrollbar-hide">
        <button class="px-6 py-3 border-b-2 border-primary text-primary font-semibold text-sm">Tous les comptes</button>
        <button class="px-6 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 font-medium text-sm">Administrateurs</button>
        <button class="px-6 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 font-medium text-sm">Utilisateurs</button>
        <button class="px-6 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 font-medium text-sm">Invités</button>
        <button class="px-6 py-3 border-b-2 border-transparent text-slate-500 hover:text-slate-700 font-medium text-sm">Désactivés</button>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-primary/5 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-primary/5">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nom d'utilisateur</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mot de passe</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    @foreach($users as $user)
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-full bg-slate-200 flex items-center justify-center overflow-hidden text-slate-400">
                                        <span class="material-symbols-outlined text-xl">account_circle</span>
                                    </div>
                                    <span class="font-semibold text-sm">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-slate-400">••••••••••••</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'admin')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-primary/10 text-primary">Administrateur</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">Utilisateur</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-1.5 text-slate-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            <span class="material-symbols-outlined text-lg">delete</span>
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
        <div class="px-6 py-4 border-t border-primary/5 flex items-center justify-between">
            <p class="text-sm text-slate-500">Affichage de <span class="font-semibold text-slate-900 dark:text-slate-100">1</span> à <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $users->count() }}</span> sur <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $users->count() }}</span> utilisateurs</p>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 rounded border border-primary/10 hover:bg-primary/5 text-slate-500 disabled:opacity-50 transition-all">
                    <span class="material-symbols-outlined text-sm leading-none">chevron_left</span>
                </button>
                <button class="px-3 py-1.5 rounded bg-primary text-white text-sm font-bold shadow-md shadow-primary/20">1</button>
                <button class="px-3 py-1.5 rounded border border-primary/10 hover:bg-primary/5 text-slate-500 transition-all text-sm font-medium">2</button>
                <button class="px-3 py-1.5 rounded border border-primary/10 hover:bg-primary/5 text-slate-500 transition-all">
                    <span class="material-symbols-outlined text-sm leading-none">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endif
@endsection

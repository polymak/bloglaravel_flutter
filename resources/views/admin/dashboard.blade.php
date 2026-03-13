@extends('layouts.admin')

@section('content')
<!-- Title Section -->
<div class="flex flex-col gap-1">
    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Tableau de Bord Admin</h1>
    <p class="text-slate-500 dark:text-slate-400">Bienvenue. Voici un aperçu de votre activité aujourd'hui.</p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="group relative overflow-hidden bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 hover:shadow-lg hover:border-primary/50 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Articles</p>
                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ $postsCount }}</p>
            </div>
            <div class="p-3 bg-primary/10 text-primary rounded-xl group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                <span class="material-symbols-outlined">description</span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-emerald-500">
            <span class="material-symbols-outlined text-sm">trending_up</span>
            <span>+12% vs mois dernier</span>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-9xl">description</span>
        </div>
    </div>
    
    <div class="group relative overflow-hidden bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 hover:shadow-lg hover:border-primary/50 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Utilisateurs</p>
                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ $usersCount }}</p>
            </div>
            <div class="p-3 bg-purple-500/10 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                <span class="material-symbols-outlined">person</span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-emerald-500">
            <span class="material-symbols-outlined text-sm">trending_up</span>
            <span>Stabilité maintenue</span>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-9xl">person</span>
        </div>
    </div>
    
    <div class="group relative overflow-hidden bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 hover:shadow-lg hover:border-primary/50 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Admins</p>
                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ $adminsCount }}</p>
            </div>
            <div class="p-3 bg-amber-500/10 text-amber-600 rounded-xl group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                <span class="material-symbols-outlined">admin_panel_settings</span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-xs font-semibold text-slate-400">
            <span class="material-symbols-outlined text-sm">lock</span>
            <span>Privilèges élevés</span>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-5 pointer-events-none group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-9xl">admin_panel_settings</span>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="space-y-4">
    <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">bolt</span>
        Actions Rapides
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ url('/admin/posts') }}" class="flex items-center gap-3 p-4 bg-primary text-white rounded-xl shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5 transition-all group">
            <div class="p-2 bg-white/20 rounded-lg group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined">edit_note</span>
            </div>
            <span class="font-bold text-sm">Gérer les Articles</span>
        </a>
        <a href="{{ url('/admin/users') }}" class="flex items-center gap-3 p-4 bg-slate-900 dark:bg-slate-800 text-white rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all group">
            <div class="p-2 bg-white/10 rounded-lg group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined">manage_accounts</span>
            </div>
            <span class="font-bold text-sm">Gérer les Utilisateurs</span>
        </a>
        <button class="flex items-center gap-3 p-4 bg-white dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm hover:shadow-md hover:border-primary/30 hover:-translate-y-0.5 transition-all group">
            <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined">analytics</span>
            </div>
            <span class="font-bold text-sm">Voir Statistiques</span>
        </button>
        <button class="flex items-center gap-3 p-4 bg-white dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm hover:shadow-md hover:border-primary/30 hover:-translate-y-0.5 transition-all group">
            <div class="p-2 bg-slate-100 dark:bg-slate-800 rounded-lg group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined">mail</span>
            </div>
            <span class="font-bold text-sm">Messagerie</span>
        </button>
    </div>
</div>

<!-- Simple Table Example for data-driven feel -->
<div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <h3 class="font-bold">Activités Récentes</h3>
        <a href="#" class="text-primary text-sm font-semibold hover:underline">Voir tout</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <th class="px-6 py-4">Utilisateur</th>
                    <th class="px-6 py-4">Action</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <tr>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="size-8 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">Connexion au tableau de bord</td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ now()->format('Aujourd\'hui, H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded text-[10px] font-bold uppercase">Actif</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="size-8 rounded-full bg-purple-500/20 text-purple-600 flex items-center justify-center font-bold text-xs">AD</div>
                        <span class="text-sm font-semibold">Admin</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">Mise à jour système</td>
                    <td class="px-6 py-4 text-sm text-slate-500">Hier, 11:15</td>
                    <td class="px-6 py-4 text-right">
                        <span class="px-2 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded text-[10px] font-bold uppercase">En cours</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

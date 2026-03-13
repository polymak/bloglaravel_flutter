@extends('layouts.admin')

@section('content')
<main class="flex flex-1 justify-center py-10 px-4 md:px-10">
    <div class="layout-content-container flex flex-col max-w-[800px] flex-1">
        <div class="flex flex-col gap-2 mb-8">
            <div class="flex items-center gap-2 text-primary font-semibold text-sm uppercase tracking-wider">
                <span class="material-symbols-outlined text-sm">person_add</span>
                <span>Gestion des Utilisateurs</span>
            </div>
            <h1 class="text-slate-900 dark:text-white text-4xl font-black leading-tight tracking-tight">
                {{ $user ? 'Modifier l\'Utilisateur' : 'Ajouter un Utilisateur' }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-base font-normal">
                {{ $user ? 'Mettez à jour les informations de l\'utilisateur' : 'Créez un nouvel utilisateur pour votre plateforme' }}
            </p>
        </div>
        
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="p-6 md:p-8 space-y-6">
                <form action="{{ $user ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if($user)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Nom d'utilisateur</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                                <input 
                                    class="w-full pl-10 rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary h-12 transition-all @error('name') border-red-500 @enderror"
                                    id="name" 
                                    name="name"
                                    placeholder="Entrez le nom complet" 
                                    type="text" 
                                    value="{{ old('name', $user->name ?? '') }}"
                                    required
                                >
                            </div>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Adresse Email</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                                <input 
                                    class="w-full pl-10 rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary h-12 transition-all @error('email') border-red-500 @enderror"
                                    id="email" 
                                    name="email"
                                    placeholder="exemple@domaine.com" 
                                    type="email" 
                                    value="{{ old('email', $user->email ?? '') }}"
                                    required
                                >
                            </div>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Mot de passe</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                                <input 
                                    class="w-full pl-10 rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary h-12 transition-all @error('password') border-red-500 @enderror"
                                    id="password" 
                                    name="password"
                                    placeholder="{{ $user ? 'Laissez vide pour conserver le mot de passe actuel' : 'Entrez un mot de passe' }}" 
                                    type="password"
                                    {{ $user ? '' : 'required' }}
                                >
                            </div>
                            @if($user)
                                <p class="text-slate-400 text-xs mt-1">Laissez vide pour conserver le mot de passe actuel</p>
                            @endif
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Confirmation du mot de passe</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">lock_reset</span>
                                <input 
                                    class="w-full pl-10 rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary h-12 transition-all @error('password_confirmation') border-red-500 @enderror"
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    placeholder="Confirmez le mot de passe" 
                                    type="password"
                                >
                            </div>
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Rôle</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">badge</span>
                            <select 
                                class="w-full pl-10 rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary h-12 transition-all appearance-none @error('role') border-red-500 @enderror"
                                id="role" 
                                name="role"
                                required
                            >
                                <option disabled {{ !$user ? 'selected' : '' }}>Choisissez un rôle</option>
                                <option value="user" {{ (old('role', $user->role ?? '') === 'user') ? 'selected' : '' }}>Utilisateur</option>
                                <option value="admin" {{ (old('role', $user->role ?? '') === 'admin') ? 'selected' : '' }}>Administrateur</option>
                            </select>
                        </div>
                        <p class="text-slate-400 text-xs mt-1">Sélectionnez le niveau d'accès que cet utilisateur aura dans le portail.</p>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 px-6 md:px-8 py-5 border-t border-slate-200 dark:border-slate-800 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold text-sm hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-8 py-2.5 rounded-lg bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition-colors shadow-md shadow-primary/20 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                        {{ $user ? 'Mettre à jour' : 'Créer l\'utilisateur' }}
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 opacity-60">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-primary">security</span>
                <div>
                    <h4 class="text-sm font-bold">Accès Sécurisé</h4>
                    <p class="text-xs">Chiffrement des mots de passe actif.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-primary">history</span>
                <div>
                    <h4 class="text-sm font-bold">Journal d'Audit</h4>
                    <p class="text-xs">Toutes les actions sont enregistrées.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-primary">notifications_active</span>
                <div>
                    <h4 class="text-sm font-bold">Invitation Email</h4>
                    <p class="text-xs">Invitation envoyée à la création.</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

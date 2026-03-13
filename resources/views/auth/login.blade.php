@extends('layouts.app')

@section('content')
<main class="flex-1 flex items-center justify-center p-6 sm:p-12 relative overflow-hidden">
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-primary/10 rounded-full blur-[120px]"></div>
    <div class="w-full max-w-[480px] z-10">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl shadow-primary/5 overflow-hidden">
            <div class="p-8 pb-4">
                <div class="flex flex-col gap-2 mb-8">
                    <h1 class="text-slate-900 dark:text-slate-100 text-4xl font-black leading-tight tracking-tight">Admin Login</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base font-normal">Connectez-vous à votre tableau de bord</p>
                </div>
                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
                    @csrf
                    
                    <div class="flex flex-col gap-2">
                        <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold tracking-wide" for="email">
                            Email Address
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </div>
                            <input 
                                class="flex w-full pl-11 rounded-lg text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 h-14 placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-base @error('email') border-red-500 @enderror"
                                id="email" 
                                name="email"
                                placeholder="Enter your email" 
                                type="email" 
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                            >
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold tracking-wide" for="password">
                                Password
                            </label>
                            <a class="text-primary text-xs font-semibold hover:underline" href="#">Forgot password?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">lock</span>
                            </div>
                            <input 
                                class="flex w-full pl-11 pr-12 rounded-lg text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 h-14 placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-base @error('password') border-red-500 @enderror"
                                id="password" 
                                name="password"
                                placeholder="Enter your password" 
                                type="password" 
                                required
                                autocomplete="current-password"
                            >
                            <button class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors" type="button" onclick="togglePassword()">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center gap-2 mt-1">
                        <input class="w-4 h-4 text-primary bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded focus:ring-primary" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <label class="text-slate-600 dark:text-slate-400 text-sm" for="remember">Remember me on this device</label>
                    </div>
                    
                    <button class="flex w-full cursor-pointer items-center justify-center rounded-lg h-14 px-6 bg-primary text-white text-base font-bold leading-normal tracking-wide hover:bg-primary/90 shadow-lg shadow-primary/20 active:scale-[0.98] transition-all mt-2" type="submit">
                        <span>Login</span>
                        <span class="material-symbols-outlined ml-2 text-lg">login</span>
                    </button>
                </form>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-slate-500 dark:text-slate-400 text-sm">
                    Don't have an admin account? <a class="text-primary font-semibold hover:underline" href="{{ route('home') }}">Contact system owner</a>
                </p>
            </div>
        </div>
        <div class="mt-12 flex flex-col items-center gap-6 opacity-60">
            <div class="flex gap-8">
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="material-symbols-outlined text-sm">verified_user</span>
                    <span class="text-xs uppercase tracking-widest font-bold">Secure SSL</span>
                </div>
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="material-symbols-outlined text-sm">encrypted</span>
                    <span class="text-xs uppercase tracking-widest font-bold">2FA Ready</span>
                </div>
            </div>
            <p class="text-slate-400 text-xs font-medium">© {{ date('Y') }} Admin Panel Dashboard. All rights reserved.</p>
        </div>
    </div>
</main>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = event.target;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
@endsection

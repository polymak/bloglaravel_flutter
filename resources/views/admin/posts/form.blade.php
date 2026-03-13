@extends('layouts.admin')

@section('content')
<main class="w-full max-w-3xl px-6 py-10">
    <form action="{{ $post ? route('admin.posts.update', $post->id) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($post)
            @method('PUT')
        @endif
        
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="p-8 space-y-8">
                <!-- Article Title Section -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300" for="title">
                        {{ $post ? 'Modifier l\'Article' : 'Ajouter un Article' }}
                    </label>
                    <input 
                        class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary focus:border-primary transition-all p-3.5 text-base @error('title') border-red-500 @enderror"
                        id="title" 
                        name="title"
                        placeholder="{{ $post ? 'Modifier le titre de l\'article' : 'Entrez le titre de votre article' }}" 
                        type="text" 
                        value="{{ old('title', $post->title ?? '') }}"
                        required
                    >
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Content Section -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300" for="content">
                        Contenu
                    </label>
                    <textarea 
                        class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-primary focus:border-primary transition-all p-3.5 text-base resize-none @error('content') border-red-500 @enderror"
                        id="content" 
                        name="content"
                        placeholder="Rédigez le contenu de votre article..." 
                        rows="10"
                        required
                    >{{ old('content', $post->content ?? '') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Image Upload Section -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Image
                    </label>
                    <div class="relative group">
                        <div class="flex flex-col items-center justify-center w-full min-h-[220px] rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-primary/50 transition-all cursor-pointer">
                            <div class="flex flex-col items-center gap-4 text-center px-6">
                                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                                </div>
                                <div>
                                    <p class="text-base font-semibold text-slate-900 dark:text-slate-100">Cliquer pour uploader ou glisser-déposer</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Recommandé: 1200x630px (PNG, JPG, ou GIF jusqu'à 5MB)</p>
                                </div>
                                <input 
                                    type="file" 
                                    name="image" 
                                    id="image" 
                                    class="hidden"
                                    accept="image/*"
                                    onchange="previewImage(event)"
                                >
                                <button type="button" onclick="document.getElementById('image').click()" class="mt-2 px-5 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors shadow-sm">
                                    Sélectionner Image
                                </button>
                            </div>
                        </div>
                        
                        <!-- Preview Image -->
                        <div id="image-preview-container" class="mt-4 hidden">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Aperçu de l'image</label>
                            <div class="mt-2">
                                <img id="image-preview" src="" alt="Aperçu de l'image" class="w-48 h-32 object-cover rounded-lg shadow-md">
                            </div>
                        </div>
                        
                        @if($post && $post->image)
                            <div class="mt-4">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Image actuelle</label>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-48 h-32 object-cover rounded-lg shadow-md">
                                </div>
                            </div>
                        @endif
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <!-- Footer Actions -->
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-4">
                <a href="{{ route('admin.posts.index') }}" class="px-6 py-2.5 rounded-lg text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-all shadow-md shadow-primary/20 flex items-center gap-2">
                    <span>{{ $post ? 'Mettre à jour' : 'Créer l\'article' }}</span>
                    <span class="material-symbols-outlined text-sm">send</span>
                </button>
            </div>
        </div>
    </form>
    
    <!-- Optional: SEO Tip Card -->
    <div class="mt-8 p-5 bg-primary/5 border border-primary/10 rounded-xl flex items-start gap-4">
        <span class="material-symbols-outlined text-primary mt-0.5">lightbulb</span>
        <div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100">Conseil Pro</h4>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">
                Les articles avec des images de couverture de haute qualité reçoivent jusqu'à 40% d'engagement en plus. Assurez-vous que votre titre contient entre 40 et 60 caractères pour un meilleur référencement SEO.
            </p>
        </div>
    </div>
    
    <script>
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</main>
@endsection

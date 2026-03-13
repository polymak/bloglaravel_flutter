@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="mb-12">
        <div class="relative overflow-hidden rounded-xl aspect-[21/9] flex items-end group cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-600 via-slate-700 to-slate-800 dark:from-slate-800 dark:via-slate-900 dark:to-black transition-transform duration-700 group-hover:scale-105"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent opacity-90"></div>
            <div class="relative p-8 md:p-12 w-full max-w-3xl">
                <span class="bg-primary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest text-white mb-4 inline-block">Derniers Articles</span>
                <h2 class="text-4xl md:text-6xl font-bold leading-tight mb-4 text-white">Explorez Nos Dernières Publications</h2>
                <p class="text-slate-200 text-lg mb-6 line-clamp-2">Découvrez nos articles les plus récents et restez informé de l'actualité.</p>
                <a href="{{ url('/') }}" class="bg-primary hover:bg-primary/90 text-white font-bold py-3 px-8 rounded-lg transition-all flex items-center gap-2">
                    Voir Tous les Articles <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($posts->isEmpty())
                <div class="bg-white/80 dark:bg-slate-900/80 rounded-2xl p-8 text-center border border-slate-200 dark:border-slate-700 backdrop-blur-sm">
                    <div class="text-slate-600 dark:text-slate-400 text-lg font-medium">Aucun article pour le moment.</div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <article class="group cursor-pointer" onclick="window.location.href='{{ route('posts.show', $post->id) }}'">
                            <div class="relative aspect-video rounded-xl overflow-hidden mb-4 bg-slate-800">
                                @if($post->image)
                                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image: url('{{ asset('storage/' . $post->image) }}')"></div>
                                @else
                                    <div class="absolute inset-0 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                        <span class="text-slate-400 dark:text-slate-500">No image</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 text-primary text-xs font-bold uppercase tracking-wider mb-2">
                                    <span>Article</span>
                                    <span class="size-1 rounded-full bg-slate-500"></span>
                                    <span class="text-slate-500 font-medium">{{ ceil(str_word_count($post->content) / 200) }} min</span>
                                </div>
                                <h4 class="text-xl font-bold mb-2 group-hover:text-primary transition-colors">
                                    {{ $post->title }}
                                </h4>
                                <p class="text-slate-400 text-sm leading-relaxed line-clamp-2">
                                    {{ Str::limit($post->content, 150) }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

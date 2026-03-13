@extends('layouts.app')

@section('content')
    <div class="max-w-[900px] mx-auto px-6 py-12 lg:py-20">
        <!-- Article Header -->
        <div class="mb-12">
            <div class="flex items-center gap-2 mb-6">
                <span class="px-3 py-1 text-[10px] font-bold tracking-[0.2em] uppercase bg-primary/10 text-primary border border-primary/20 rounded">Article</span>
                <span class="text-slate-500 text-xs font-medium">{{ ceil(str_word_count($post->content) / 200) }} MIN READ</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold leading-[1.1] mb-8 tracking-tight text-slate-900 dark:text-slate-50">
                {{ $post->title }}
            </h1>
            <p class="text-xl md:text-2xl text-slate-600 dark:text-slate-400 font-light leading-relaxed">
                Publié le {{ $post->created_at->format('d M Y') }}
            </p>
        </div>

        <!-- Article Image -->
        @if($post->image)
            <div class="relative w-full aspect-video rounded-2xl overflow-hidden mb-12 shadow-2xl shadow-primary/5">
                <div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('{{ asset('storage/' . $post->image) }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-background-dark/80 to-transparent"></div>
            </div>
        @endif

        <!-- Article Content -->
        <div class="flex flex-col gap-8 text-lg leading-relaxed text-slate-700 dark:text-slate-300">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- Article Footer -->
        <div class="mt-20 pt-10 border-t border-slate-200 dark:border-slate-800">
            <div class="flex items-center justify-between flex-wrap gap-6">
                <div class="flex items-center gap-4">
                    <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-xl">thumb_up</span>
                        <span class="text-sm font-bold">J'aime</span>
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-xl">share</span>
                        <span class="text-sm font-bold">Partager</span>
                    </button>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded text-xs font-medium">#article</span>
                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded text-xs font-medium">#blog</span>
                </div>
            </div>
        </div>
    </div>
@endsection

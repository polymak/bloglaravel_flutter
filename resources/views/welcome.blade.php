@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Bienvenue sur BlogLaravel</h1>
                <p class="text-gray-600 mb-8">Un blog simple et élégant créé avec Laravel, TailwindCSS et un design inspiré de X.com et Instagram.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-blue-900 mb-2">Design Moderne</h2>
                        <p class="text-blue-600 text-sm">Interface clean et responsive avec animations hover</p>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-green-900 mb-2">Backend Admin</h2>
                        <p class="text-green-600 text-sm">Gestion complète des articles et utilisateurs</p>
                    </div>
                    
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-purple-900 mb-2">Upload d'Images</h2>
                        <p class="text-purple-600 text-sm">Système d'upload d'images pour les articles</p>
                    </div>
                </div>
                
                <div class="mt-8 text-center">
                    <a href="{{ url('/login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                        Se Connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
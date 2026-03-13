import React, { useEffect } from 'react'
import { useParams } from 'react-router-dom'
import { usePostStore } from '../store/postStore'

const PostDetail: React.FC = () => {
  const { id } = useParams<{ id: string }>()
  const { currentPost, fetchPost, isLoading } = usePostStore()

  useEffect(() => {
    if (id) {
      fetchPost(parseInt(id))
    }
  }, [id, fetchPost])

  if (isLoading) {
    return (
      <div className="flex justify-center items-center py-12">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
      </div>
    )
  }

  if (!currentPost) {
    return (
      <div className="text-center py-12">
        <h2 className="text-2xl font-bold text-slate-900 dark:text-white">Article non trouvé</h2>
        <p className="text-slate-500 dark:text-slate-400 mt-2">L'article que vous recherchez n'existe pas.</p>
      </div>
    )
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    })
  }

  const readingTime = Math.ceil(currentPost.content.split(' ').length / 200)

  return (
    <div className="max-w-[900px] mx-auto px-6 py-12 lg:py-20">
      {/* Article Header */}
      <div className="mb-12">
        <div className="flex items-center gap-2 mb-6">
          <span className="px-3 py-1 text-[10px] font-bold tracking-[0.2em] uppercase bg-primary/10 text-primary border border-primary/20 rounded">Article</span>
          <span className="text-slate-500 text-xs font-medium">{readingTime} MIN READ</span>
        </div>
        <h1 className="text-4xl md:text-6xl font-bold leading-[1.1] mb-8 tracking-tight text-slate-900 dark:text-slate-50">
          {currentPost.title}
        </h1>
        <p className="text-xl md:text-2xl text-slate-600 dark:text-slate-400 font-light leading-relaxed">
          Publié le {formatDate(currentPost.created_at)}
        </p>
      </div>

      {/* Article Image */}
      {currentPost.image && (
        <div className="relative w-full aspect-video rounded-2xl overflow-hidden mb-12 shadow-2xl shadow-primary/5">
          <div 
            className="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
            style={{ backgroundImage: `url(${currentPost.image})` }}
          ></div>
          <div className="absolute inset-0 bg-gradient-to-t from-background-dark/80 to-transparent"></div>
        </div>
      )}

      {/* Article Content */}
      <div className="flex flex-col gap-8 text-lg leading-relaxed text-slate-700 dark:text-slate-300">
        {currentPost.content.split('\n').map((paragraph, index) => (
          <p key={index}>{paragraph}</p>
        ))}
      </div>

      {/* Article Footer */}
      <div className="mt-20 pt-10 border-t border-slate-200 dark:border-slate-800">
        <div className="flex items-center justify-between flex-wrap gap-6">
          <div className="flex items-center gap-4">
            <button className="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
              <span className="material-symbols-outlined text-xl">thumb_up</span>
              <span className="text-sm font-bold">J'aime</span>
            </button>
            <button className="flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
              <span className="material-symbols-outlined text-xl">share</span>
              <span className="text-sm font-bold">Partager</span>
            </button>
          </div>
          <div className="flex gap-2">
            <span className="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded text-xs font-medium">#article</span>
            <span className="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded text-xs font-medium">#blog</span>
          </div>
        </div>
      </div>
    </div>
  )
}

export default PostDetail
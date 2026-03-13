import React, { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { usePostStore } from '../store/postStore'
import { useAuthStore } from '../store/authStore'
import { Post } from '../types/Post'

const AdminDashboard: React.FC = () => {
  const navigate = useNavigate()
  const { user } = useAuthStore()
  const { posts, fetchPosts, deletePost, isLoading } = usePostStore()
  const [searchTerm, setSearchTerm] = useState('')

  useEffect(() => {
    if (!user || user.role !== 'admin') {
      navigate('/login')
    } else {
      fetchPosts()
    }
  }, [user, navigate, fetchPosts])

  const handleDeletePost = async (id: number) => {
    if (window.confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
      await deletePost(id)
    }
  }

  const filteredPosts = posts.filter(post =>
    post.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
    post.content.toLowerCase().includes(searchTerm.toLowerCase())
  )

  if (!user || user.role !== 'admin') {
    return null
  }

  return (
    <div className="py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-8 flex justify-between items-center">
          <div>
            <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Tableau de bord Administrateur</h1>
            <p className="text-slate-600 dark:text-slate-400 mt-1">Gérez les articles et les utilisateurs</p>
          </div>
          <button
            onClick={() => navigate('/post-editor')}
            className="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors"
          >
            Créer un article
          </button>
        </div>

        {/* Search Bar */}
        <div className="mb-8">
          <input
            type="text"
            placeholder="Rechercher un article..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-white"
          />
        </div>

        {/* Posts List */}
        <div className="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
          <div className="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h2 className="text-lg font-semibold text-slate-900 dark:text-white">Articles</h2>
          </div>
          
          {isLoading ? (
            <div className="flex justify-center items-center py-12">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
            </div>
          ) : filteredPosts.length === 0 ? (
            <div className="p-6 text-center text-slate-500 dark:text-slate-400">
              Aucun article trouvé
            </div>
          ) : (
            <div className="divide-y divide-slate-200 dark:divide-slate-800">
              {filteredPosts.map((post) => (
                <div key={post.id} className="p-6 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                  <div className="flex justify-between items-start">
                    <div className="flex-1">
                      <h3 className="text-lg font-medium text-slate-900 dark:text-white mb-2">{post.title}</h3>
                      <p className="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-2">{post.content}</p>
                      <div className="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                        <span>{new Date(post.created_at).toLocaleDateString('fr-FR')}</span>
                        <span>•</span>
                        <span>{Math.ceil(post.content.split(' ').length / 200)} min de lecture</span>
                      </div>
                    </div>
                    <div className="flex gap-2 ml-4">
                      <button
                        onClick={() => navigate(`/post-editor/${post.id}`)}
                        className="px-3 py-1 text-sm bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-200 dark:hover:bg-slate-700"
                      >
                        Modifier
                      </button>
                      <button
                        onClick={() => handleDeletePost(post.id)}
                        className="px-3 py-1 text-sm bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-md hover:bg-red-200 dark:hover:bg-red-900/40"
                      >
                        Supprimer
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  )
}

export default AdminDashboard
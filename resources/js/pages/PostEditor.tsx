import React, { useState, useEffect } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { usePostStore } from '../store/postStore'
import { useAuthStore } from '../store/authStore'

const PostEditor: React.FC = () => {
  const { id } = useParams<{ id: string }>()
  const navigate = useNavigate()
  const { user } = useAuthStore()
  const { currentPost, createPost, updatePost, setCurrentPost } = usePostStore()
  
  const [formData, setFormData] = useState({
    title: '',
    content: '',
    image: null as File | null
  })
  const [isLoading, setIsLoading] = useState(false)
  const [imagePreview, setImagePreview] = useState<string | null>(null)

  useEffect(() => {
    if (id && currentPost) {
      setFormData({
        title: currentPost.title,
        content: currentPost.content,
        image: null
      })
      if (currentPost.image) {
        setImagePreview(currentPost.image)
      }
    }
  }, [id, currentPost])

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    })
  }

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0]
    if (file) {
      setFormData({
        ...formData,
        image: file
      })
      setImagePreview(URL.createObjectURL(file))
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsLoading(true)

    try {
      if (id) {
        // Update existing post
        await updatePost(parseInt(id), { ...formData, id: parseInt(id) })
      } else {
        // Create new post
        await createPost(formData)
      }
      navigate('/')
    } catch (error) {
      console.error('Error saving post:', error)
    } finally {
      setIsLoading(false)
    }
  }

  const handleCancel = () => {
    navigate('/')
  }

  if (id && !currentPost) {
    return (
      <div className="text-center py-12">
        <h2 className="text-2xl font-bold text-slate-900 dark:text-white">Article non trouvé</h2>
        <p className="text-slate-500 dark:text-slate-400 mt-2">L'article que vous essayez de modifier n'existe pas.</p>
      </div>
    )
  }

  return (
    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div className="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
        <h1 className="text-3xl font-bold text-slate-900 dark:text-white mb-8">
          {id ? 'Modifier l\'article' : 'Créer un nouvel article'}
        </h1>

        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label htmlFor="title" className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Titre de l'article
            </label>
            <input
              type="text"
              id="title"
              name="title"
              required
              className="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-white"
              placeholder="Entrez le titre de votre article"
              value={formData.title}
              onChange={handleChange}
            />
          </div>

          <div>
            <label htmlFor="content" className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Contenu de l'article
            </label>
            <textarea
              id="content"
              name="content"
              required
              rows={10}
              className="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-white"
              placeholder="Rédigez votre article ici..."
              value={formData.content}
              onChange={handleChange}
            />
          </div>

          <div>
            <label htmlFor="image" className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Image de l'article (optionnel)
            </label>
            <input
              type="file"
              id="image"
              name="image"
              accept="image/*"
              className="w-full text-sm text-slate-500 dark:text-slate-400
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-primary file:text-white
                hover:file:bg-primary/90"
              onChange={handleImageChange}
            />
            
            {imagePreview && (
              <div className="mt-4">
                <img src={imagePreview} alt="Aperçu de l'image" className="max-h-48 rounded-lg" />
              </div>
            )}
          </div>

          <div className="flex justify-between items-center pt-6 border-t border-slate-200 dark:border-slate-800">
            <button
              type="button"
              onClick={handleCancel}
              className="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-md hover:bg-slate-200 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500"
            >
              Annuler
            </button>
            
            <button
              type="submit"
              disabled={isLoading}
              className="px-6 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {isLoading ? (id ? 'Mise à jour...' : 'Création...') : (id ? 'Mettre à jour' : 'Publier')}
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}

export default PostEditor
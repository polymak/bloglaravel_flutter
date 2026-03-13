import React from 'react'
import { Link } from 'react-router-dom'
import { Post } from '../types/Post'

interface PostCardProps {
  post: Post
}

const PostCard: React.FC<PostCardProps> = ({ post }) => {
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    })
  }

  const readingTime = Math.ceil(post.content.split(' ').length / 200)

  return (
    <article className="group cursor-pointer">
      <div className="relative aspect-video rounded-xl overflow-hidden mb-4 bg-slate-800">
        {post.image ? (
          <div 
            className="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110"
            style={{ backgroundImage: `url(${post.image})` }}
          ></div>
        ) : (
          <div className="absolute inset-0 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
            <span className="text-slate-400 dark:text-slate-500">No image</span>
          </div>
        )}
        <div className="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
      </div>
      
      <div>
        <div className="flex items-center gap-2 text-primary text-xs font-bold uppercase tracking-wider mb-2">
          <span>Article</span>
          <span className="size-1 rounded-full bg-slate-500"></span>
          <span className="text-slate-500 font-medium">{readingTime} min</span>
        </div>
        
        <h4 className="text-xl font-bold mb-2 group-hover:text-primary transition-colors">
          {post.title}
        </h4>
        
        <p className="text-slate-400 text-sm leading-relaxed line-clamp-2">
          {post.content.substring(0, 150)}...
        </p>
      </div>
    </article>
  )
}

export default PostCard
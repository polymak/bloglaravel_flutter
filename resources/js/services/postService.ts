import api from './api'
import { Post, CreatePostData, UpdatePostData } from '../types/Post'

export const postService = {
  async getAll(): Promise<Post[]> {
    const response = await api.get('/posts')
    return response.data.data || response.data
  },

  async getById(id: number): Promise<Post> {
    const response = await api.get(`/posts/${id}`)
    return response.data.data || response.data
  },

  async create(data: CreatePostData): Promise<Post> {
    const formData = new FormData()
    formData.append('title', data.title)
    formData.append('content', data.content)
    
    if (data.image) {
      formData.append('image', data.image)
    }

    const response = await api.post('/posts', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data.data || response.data
  },

  async update(id: number, data: UpdatePostData): Promise<Post> {
    const formData = new FormData()
    formData.append('title', data.title)
    formData.append('content', data.content)
    
    if (data.image) {
      formData.append('image', data.image)
    }

    const response = await api.put(`/posts/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    return response.data.data || response.data
  },

  async delete(id: number): Promise<void> {
    await api.delete(`/posts/${id}`)
  }
}
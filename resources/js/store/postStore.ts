import { create } from 'zustand'
import { Post, CreatePostData, UpdatePostData } from '../types/Post'
import { postService } from '../services/postService'

interface PostState {
  posts: Post[]
  currentPost: Post | null
  isLoading: boolean
  error: string | null
  fetchPosts: () => Promise<void>
  fetchPost: (id: number) => Promise<void>
  createPost: (data: CreatePostData) => Promise<Post>
  updatePost: (id: number, data: UpdatePostData) => Promise<Post>
  deletePost: (id: number) => Promise<void>
  clearError: () => void
  setCurrentPost: (post: Post | null) => void
}

export const usePostStore = create<PostState>((set, get) => ({
  posts: [],
  currentPost: null,
  isLoading: false,
  error: null,

  fetchPosts: async () => {
    set({ isLoading: true, error: null })
    try {
      const posts = await postService.getAll()
      set({ posts, isLoading: false })
    } catch (error: any) {
      set({ 
        error: error.response?.data?.message || 'Failed to fetch posts', 
        isLoading: false 
      })
    }
  },

  fetchPost: async (id: number) => {
    set({ isLoading: true, error: null })
    try {
      const post = await postService.getById(id)
      set({ currentPost: post, isLoading: false })
    } catch (error: any) {
      set({ 
        error: error.response?.data?.message || 'Failed to fetch post', 
        isLoading: false 
      })
    }
  },

  createPost: async (data: CreatePostData) => {
    set({ isLoading: true, error: null })
    try {
      const post = await postService.create(data)
      const { posts } = get()
      set({ posts: [post, ...posts], isLoading: false })
      return post
    } catch (error: any) {
      set({ 
        error: error.response?.data?.message || 'Failed to create post', 
        isLoading: false 
      })
      throw error
    }
  },

  updatePost: async (id: number, data: UpdatePostData) => {
    set({ isLoading: true, error: null })
    try {
      const post = await postService.update(id, data)
      const { posts } = get()
      const updatedPosts = posts.map(p => p.id === id ? post : p)
      set({ posts: updatedPosts, currentPost: post, isLoading: false })
      return post
    } catch (error: any) {
      set({ 
        error: error.response?.data?.message || 'Failed to update post', 
        isLoading: false 
      })
      throw error
    }
  },

  deletePost: async (id: number) => {
    set({ isLoading: true, error: null })
    try {
      await postService.delete(id)
      const { posts } = get()
      const filteredPosts = posts.filter(p => p.id !== id)
      set({ posts: filteredPosts, isLoading: false })
    } catch (error: any) {
      set({ 
        error: error.response?.data?.message || 'Failed to delete post', 
        isLoading: false 
      })
    }
  },

  clearError: () => set({ error: null }),

  setCurrentPost: (post) => set({ currentPost: post })
}))
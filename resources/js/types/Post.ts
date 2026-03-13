export interface Post {
  id: number
  title: string
  content: string
  image: string | null
  created_at: string
  updated_at: string
}

export interface CreatePostData {
  title: string
  content: string
  image?: File | null
}

export interface UpdatePostData extends CreatePostData {
  id: number
}
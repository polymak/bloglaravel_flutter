export interface User {
  id: number
  name: string
  email: string
  email_verified_at?: string | null
  role: 'user' | 'admin'
  created_at: string
  updated_at: string
}

export interface AuthUser extends User {
  token?: string
}

export interface LoginData {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface CreateUser extends Omit<RegisterData, 'password_confirmation'> {
  role: 'user' | 'admin'
}

export interface UpdateUser extends Partial<CreateUser> {
  id: number
}
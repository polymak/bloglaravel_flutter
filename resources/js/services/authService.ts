import api from './api'
import { LoginData, RegisterData, AuthUser } from '../types/User'

export const authService = {
  async login(data: LoginData): Promise<AuthUser> {
    const response = await api.post('/auth/login', data)
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
    }
    return response.data
  },

  async register(data: RegisterData): Promise<AuthUser> {
    const response = await api.post('/auth/register', data)
    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
    }
    return response.data
  },

  async logout(): Promise<void> {
    await api.post('/auth/logout')
    localStorage.removeItem('auth_token')
  },

  async getCurrentUser(): Promise<AuthUser> {
    const response = await api.get('/user')
    return response.data
  },

  getToken(): string | null {
    return localStorage.getItem('auth_token')
  },

  isAuthenticated(): boolean {
    return !!localStorage.getItem('auth_token')
  },

  clearToken(): void {
    localStorage.removeItem('auth_token')
  }
}
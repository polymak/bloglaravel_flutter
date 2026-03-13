import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import { AuthUser } from '../types/User'
import { authService } from '../services/authService'

interface AuthState {
  user: AuthUser | null
  isAuthenticated: boolean
  isLoading: boolean
  error: string | null
  login: (data: { email: string; password: string }) => Promise<void>
  register: (data: { name: string; email: string; password: string; password_confirmation: string }) => Promise<void>
  logout: () => Promise<void>
  clearError: () => void
  setUser: (user: AuthUser | null) => void
}

export const useAuthStore = create<AuthState>()(
  persist(
    (set, get) => ({
      user: null,
      isAuthenticated: false,
      isLoading: false,
      error: null,

      login: async (data) => {
        set({ isLoading: true, error: null })
        try {
          const user = await authService.login(data)
          set({ user, isAuthenticated: true, isLoading: false })
        } catch (error: any) {
          set({ 
            error: error.response?.data?.message || 'Login failed', 
            isLoading: false 
          })
        }
      },

      register: async (data) => {
        set({ isLoading: true, error: null })
        try {
          const user = await authService.register(data)
          set({ user, isAuthenticated: true, isLoading: false })
        } catch (error: any) {
          set({ 
            error: error.response?.data?.message || 'Registration failed', 
            isLoading: false 
          })
        }
      },

      logout: async () => {
        try {
          await authService.logout()
        } catch (error) {
          console.error('Logout error:', error)
        } finally {
          set({ user: null, isAuthenticated: false, error: null })
        }
      },

      clearError: () => set({ error: null }),

      setUser: (user) => set({ user })
    }),
    {
      name: 'auth-storage',
      partialize: (state) => ({ user: state.user, isAuthenticated: state.isAuthenticated }),
    }
  )
)
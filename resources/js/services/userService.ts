import api from './api'
import { User, CreateUser, UpdateUser } from '../types/User'

export const userService = {
  async getAll(): Promise<User[]> {
    const response = await api.get('/users')
    return response.data.data || response.data
  },

  async getById(id: number): Promise<User> {
    const response = await api.get(`/users/${id}`)
    return response.data.data || response.data
  },

  async create(data: CreateUser): Promise<User> {
    const response = await api.post('/users', data)
    return response.data.data || response.data
  },

  async update(id: number, data: UpdateUser): Promise<User> {
    const response = await api.put(`/users/${id}`, data)
    return response.data.data || response.data
  },

  async delete(id: number): Promise<void> {
    await api.delete(`/users/${id}`)
  }
}
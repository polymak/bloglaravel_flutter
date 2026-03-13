import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Layout from './components/Layout'
import Home from './pages/Home'
import Login from './pages/Login'
import Register from './pages/Register'
import PostDetail from './pages/PostDetail'
import AdminDashboard from './pages/AdminDashboard'
import PostEditor from './pages/PostEditor'

export default function App() {
  return (
    <Router>
      <Layout>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/post/:id" element={<PostDetail />} />
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/admin/post/new" element={<PostEditor />} />
          <Route path="/admin/post/:id/edit" element={<PostEditor />} />
        </Routes>
      </Layout>
    </Router>
  )
}

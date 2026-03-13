<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $postsCount = Post::count();
        $usersCount = User::count();
        $adminsCount = User::where('role', 'admin')->count();
        
        return view('admin.dashboard', compact('postsCount', 'usersCount', 'adminsCount'));
    }
}

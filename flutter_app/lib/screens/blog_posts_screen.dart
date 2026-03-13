import 'package:flutter/material.dart';
import 'package:flutter_app/models/post_model.dart';
import 'package:flutter_app/services/api_service.dart';
import 'package:flutter_app/widgets/post_card.dart';
import 'package:flutter_app/screens/post_form_screen.dart';
import 'package:shared_preferences/shared_preferences.dart';

class BlogPostsScreen extends StatefulWidget {
  const BlogPostsScreen({super.key});

  @override
  State<BlogPostsScreen> createState() => _BlogPostsScreenState();
}

class _BlogPostsScreenState extends State<BlogPostsScreen> {
  late Future<List<Post>> _postsFuture;
  late ApiService _apiService;
  String? _token;

  @override
  void initState() {
    super.initState();
    _apiService = ApiService();
    _loadToken();
  }

  Future<void> _loadToken() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
    if (_token != null) {
      setState(() {
        _postsFuture = _fetchPosts();
      });
    }
  }

  Future<List<Post>> _fetchPosts() async {
    try {
      return await _apiService.fetchPosts();
    } catch (e) {
      throw Exception('Failed to load posts: $e');
    }
  }

  Future<void> _refreshPosts() async {
    if (_token != null) {
      setState(() {
        _postsFuture = _fetchPosts();
      });
    }
  }

  Future<void> _createPost() async {
    if (_token != null) {
      final result = await Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) =>
              PostFormScreen(token: _token!, isEditing: false),
        ),
      );
      if (result == true) {
        _refreshPosts();
      }
    }
  }

  Future<void> _editPost(Post post) async {
    if (_token != null) {
      final result = await Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) =>
              PostFormScreen(token: _token!, isEditing: true, post: post),
        ),
      );
      if (result == true) {
        _refreshPosts();
      }
    }
  }

  Future<void> _deletePost(Post post) async {
    if (_token != null) {
      try {
        await _apiService.deletePost(post.id, _token!);
        _refreshPosts();
      } catch (e) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Failed to delete post: $e')));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Blog Posts Management'),
        backgroundColor: Theme.of(context).colorScheme.primary,
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _createPost,
        tooltip: 'Add New Article',
        child: const Icon(Icons.add),
      ),
      body: _token == null
          ? const Center(child: Text('Not authenticated'))
          : FutureBuilder<List<Post>>(
              future: _postsFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                } else if (snapshot.hasError) {
                  return Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(Icons.error, size: 64, color: Colors.red),
                        const SizedBox(height: 16),
                        Text(
                          'Error: ${snapshot.error}',
                          style: const TextStyle(fontSize: 16),
                          textAlign: TextAlign.center,
                        ),
                        const SizedBox(height: 16),
                        ElevatedButton(
                          onPressed: _refreshPosts,
                          child: const Text('Retry'),
                        ),
                      ],
                    ),
                  );
                } else if (snapshot.hasData) {
                  final posts = snapshot.data!;

                  if (posts.isEmpty) {
                    return const Center(child: Text('No posts available'));
                  }

                  return RefreshIndicator(
                    onRefresh: _refreshPosts,
                    child: ListView.builder(
                      padding: const EdgeInsets.all(16),
                      itemCount: posts.length,
                      itemBuilder: (context, index) {
                        final post = posts[index];
                        return Card(
                          margin: const EdgeInsets.only(bottom: 16),
                          child: Padding(
                            padding: const EdgeInsets.all(16),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                // Post Image
                                if (post.image != null &&
                                    post.image!.isNotEmpty)
                                  Container(
                                    height: 200,
                                    width: double.infinity,
                                    decoration: BoxDecoration(
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Image.network(
                                      post.image!,
                                      fit: BoxFit.cover,
                                      loadingBuilder:
                                          (context, child, loadingProgress) {
                                            if (loadingProgress == null) {
                                              return child;
                                            }
                                            return Center(
                                              child: CircularProgressIndicator(
                                                value:
                                                    loadingProgress
                                                            .expectedTotalBytes !=
                                                        null
                                                    ? loadingProgress
                                                              .cumulativeBytesLoaded /
                                                          loadingProgress
                                                              .expectedTotalBytes!
                                                    : null,
                                              ),
                                            );
                                          },
                                      errorBuilder:
                                          (context, error, stackTrace) {
                                            return const Center(
                                              child: Text(
                                                'Image failed to load',
                                              ),
                                            );
                                          },
                                    ),
                                  ),
                                if (post.image != null &&
                                    post.image!.isNotEmpty)
                                  const SizedBox(height: 12),

                                // Post Title
                                Text(
                                  post.title,
                                  style: const TextStyle(
                                    fontSize: 18,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                const SizedBox(height: 8),

                                // Post Content Preview
                                Text(
                                  post.content.length > 100
                                      ? '${post.content.substring(0, 100)}...'
                                      : post.content,
                                  style: const TextStyle(
                                    fontSize: 14,
                                    color: Colors.grey,
                                  ),
                                ),
                                const SizedBox(height: 12),

                                // Action Buttons
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.end,
                                  children: [
                                    ElevatedButton.icon(
                                      onPressed: () => _editPost(post),
                                      icon: const Icon(Icons.edit, size: 16),
                                      label: const Text('Edit'),
                                      style: ElevatedButton.styleFrom(
                                        backgroundColor: Colors.blue,
                                        padding: const EdgeInsets.symmetric(
                                          horizontal: 16,
                                          vertical: 8,
                                        ),
                                      ),
                                    ),
                                    const SizedBox(width: 8),
                                    ElevatedButton.icon(
                                      onPressed: () => _deletePost(post),
                                      icon: const Icon(Icons.delete, size: 16),
                                      label: const Text('Delete'),
                                      style: ElevatedButton.styleFrom(
                                        backgroundColor: Colors.red,
                                        padding: const EdgeInsets.symmetric(
                                          horizontal: 16,
                                          vertical: 8,
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
                  );
                } else {
                  return const Center(child: Text('No data available'));
                }
              },
            ),
    );
  }
}

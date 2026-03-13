import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/post_model.dart';
import '../models/user_model.dart';
import 'log_service.dart';

class ApiService {
  static const String baseUrl = 'https://testapp.depechesdelatshopo.com/api';

  final http.Client _client;

  ApiService({http.Client? client}) : _client = client ?? http.Client();

  Future<List<Post>> fetchPosts() async {
    try {
      final response = await _client.get(
        Uri.parse('$baseUrl/posts'),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);
        final List<dynamic> postsData = decoded['data'];
        return postsData.map((json) => Post.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load posts: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  Future<Post> fetchPostById(int id) async {
    try {
      final response = await _client.get(
        Uri.parse('$baseUrl/posts/$id'),
        headers: {'Content-Type': 'application/json'},
      );

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);
        final postData = decoded['data'];
        return Post.fromJson(postData);
      } else {
        throw Exception('Failed to load post: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  Future<String> login(String email, String password) async {
    try {
      LogService.log("Calling API: login with email $email");

      final response = await _client.post(
        Uri.parse('$baseUrl/auth/login'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'email': email, 'password': password}),
      );

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);

        if (decoded['status'] == true) {
          final token = decoded['data']['token'];
          final userRole = decoded['data']['user']['role'] ?? 'unknown';
          LogService.log("Login success for user: $email with role: $userRole");

          // Store token in SharedPreferences
          await _storeToken(token);

          return token;
        } else {
          LogService.log("Login failed: ${decoded['message']}");
          throw Exception(decoded['message'] ?? 'Login failed');
        }
      } else {
        final errorJson = jsonDecode(response.body);
        LogService.log("Login error: ${errorJson['message']}");
        throw Exception(errorJson['message'] ?? 'Server error');
      }
    } catch (e) {
      LogService.log("ERROR: Login network error: $e");
      throw Exception('Network error: $e');
    }
  }

  Future<void> _storeToken(String token) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString('auth_token', token);
      LogService.log("Token stored successfully");
    } catch (e) {
      LogService.log("ERROR: Failed to store token: $e");
    }
  }

  Future<String?> getToken() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token != null) {
        LogService.log("Token retrieved from storage");
      } else {
        LogService.log("No token found in storage");
      }
      return token;
    } catch (e) {
      LogService.log("ERROR: Failed to retrieve token: $e");
      return null;
    }
  }

  Future<void> clearToken() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove('auth_token');
      LogService.log("Token cleared from storage");
    } catch (e) {
      LogService.log("ERROR: Failed to clear token: $e");
    }
  }

  Future<Post> createPost(
    String title,
    String content,
    String? image,
    String token,
  ) async {
    try {
      final response = await _client.post(
        Uri.parse('$baseUrl/posts'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({'title': title, 'content': content, 'image': image}),
      );

      if (response.statusCode == 201) {
        final json = jsonDecode(response.body);
        return Post.fromJson(json['data']);
      } else {
        final errorJson = jsonDecode(response.body);
        throw Exception(errorJson['message'] ?? 'Failed to create post');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  Future<Post> updatePost(
    int id,
    String title,
    String content,
    String? image,
    String token,
  ) async {
    try {
      final response = await _client.put(
        Uri.parse('$baseUrl/posts/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({'title': title, 'content': content, 'image': image}),
      );

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        return Post.fromJson(json['data']);
      } else {
        final errorJson = jsonDecode(response.body);
        throw Exception(errorJson['message'] ?? 'Failed to update post');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  Future<void> deletePost(int id, String token) async {
    try {
      final response = await _client.delete(
        Uri.parse('$baseUrl/posts/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode != 200) {
        final errorJson = jsonDecode(response.body);
        throw Exception(errorJson['message'] ?? 'Failed to delete post');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  // User Management Methods
  Future<List<User>> fetchUsers(String token) async {
    try {
      LogService.log(
        "Calling API: fetchUsers with token: ${token.substring(0, token.length < 20 ? token.length : 20)}...",
      );

      // First, let's test if the token works with another endpoint
      final testResponse = await _client.get(
        Uri.parse('$baseUrl/posts'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      LogService.log(
        "Test API (posts) Response Status: ${testResponse.statusCode}",
      );
      if (testResponse.statusCode == 200) {
        LogService.log("Token is valid - can access posts endpoint");
      } else {
        LogService.log("Token might be invalid or expired");
      }

      // NEW: Call users endpoint without authentication (Laravel change)
      final response = await _client.get(
        Uri.parse('$baseUrl/users'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          // No Authorization header needed - endpoint is now public
        },
      );

      LogService.log("Users API Response Status: ${response.statusCode}");
      LogService.log("Users API Response Body: ${response.body}");

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);

        LogService.log("Users API decoded response: ${decoded.toString()}");

        if (decoded['status'] == true && decoded.containsKey('data')) {
          final usersData = decoded['data'];
          LogService.log("Users data received: ${usersData.length} users");

          if (usersData is List) {
            if (usersData.isEmpty) {
              LogService.log(
                "WARNING: Users API returned empty array - no users found",
              );
            } else {
              LogService.log(
                "SUCCESS: Found ${usersData.length} users in database",
              );
            }
            return usersData.map((json) => User.fromJson(json)).toList();
          } else {
            throw Exception('Invalid data format: expected array');
          }
        } else {
          LogService.log("ERROR: Invalid response format or status false");
          throw Exception('Invalid response format or status false');
        }
      } else {
        LogService.log(
          "ERROR: Failed to load users with status: ${response.statusCode}",
        );
        throw Exception('Failed to load users: ${response.statusCode}');
      }
    } catch (e) {
      LogService.log("ERROR: Exception in fetchUsers: $e");
      throw Exception('Network error: $e');
    }
  }

  Future<User> fetchUserById(int id, String token) async {
    try {
      final response = await _client.get(
        Uri.parse('$baseUrl/users/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final decoded = jsonDecode(response.body);
        final userData = decoded['data'];
        return User.fromJson(userData);
      } else {
        throw Exception('Failed to load user: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  Future<User> createUser(
    String name,
    String email,
    String password,
    String role,
    String token,
  ) async {
    try {
      final response = await _client.post(
        Uri.parse('$baseUrl/users'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': password,
          'role': role,
        }),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        final decoded = jsonDecode(response.body);
        print(decoded);
        return User.fromJson(decoded['data']);
      } else {
        print(response.body);
        throw Exception('Failed to create user: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  Future<User> updateUser(
    int id,
    String name,
    String email,
    String? password,
    String role,
    String token,
  ) async {
    try {
      final body = {'name': name, 'email': email, 'role': role};

      if (password != null && password.isNotEmpty) {
        body['password'] = password;
      }

      final response = await _client.put(
        Uri.parse('$baseUrl/users/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(body),
      );

      if (response.statusCode == 200) {
        final json = jsonDecode(response.body);
        return User.fromJson(json['data']);
      } else {
        final errorJson = jsonDecode(response.body);
        throw Exception(errorJson['message'] ?? 'Failed to update user');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  Future<void> deleteUser(int id, String token) async {
    try {
      final response = await _client.delete(
        Uri.parse('$baseUrl/users/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode != 200) {
        final errorJson = jsonDecode(response.body);
        throw Exception(errorJson['message'] ?? 'Failed to delete user');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  // Multipart Form Data Methods for Image Upload
  Future<Post> createPostWithImage(
    String title,
    String content,
    File? imageFile,
    String token,
  ) async {
    try {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/posts'),
      );

      request.headers['Authorization'] = 'Bearer $token';
      request.headers['Accept'] = 'application/json';

      // Add form fields
      request.fields['title'] = title;
      request.fields['content'] = content;

      // Add image file if provided
      if (imageFile != null) {
        request.files.add(
          await http.MultipartFile.fromPath(
            'image',
            imageFile.path,
            contentType: MediaType('image', imageFile.path.split('.').last),
          ),
        );
      }

      final response = await request.send();
      final responseString = await response.stream.bytesToString();

      if (response.statusCode == 201) {
        final decoded = jsonDecode(responseString);
        print(decoded);
        return Post.fromJson(decoded['data']);
      } else {
        print(responseString);
        throw Exception('Failed to create post: ${response.statusCode}');
      }
    } catch (e) {
      print('Error creating post with image: $e');
      throw Exception('Network error: $e');
    }
  }

  Future<Post> updatePostWithImage(
    int id,
    String title,
    String content,
    File? imageFile,
    String token,
  ) async {
    try {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/posts/$id'),
      );

      request.headers['Authorization'] = 'Bearer $token';
      request.headers['Accept'] = 'application/json';

      // Add form fields with _method = PUT for Laravel
      request.fields['_method'] = 'PUT';
      request.fields['title'] = title;
      request.fields['content'] = content;

      // Add image file if provided
      if (imageFile != null) {
        request.files.add(
          await http.MultipartFile.fromPath(
            'image',
            imageFile.path,
            contentType: MediaType('image', imageFile.path.split('.').last),
          ),
        );
      }

      final response = await request.send();
      final responseString = await response.stream.bytesToString();

      if (response.statusCode == 200) {
        final decoded = jsonDecode(responseString);
        print(decoded);
        return Post.fromJson(decoded['data']);
      } else {
        print(responseString);
        throw Exception('Failed to update post: ${response.statusCode}');
      }
    } catch (e) {
      print('Error updating post with image: $e');
      throw Exception('Network error: $e');
    }
  }
}

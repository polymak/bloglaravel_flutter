import 'package:flutter/material.dart';
import 'package:flutter_app/models/user_model.dart';
import 'package:flutter_app/services/api_service.dart';
import 'package:flutter_app/screens/user_form_screen.dart';
import 'package:shared_preferences/shared_preferences.dart';

class UsersScreen extends StatefulWidget {
  const UsersScreen({super.key});

  @override
  State<UsersScreen> createState() => _UsersScreenState();
}

class _UsersScreenState extends State<UsersScreen> {
  late Future<List<User>> _usersFuture;
  String? _token;
  late ApiService _apiService;

  @override
  void initState() {
    super.initState();
    _apiService = ApiService();
    _loadToken();
  }

  Future<void> _loadToken() async {
    _token = await _apiService.getToken();
    if (_token != null) {
      setState(() {
        _usersFuture = _fetchUsers();
      });
    }
  }

  Future<List<User>> _fetchUsers() async {
    try {
      return await _apiService.fetchUsers(_token!);
    } catch (e) {
      throw Exception('Failed to load users: $e');
    }
  }

  Future<void> _refreshUsers() async {
    if (_token != null) {
      setState(() {
        _usersFuture = _fetchUsers();
      });
    }
  }

  Future<void> _createUser() async {
    if (_token != null) {
      final result = await Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) =>
              UserFormScreen(token: _token!, isEditing: false),
        ),
      );
      if (result == true) {
        _refreshUsers();
      }
    }
  }

  Future<void> _editUser(User user) async {
    if (_token != null) {
      final result = await Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) =>
              UserFormScreen(token: _token!, isEditing: true, user: user),
        ),
      );
      if (result == true) {
        _refreshUsers();
      }
    }
  }

  Future<void> _deleteUser(User user) async {
    if (_token != null) {
      try {
        await _apiService.deleteUser(user.id, _token!);
        _refreshUsers();
      } catch (e) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Failed to delete user: $e')));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('User Management'),
        backgroundColor: Theme.of(context).colorScheme.primary,
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _refreshUsers,
            tooltip: 'Refresh',
          ),
          IconButton(
            icon: const Icon(Icons.api),
            onPressed: _testApiDirectly,
            tooltip: 'Test API',
          ),
        ],
      ),
      floatingActionButton: Column(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          FloatingActionButton(
            onPressed: _createUser,
            tooltip: 'Add New User',
            heroTag: "addUser",
            child: const Icon(Icons.person_add),
          ),
          const SizedBox(height: 16),
          FloatingActionButton(
            onPressed: _refreshUsers,
            tooltip: 'Refresh List',
            heroTag: "refreshList",
            backgroundColor: Colors.orange,
            child: const Icon(Icons.refresh),
          ),
        ],
      ),
      body: _token == null
          ? const Center(child: Text('Not authenticated'))
          : FutureBuilder<List<User>>(
              future: _apiService.fetchUsers(_token!),
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        CircularProgressIndicator(),
                        SizedBox(height: 16),
                        Text('Loading users...'),
                      ],
                    ),
                  );
                }

                if (snapshot.hasError) {
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
                          onPressed: _refreshUsers,
                          child: const Text('Retry'),
                        ),
                      ],
                    ),
                  );
                }

                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.group, size: 64, color: Colors.grey),
                        SizedBox(height: 16),
                        Text(
                          "No users available",
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        SizedBox(height: 8),
                        Text(
                          "The database might be empty or there's an API issue",
                          textAlign: TextAlign.center,
                          style: TextStyle(color: Colors.grey),
                        ),
                        SizedBox(height: 16),
                        Text(
                          "Try creating a new user or check the API logs",
                          textAlign: TextAlign.center,
                          style: TextStyle(fontSize: 12, color: Colors.blue),
                        ),
                      ],
                    ),
                  );
                }

                final users = snapshot.data!;

                return RefreshIndicator(
                  onRefresh: _refreshUsers,
                  child: ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: users.length,
                    itemBuilder: (context, index) {
                      final user = users[index];
                      return Card(
                        elevation: 2,
                        margin: const EdgeInsets.only(bottom: 16),
                        child: ListTile(
                          leading: Container(
                            width: 40,
                            height: 40,
                            decoration: BoxDecoration(
                              color: Theme.of(context).colorScheme.primary,
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: const Icon(
                              Icons.person,
                              color: Colors.white,
                            ),
                          ),
                          title: Text(
                            user.name,
                            style: const TextStyle(fontWeight: FontWeight.bold),
                          ),
                          subtitle: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(user.email),
                              const SizedBox(height: 4),
                              Container(
                                padding: const EdgeInsets.symmetric(
                                  horizontal: 8,
                                  vertical: 2,
                                ),
                                decoration: BoxDecoration(
                                  color: user.role == 'admin'
                                      ? Colors.green
                                      : Colors.blue,
                                  borderRadius: BorderRadius.circular(4),
                                ),
                                child: Text(
                                  user.role ?? 'user',
                                  style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 12,
                                  ),
                                ),
                              ),
                            ],
                          ),
                          trailing: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              IconButton(
                                icon: const Icon(Icons.edit),
                                onPressed: () => _editUser(user),
                                tooltip: 'Edit',
                              ),
                              IconButton(
                                icon: const Icon(Icons.delete),
                                onPressed: () => _deleteUser(user),
                                tooltip: 'Delete',
                                color: Colors.red,
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  ),
                );
              },
            ),
    );
  }

  Future<void> _testApiDirectly() async {
    if (_token != null) {
      try {
        final response = await _apiService.fetchUsers(_token!);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('API Test: ${response.length} users found'),
            backgroundColor: response.isEmpty ? Colors.orange : Colors.green,
          ),
        );
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('API Test Failed: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }
}

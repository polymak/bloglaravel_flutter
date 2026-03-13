import 'package:flutter/material.dart';
import 'package:flutter_app/models/post_model.dart';
import 'package:flutter_app/services/api_service.dart';
import 'package:image_picker/image_picker.dart';
import 'dart:io';

class PostFormScreen extends StatefulWidget {
  final String token;
  final bool isEditing;
  final Post? post;

  const PostFormScreen({
    super.key,
    required this.token,
    required this.isEditing,
    this.post,
  });

  @override
  State<PostFormScreen> createState() => _PostFormScreenState();
}

class _PostFormScreenState extends State<PostFormScreen> {
  final ApiService _apiService = ApiService();
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _titleController = TextEditingController();
  final TextEditingController _contentController = TextEditingController();
  final TextEditingController _imageController = TextEditingController();
  bool _isLoading = false;
  File? _imageFile;
  String? _imagePreview;

  @override
  void initState() {
    super.initState();
    if (widget.isEditing && widget.post != null) {
      _titleController.text = widget.post!.title;
      _contentController.text = widget.post!.content;
      _imageController.text = widget.post!.image ?? '';
    }
  }

  Future<void> _pickImage() async {
    final ImagePicker picker = ImagePicker();
    final XFile? pickedFile = await picker.pickImage(
      source: ImageSource.gallery,
    );

    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
        _imagePreview = pickedFile.path;
      });
    }
  }

  Future<void> _takePhoto() async {
    final ImagePicker picker = ImagePicker();
    final XFile? pickedFile = await picker.pickImage(
      source: ImageSource.camera,
    );

    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
        _imagePreview = pickedFile.path;
      });
    }
  }

  Future<void> _savePost() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      if (widget.isEditing && widget.post != null) {
        if (_imageFile != null) {
          await _apiService.updatePostWithImage(
            widget.post!.id,
            _titleController.text.trim(),
            _contentController.text.trim(),
            _imageFile,
            widget.token,
          );
        } else {
          await _apiService.updatePost(
            widget.post!.id,
            _titleController.text.trim(),
            _contentController.text.trim(),
            _imageController.text.trim().isEmpty
                ? null
                : _imageController.text.trim(),
            widget.token,
          );
        }
      } else {
        if (_imageFile != null) {
          await _apiService.createPostWithImage(
            _titleController.text.trim(),
            _contentController.text.trim(),
            _imageFile,
            widget.token,
          );
        } else {
          await _apiService.createPost(
            _titleController.text.trim(),
            _contentController.text.trim(),
            _imageController.text.trim().isEmpty
                ? null
                : _imageController.text.trim(),
            widget.token,
          );
        }
      }

      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Post saved successfully')));
      Navigator.pop(context, true);
    } catch (e) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text('Error: $e')));
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: true,
      appBar: AppBar(
        title: Text(widget.isEditing ? 'Edit Article' : 'Add New Article'),
        backgroundColor: Theme.of(context).colorScheme.primary,
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Title Field
                TextFormField(
                  controller: _titleController,
                  decoration: const InputDecoration(
                    labelText: 'Title',
                    border: OutlineInputBorder(),
                  ),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter a title';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 16),

                // Content Field
                TextFormField(
                  controller: _contentController,
                  decoration: const InputDecoration(
                    labelText: 'Content',
                    border: OutlineInputBorder(),
                  ),
                  maxLines: 6,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter content';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 20),

                // Image Upload Section
                const Text(
                  'Image Upload',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 10),
                Row(
                  children: [
                    Expanded(
                      child: ElevatedButton.icon(
                        onPressed: _pickImage,
                        icon: const Icon(Icons.image),
                        label: const Text('Gallery'),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: ElevatedButton.icon(
                        onPressed: _takePhoto,
                        icon: const Icon(Icons.camera_alt),
                        label: const Text('Camera'),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 20),

                // Image Preview
                if (_imageFile != null)
                  Container(
                    height: 220,
                    width: double.infinity,
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.file(_imageFile!, fit: BoxFit.cover),
                    ),
                  ),

                const SizedBox(height: 30),

                // Image URL Field (Alternative)
                TextFormField(
                  controller: _imageController,
                  decoration: const InputDecoration(
                    labelText: 'Image URL (optional)',
                    border: OutlineInputBorder(),
                  ),
                  validator: (value) {
                    if (value != null &&
                        value.isNotEmpty &&
                        !value.startsWith('http')) {
                      return 'Please enter a valid URL';
                    }
                    return null;
                  },
                ),

                const SizedBox(height: 30),

                // Save Button
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _savePost,
                    style: ElevatedButton.styleFrom(
                      padding: const EdgeInsets.symmetric(vertical: 16),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    child: _isLoading
                        ? const CircularProgressIndicator()
                        : Text(
                            widget.isEditing
                                ? 'Update Article'
                                : 'Publish Article',
                          ),
                  ),
                ),

                const SizedBox(height: 40),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

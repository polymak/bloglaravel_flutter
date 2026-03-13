class Post {
  final int id;
  final String title;
  final String content;
  final String? image;
  final String? author;
  final DateTime? createdAt;

  Post({
    required this.id,
    required this.title,
    required this.content,
    this.image,
    this.author,
    this.createdAt,
  });

  factory Post.fromJson(Map<String, dynamic> json) {
    return Post(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      content: json['content'] ?? '',
      image: json['image'],
      author: json['author'],
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'content': content,
      'image': image,
      'author': author,
      'created_at': createdAt?.toIso8601String(),
    };
  }
}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post CRUD</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1>BLOG</h1>
            
            <!-- Right-aligned Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger float-end">Logout</button>
            </form>
        </div>

        <!-- Search Input -->
        <div class="mb-3">
            <input type="text" id="search-input" class="form-control" placeholder="Search posts by name">
        </div>

        <!-- Add Post Button -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addPostModal">Add Post</button>

        <!-- All Posts List -->
        <h2>All Posts</h2>

        <!-- Container for the Posts List -->
        <div id="posts-container" class="list-group">
            @include('partials.posts_list', ['posts' => $posts])
        </div>
    </div>


      <!-- Add Post Modal -->
<div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPostModalLabel">Add New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-post-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="post-name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="post-name" placeholder="Name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="post-content" class="form-label">Content</label>
                        <textarea class="form-control" name="content" id="post-content" placeholder="Content" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="post-image" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="post-image">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Post</button>
                </form>
            </div>
        </div>
    </div>
</div>


       <!-- Edit Post Modal (same as Add Post Modal but pre-filled with data) -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-post-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit-post-id">
                    
                    <!-- Name (editable) -->
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit-name" placeholder="Name" required>
                    </div>

                    <!-- Content (editable) -->
                    <div class="mb-3">
                        <label for="edit-content" class="form-label">Content</label>
                        <textarea class="form-control" name="content" id="edit-content" placeholder="Content" required></textarea>
                    </div>

                    <!-- Image (editable) -->
                    <div class="mb-3">
                        <label for="edit-image" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="edit-image">
                    </div>

                    <!-- Show existing image if available -->
                    <div class="mb-3" id="existing-image" style="display: none;">
                        <label class="form-label">Existing Image</label><br>
                        <img id="image-preview" src="" alt="Existing Image" class="img-fluid" style="max-width: 200px;">
                    </div>

                    <!-- Author and Date (not editable) -->
                    <div class="mb-3">
                        <label for="edit-author" class="form-label">Author</label>
                        <input type="text" class="form-control" name="author" id="edit-author" readonly>
                    </div>

                  

                    <button type="submit" class="btn btn-primary">Update Post</button>
                </form>
            </div>
        </div>
    </div>
</div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
    
            // Client-side validation function
            function validateForm(form) {
                let isValid = true;
                let name = form.find('input[name="name"]').val().trim();
                let content = form.find('textarea[name="content"]').val().trim();
                let image = form.find('input[name="image"]')[0].files[0];
    
                // Clear previous error messages
                form.find('.error-message').remove();
    
                // Name validation
                if (name === "") {
                    isValid = false;
                    form.find('input[name="name"]').after('<span class="error-message" style="color:red;">Name is required.</span>');
                }
    
                // Content validation
                if (content === "") {
                    isValid = false;
                    form.find('textarea[name="content"]').after('<span class="error-message" style="color:red;">Content is required.</span>');
                }
    
               
    
                // Image validation (optional)
                if (image) {
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                    const maxSize = 2048 * 1024; // 2MB in bytes
    
                    if (!allowedTypes.includes(image.type)) {
                        isValid = false;
                        form.find('input[name="image"]').after('<span class="error-message" style="color:red;">Only JPEG, PNG, JPG, GIF, or SVG files are allowed.</span>');
                    } else if (image.size > maxSize) {
                        isValid = false;
                        form.find('input[name="image"]').after('<span class="error-message" style="color:red;">Image size should not exceed 2MB.</span>');
                    }
                }
    
                return isValid;
            }
    
            // Add Post functionality
            $('#add-post-form').submit(function (e) {
                e.preventDefault();
    
                // Perform client-side validation
                if (!validateForm($(this))) {
                    return; // Stop submission if validation fails
                }
    
                var formData = new FormData(this);
    
                $.ajax({
                    url: "{{ route('posts.store') }}", // Add post route
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            var post = response.post;
                            $('#posts-container').prepend(`
                                <div id="post-${post.id}" class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>${post.name} <small class="text-muted">by ${post.author}</small></h5>
                                        <p>${post.content}</p>
                                        <small class="text-muted">${post.date}</small>
                                    </div>
                                    <div>
                                        ${post.image ? `<img src="/storage/${post.image}" width="100" class="mb-2">` : ''}
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${post.id}" data-name="${post.name}" data-author="${post.author}" data-content="${post.content}" data-image="${post.image}" data-date="${post.date}">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${post.id}">Delete</button>
                                    </div>
                                </div>
                            `);
                            $('#add-post-form')[0].reset();
                            $('#addPostModal').modal('hide');
                        }
                    },
                    error: function () {
                        alert('Error adding post');
                    }
                });
            });
    
            // Edit Post button click
            $(document).on('click', '.edit-btn', function () {
                var postId = $(this).data('id');
                var name = $(this).data('name');
                var author = $(this).data('author');
                var date = $(this).data('date');
                var content = $(this).data('content');
                var image = $(this).data('image'); // This is the image URL
    
                // Pre-fill the Edit Modal form with current post data
                $('#edit-post-id').val(postId);
                $('#edit-name').val(name);
                $('#edit-author').val(author);
                $('#edit-date').val(date);
                $('#edit-content').val(content);
    
                // Show existing image if available
                if (image) {
                    $('#existing-image').show(); // Show the existing image section
                    $('#image-preview').attr('src', '/storage/' + image); // Set the source of the image preview
                } else {
                    $('#existing-image').hide(); // Hide the existing image section if no image exists
                }
    
                // Show the Edit Modal
                var modal = new bootstrap.Modal($('#editPostModal')[0]);
                modal.show();
            });
    
            // Save edited post
            $('#edit-post-form').submit(function (e) {
                e.preventDefault();
    
                // Perform client-side validation
                if (!validateForm($(this))) {
                    return; // Stop submission if validation fails
                }
    
                var formData = new FormData(this);
                var postId = $('#edit-post-id').val();
                
                $.ajax({
                    url: `/posts/${postId}/update`,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            var post = response.post;
                            $('#post-' + post.id).replaceWith(`
                                <div id="post-${post.id}" class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>${post.name} <small class="text-muted">by ${post.author}</small></h5>
                                        <p>${post.content}</p>
                                        <small class="text-muted">${post.date}</small>
                                    </div>
                                    <div>
                                        ${post.image ? `<img src="/storage/${post.image}" width="100" class="mb-2">` : ''}
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${post.id}" data-name="${post.name}" data-author="${post.author}" data-content="${post.content}" data-image="${post.image}" data-date="${post.date}">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${post.id}">Delete</button>
                                    </div>
                                </div>
                            `);
                            $('#editPostModal').modal('hide');
                        }
                    },
                    error: function () {
                        alert('Error updating post');
                    }
                });
            });
    
            // Delete Post functionality
            $(document).on('click', '.delete-btn', function () {
                var postId = $(this).data('id');
                if (confirm('Are you sure you want to delete this post?')) {
                    $.ajax({
                        url: `/posts/${postId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                $('#post-' + postId).remove();
                            }
                        },
                        error: function () {
                            alert('Error deleting post');
                        }
                    });
                }
            });
    
            // Search functionality
            $('#search-input').on('keyup', function () {
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('posts.index') }}",
                    type: 'GET',
                    data: { search: query },
                    success: function (response) {
                        if (response.success) {
                            $('#posts-container').html(response.html);
                        }
                    },
                    error: function () {
                        alert('Error searching posts');
                    }
                });
            });
        });
    </script>
    
    
</body>
</html>

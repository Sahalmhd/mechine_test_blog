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
            url: '/posts', // Dynamically set the URL (no Blade syntax here)
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
            url: '/posts',  // Set the URL for the GET request to the posts index route
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
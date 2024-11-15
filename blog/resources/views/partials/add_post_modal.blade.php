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

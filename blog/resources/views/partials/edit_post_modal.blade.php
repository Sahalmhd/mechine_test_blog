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
                    
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit-name" placeholder="Name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-content" class="form-label">Content</label>
                        <textarea class="form-control" name="content" id="edit-content" placeholder="Content" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit-image" class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="edit-image">
                    </div>

                    <div class="mb-3" id="existing-image" style="display: none;">
                        <label class="form-label">Existing Image</label><br>
                        <img id="image-preview" src="" alt="Existing Image" class="img-fluid" style="max-width: 200px;">
                    </div>

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

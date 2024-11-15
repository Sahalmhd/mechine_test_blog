@foreach($posts as $post)
    <div id="post-{{ $post->id }}" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
            <h5>{{ $post->name }} <small class="text-muted">by {{ $post->author }}</small></h5>
            <p>{{ $post->content }}</p>
            <small class="text-muted">{{ $post->created_at->format('Y-m-d H:i:s') }}</small>
        </div>
        <div>
            @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" width="100" class="mb-2">
            @endif
            <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $post->id }}" data-name="{{ $post->name }}" data-author="{{ $post->author }}" data-date="{{ $post->created_at->format('Y-m-d') }}" data-content="{{ $post->content }}" data-image="{{ $post->image }}">Edit</button>
            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $post->id }}">Delete</button>
        </div>
    </div>
@endforeach

<!-- Pagination -->
<div class="mt-3">
    {{ $posts->links() }}
</div>

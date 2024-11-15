@extends('layouts.app')

@section('title', 'Post CRUD')

@section('content')
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

<!-- Modals -->
@include('partials.add_post_modal')
@include('partials.edit_post_modal')
@endsection

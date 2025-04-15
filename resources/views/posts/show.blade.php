@extends('layouts.new')

@section('title', $post->title ?? 'Post Detail')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-4xl mx-auto">
            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover">

            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $post->title }}</h1>
                    <div class="flex space-x-2 text-sm text-gray-600">
                        <span>{{ $post->created_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>{{ $post->user->name ?? 'Anonymous' }}</span>
                    </div>
                </div>

                <div class="prose max-w-none mb-6">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                    <div class="flex space-x-4 text-sm text-gray-600">
                        <span>{{ $post->views }} views</span>
                        <span>{{ $post->likes }} likes</span>
                    </div>
                    <div class="flex items-center">
                        <form action="{{ route('posts.like', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center {{ in_array($post->id, session('liked_posts', [])) ? 'text-red-500' : 'text-gray-600 hover:text-red-500' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 16.828l-6.828-6.828a4 4 0 010-5.656z" />
                                </svg>
                                {{ in_array($post->id, session('liked_posts', [])) ? 'Liked' : 'Like' }}
                            </button>
                        </form>
                    </div>
                </div>

                @if ($post->status)
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <div class="font-medium text-blue-700">Status: {{ ucfirst(str_replace('_', ' ', $post->status)) }}
                        </div>
                        @if ($post->status_note)
                            <div class="mt-1 text-blue-600">{{ $post->status_note }}</div>
                        @endif
                    </div>
                @endif

                <!-- Comments section -->
                <div class="mt-8">
                    <h2 class="text-xl font-semibold mb-4">Comments</h2>

                    @if ($post->comments && $post->comments->count() > 0)
                        @foreach ($post->comments as $comment)
                            <div class="border-b border-gray-200 py-4">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ $comment->user->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="mt-2 text-gray-700">{{ $comment->comment }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No comments yet.</p>
                    @endif

                    @auth
                        <div class="mt-6">
                            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add a
                                        comment</label>
                                    <textarea name="comment" id="comment" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required></textarea>
                                </div>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                    Submit Comment
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-6">
                            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login to add a comment</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection

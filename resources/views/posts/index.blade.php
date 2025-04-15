@extends('layouts.new')

@section('title', 'All Posts - Pengaduan Masyarakat')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Posts</h1>

            @auth
                <a href="{{ route('posts.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    New Post
                </a>
            @endauth
        </div>

        <!-- Enhanced Filter Section -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('posts.index') }}" class="flex flex-wrap gap-4 items-end">
                <!-- Search Filter -->
                <div class="w-full md:w-1/4">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Search by title or content"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Province Filter -->
                <div class="w-full md:w-1/6">
                    <label for="province_id" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                    <select name="province_id" id="province_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Provinces</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->id }}"
                                {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="w-full md:w-1/6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                        </option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>


                <!-- Sort By -->
                <div class="w-full md:w-1/6">
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" id="sort"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="latest" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>
                            Latest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="most_views" {{ request('sort') == 'most_views' ? 'selected' : '' }}>Most Views
                        </option>
                        <option value="most_likes" {{ request('sort') == 'most_likes' ? 'selected' : '' }}>Most Likes
                        </option>
                    </select>
                </div>

                <!-- Apply/Reset Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Apply Filters
                    </button>
                    <a href="{{ route('posts.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="mb-4 text-gray-600">
            Showing {{ $posts->count() }} of {{ $posts->total() }} posts
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <div class="bg-white shadow-md rounded-lg overflow-hidden flex flex-col">
                    <img src="{{ $post->image_path }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col justify-between flex-grow">
                        <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
                        <p class="text-gray-600 text-sm mb-2">
                            {{ Str::limit($post->content, 100) }}
                        </p>
                        <p class="mb-2 font-bold">{{ $post->province->name }}</p>
                        <div class="flex justify-between items-center text-gray-600 text-sm mb-4">
                            <span>{{ $post->views }} views</span>
                            <span>{{ $post->likes }} likes</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('posts.show', $post->id) }}"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Read More
                            </a>
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

                            <!-- Bottom-Right Corner -->
                            @auth
                                <div class="fixed bottom-6 right-6">
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full">
                                        <a href="{{ route('posts.create') }}">
                                            New Post
                                        </a>
                                    </button>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="justify-between items-center">
            <!-- Empty State -->
            @if ($posts->isEmpty())
                <div class="bg-white shadow-md rounded-lg p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-medium text-gray-700 mb-1">No posts found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your search or filter to find what you're
                        looking for.</p>
                    <a href="{{ route('posts.index') }}" class="text-blue-500 hover:underline">Clear all
                        filters</a>
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-6">
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection

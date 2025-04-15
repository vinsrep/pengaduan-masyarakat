<x-app-layout>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-4xl mx-auto">
        <div class="flex justify-between items-center bg-gray-50 px-6 py-3">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.posts.index') }}" class="text-blue-500 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Posts
                </a>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.posts.edit', $post->id) }}" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">
                    Edit Post
                </a>
                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        @if($post->image_path)
        <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover">
        @endif

        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $post->title }}</h1>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Author:</span> {{ $post->user->name ?? 'Anonymous' }}
                    </div>
                    <div>
                        <span class="font-medium">Province:</span> {{ $post->province->name ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Created:</span> {{ $post->created_at->format('M d, Y H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $post->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $post->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $post->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $post->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="prose max-w-none mb-6 border-b border-gray-200 pb-6">
                {!! nl2br(e($post->content)) !!}
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex space-x-4 text-sm text-gray-600">
                    <span>{{ $post->views }} views</span>
                    <span>{{ $post->likes }} likes</span>
                </div>
            </div>

            <!-- Update Status Section -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h2 class="text-lg font-semibold mb-3">Update Status</h2>
                <form action="{{ route('admin.posts.update-status', $post->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="pending" {{ $post->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $post->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $post->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="status_note" class="block text-sm font-medium text-gray-700 mb-1">Status Note</label>
                            <textarea name="status_note" id="status_note" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $post->status_note }}</textarea>
                        </div>
                    </div>

                    <div class="mt-3 flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Comments section -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-4">Comments ({{ $post->comments->count() }})</h2>

                @if ($post->comments && $post->comments->count() > 0)
                    <div class="space-y-4">
                    @foreach ($post->comments as $comment)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between">
                                <span class="font-medium">{{ $comment->user->name }}</span>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="mt-2 text-gray-700">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No comments yet.</p>
                @endif

                <!-- Add Comment -->
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium mb-3">Add Admin Comment</h3>
                    <form action="{{ route('admin.comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <div class="mb-4">
                            <textarea name="comment" id="comment" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required></textarea>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Add Comment as Admin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>

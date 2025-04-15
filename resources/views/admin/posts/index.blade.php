<x-app-layout>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Post Management</h1>
        <div class="flex space-x-6">
            <a href="{{ route('admin.posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Add New Post
            </a>
            <a href="{{ route('admin.posts.export', request()->query()) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Export to Excel
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="w-full md:w-1/5">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Search by title or content"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="w-full md:w-1/5">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

            <div class="w-full md:w-1/5">
                <label for="province_id" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                <select name="province_id" id="province_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Provinces</option>
                    @foreach(\App\Models\Province::all() as $province)
                        <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-1/5">
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" id="sort"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="latest" {{ !request('sort') || request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="most_liked" {{ request('sort') == 'most_liked' ? 'selected' : '' }}>Most Liked</option>
                    <option value="most_viewed" {{ request('sort') == 'most_viewed' ? 'selected' : '' }}>Most Viewed</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Filter
                </button>
                <a href="{{ route('admin.posts.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Author
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Province
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Stats
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($posts as $post)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $post->user->name ?? 'Unknown' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $post->province->name ?? 'Unknown' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $post->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $post->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $post->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $post->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $post->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">
                            <span class="mr-2">{{ $post->views }} views</span>
                            <span>{{ $post->likes }} likes</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.posts.show', $post->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            View
                        </a>
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            Edit
                        </a>
                        <form class="inline-block" action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
</x-app-layout>

<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Create New Post</h1>
                <a href="{{ route('admin.posts.index') }}" class="text-blue-500 hover:underline">
                    Back to Posts
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- User Selection (admin) -->
                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User (Post Author)</label>
                    <select name="user_id" id="user_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Select a User</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <!-- Province Selection -->
                <div class="mb-6">
                    <label for="province_id" class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                    <select name="province_id" id="province_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Select a Province</option>
                        @foreach(\App\Models\Province::all() as $province)
                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }} selected>Pending</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <!-- Status Note -->
                <div class="mb-6">
                    <label for="status_note" class="block text-sm font-medium text-gray-700 mb-2">Status Note</label>
                    <textarea name="status_note" id="status_note" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('status_note') }}</textarea>
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea name="content" id="content" rows="8"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              required>{{ old('content') }}</textarea>
                </div>

                <!-- Image Upload -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Image (Optional)
                    </label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">
                        Upload an image related to your post (JPEG, PNG, up to 2MB)
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.posts.index') }}" class="text-gray-500 hover:text-gray-700">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Create Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>

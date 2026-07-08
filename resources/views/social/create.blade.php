<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">✏️ Create New Post</h2>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Title</label>
                    <input type="text" name="title" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Post title...">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Content</label>
                    <textarea name="content" rows="6" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="What's on your mind?" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Upload Media</label>
                    <input type="file" name="media" class="w-full" accept="image/*,video/*">
                    <p class="text-sm text-gray-500 mt-1">Supported: JPG, PNG, GIF, MP4, MOV, AVI (Max 20MB)</p>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl font-semibold hover:opacity-90 transition">
                    Publish Post 🚀
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
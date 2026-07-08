<x-app-layout>
    <style>
        .social-container {
            max-width: 720px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .social-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .social-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #0d47a1;
        }

        .social-header p {
            color: #1565c0;
            opacity: 0.8;
            font-size: 0.95rem;
        }

        .create-post-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            text-align: center;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px -8px rgba(21, 101, 192, 0.3);
            margin-bottom: 1.5rem;
        }

        .create-post-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px -10px rgba(21, 101, 192, 0.4);
            color: white;
        }

        .post-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 40px -10px rgba(13, 71, 161, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .post-card:hover {
            box-shadow: 0 15px 50px -12px rgba(13, 71, 161, 0.1);
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .post-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .post-user {
            font-weight: 600;
            color: #0d47a1;
            font-size: 1rem;
        }

        .post-user-role {
            font-size: 0.7rem;
            color: #94a3b8;
            background: rgba(21, 101, 192, 0.04);
            padding: 1px 10px;
            border-radius: 12px;
        }

        .post-time {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .post-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .post-content {
            color: #1e293b;
            line-height: 1.7;
            margin-bottom: 12px;
        }

        /* ===== MEDIA - CLEAR & VISIBLE ===== */
        .post-media {
            margin-bottom: 12px;
            border-radius: 16px;
            overflow: hidden;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 500px;
        }

        .post-media img {
            width: 100%;
            height: 100%;
            max-height: 500px;
            object-fit: contain;
            display: block;
        }

        .post-media video {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            display: block;
            border-radius: 16px;
        }

        .post-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(0, 0, 0, 0.04);
            flex-wrap: wrap;
        }

        .post-actions button {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 6px 10px;
            border-radius: 10px;
            font-family: inherit;
        }

        .post-actions button:hover {
            background: rgba(21, 101, 192, 0.04);
            color: #0d47a1;
        }

        .post-actions .like-btn.liked {
            color: #ef4444;
        }

        .post-actions .btn-edit {
            color: #2563eb;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.9rem;
            padding: 6px 10px;
            border-radius: 10px;
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .post-actions .btn-edit:hover {
            background: rgba(37, 99, 235, 0.08);
            color: #1d4ed8;
        }

        .post-actions .btn-delete {
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.9rem;
            padding: 6px 10px;
            border-radius: 10px;
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .post-actions .btn-delete:hover {
            background: rgba(220, 38, 38, 0.08);
            color: #b91c1c;
        }

        .comments-section {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(0, 0, 0, 0.04);
        }

        .comment-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 0.9rem;
            padding: 6px 10px;
            border-radius: 12px;
            background: rgba(21, 101, 192, 0.02);
        }

        .comment-item .comment-user {
            font-weight: 600;
            color: #0d47a1;
            flex-shrink: 0;
        }

        .comment-item .comment-text {
            color: #1e293b;
        }

        .comment-input-area {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .comment-input-area input {
            flex: 1;
            padding: 10px 14px;
            border: 1.5px solid rgba(21, 101, 192, 0.08);
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .comment-input-area input:focus {
            border-color: #1565c0;
            box-shadow: 0 0 0 4px rgba(21, 101, 192, 0.06);
        }

        .comment-input-area button {
            padding: 10px 20px;
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .comment-input-area button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(21, 101, 192, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #0d47a1;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .post-stats {
            display: flex;
            gap: 16px;
            font-size: 0.8rem;
            color: #94a3b8;
            padding-top: 8px;
        }

        .post-stats span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .alert-success {
            background: #dcfce7;
            border-left: 4px solid #22c55e;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 1rem;
            color: #166534;
        }

        @media (max-width: 480px) {
            .social-container {
                padding: 1rem 0.75rem;
            }

            .post-card {
                padding: 1rem;
            }

            .post-actions {
                gap: 6px;
            }

            .comment-input-area {
                flex-wrap: wrap;
            }

            .comment-input-area button {
                width: 100%;
            }

            .post-header {
                flex-wrap: wrap;
            }

            .post-media {
                max-height: 300px;
            }

            .post-media img,
            .post-media video {
                max-height: 300px;
            }
        }
    </style>

    <div class="social-container">
        <!-- Header -->
        <div class="social-header">
            <h1>🌿 Social Feed</h1>
            <p>Connect, share, and support each other on your mental health journey.</p>
        </div>

        <!-- Create Post Button -->
        <a href="{{ route('posts.create') }}" class="create-post-btn">
            ✏️ Share Your Story
        </a>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Posts -->
        @if($posts->count() > 0)
            @foreach($posts as $post)
                <div class="post-card" id="post-{{ $post->id }}">
                    <!-- Post Header -->
                    <div class="post-header">
                        <div class="post-avatar">
                            {{ $post->user->display_name ? strtoupper(substr($post->user->display_name, 0, 1)) : 'U' }}
                        </div>
                        <div>
                            <div class="post-user">
                                {{ $post->user->display_name ?? 'Anonymous' }}
                                <span class="post-user-role">
                                    @if($post->user->role === 'doctor') 👨‍⚕️ Doctor
                                    @elseif($post->user->role === 'admin') ⚙️ Admin
                                    @else 🌱 Member @endif
                                </span>
                            </div>
                            <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <!-- Post Content -->
                    @if($post->title)
                        <div class="post-title">{{ $post->title }}</div>
                    @endif
                    <div class="post-content">{{ $post->content }}</div>

                    <!-- Post Media - CLEAR & VISIBLE -->
                    @if($post->media_url)
                        <div class="post-media">
                            @if($post->media_type === 'image')
                                <img src="{{ asset('storage/' . $post->media_url) }}" alt="Post image" loading="lazy">
                            @elseif($post->media_type === 'video')
                                <video src="{{ asset('storage/' . $post->media_url) }}" controls preload="metadata"></video>
                            @endif
                        </div>
                    @endif

                    <!-- Stats -->
                    <div class="post-stats">
                        <span>❤️ <span id="like-count-{{ $post->id }}">{{ $post->like_count }}</span> likes</span>
                        <span>💬 <span>{{ $post->comment_count }}</span> comments</span>
                    </div>

                    <!-- Actions -->
                    <div class="post-actions">
                        <button onclick="toggleLike({{ $post->id }})" class="like-btn" id="like-btn-{{ $post->id }}">
                            {{ $post->likes->where('user_id', auth()->id())->first() ? '❤️' : '🤍' }}
                            <span>Like</span>
                        </button>

                        <button onclick="toggleComment({{ $post->id }})">
                            💬 <span>Comment</span>
                        </button>

                        <!-- ===== EDIT & DELETE BUTTONS ===== -->
                        @if($post->user_id === auth()->id())
                            <a href="{{ route('posts.edit', $post) }}" class="btn-edit">
                                ✏️ Edit
                            </a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" 
                                  onsubmit="return confirm('⚠️ Are you sure you want to delete this post? This action cannot be undone.')" 
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    🗑️ Delete
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Comments -->
                    <div class="comments-section" id="comments-{{ $post->id }}" style="display: none;">
                        <div id="comment-list-{{ $post->id }}">
                            @foreach($post->comments as $comment)
                                <div class="comment-item">
                                    <span class="comment-user">{{ $comment->user->display_name ?? 'Anonymous' }}</span>
                                    <span class="comment-text">{{ $comment->content }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="comment-input-area">
                            <input type="text" id="comment-input-{{ $post->id }}" placeholder="Write a comment...">
                            <button onclick="submitComment({{ $post->id }})">Post</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="post-card empty-state">
                <div class="icon">🌱</div>
                <h3>No posts yet</h3>
                <p style="color: #94a3b8;">Be the first to share your story with the community.</p>
            </div>
        @endif
    </div>

    <script>
        function toggleLike(postId) {
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById(`like-btn-${postId}`);
                const count = document.getElementById(`like-count-${postId}`);
                
                if (data.action === 'liked') {
                    btn.innerHTML = '❤️ <span>Like</span>';
                    btn.classList.add('liked');
                } else {
                    btn.innerHTML = '🤍 <span>Like</span>';
                    btn.classList.remove('liked');
                }
                count.textContent = data.like_count;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function toggleComment(postId) {
            const section = document.getElementById(`comments-${postId}`);
            if (section.style.display === 'none') {
                section.style.display = 'block';
                setTimeout(() => {
                    document.getElementById(`comment-input-${postId}`).focus();
                }, 100);
            } else {
                section.style.display = 'none';
            }
        }

        function submitComment(postId) {
            const input = document.getElementById(`comment-input-${postId}`);
            const content = input.value.trim();
            if (!content) return;

            fetch(`/posts/${postId}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ content: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const list = document.getElementById(`comment-list-${postId}`);
                    const newComment = document.createElement('div');
                    newComment.className = 'comment-item';
                    newComment.innerHTML = `
                        <span class="comment-user">${data.comment.user_display_name}</span>
                        <span class="comment-text">${data.comment.content}</span>
                    `;
                    list.appendChild(newComment);
                    input.value = '';
                    input.blur();

                    // Update comment count in stats
                    const stats = document.querySelector(`#post-${postId} .post-stats span:last-child`);
                    if (stats) {
                        const currentText = stats.textContent;
                        const match = currentText.match(/\d+/);
                        if (match) {
                            const newCount = parseInt(match[0]) + 1;
                            stats.innerHTML = `💬 <span>${newCount}</span> comments`;
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to post comment. Please try again.');
            });
        }

        // Enter key support for comments
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.comment-input-area input').forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const postId = this.id.replace('comment-input-', '');
                        submitComment(postId);
                    }
                });
            });
        });
    </script>
</x-app-layout>
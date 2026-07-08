<x-app-layout>
    <style>
        .diary-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .diary-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .diary-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #0d47a1;
        }

        .diary-header p {
            color: #1565c0;
            opacity: 0.8;
            font-size: 0.95rem;
        }

        .diary-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 40px -10px rgba(13, 71, 161, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .diary-card:hover {
            box-shadow: 0 15px 50px -12px rgba(13, 71, 161, 0.12);
        }

        .diary-card .form-label {
            font-weight: 600;
            color: #0d47a1;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 6px;
        }

        .diary-card textarea {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid rgba(21, 101, 192, 0.08);
            border-radius: 14px;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
            min-height: 120px;
            font-family: inherit;
            background: rgba(255, 255, 255, 0.8);
            resize: vertical;
        }

        .diary-card textarea:focus {
            border-color: #1565c0;
            box-shadow: 0 0 0 4px rgba(21, 101, 192, 0.06);
        }

        .diary-card .form-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
        }

        .diary-card select {
            padding: 10px 16px;
            border: 1.5px solid rgba(21, 101, 192, 0.08);
            border-radius: 12px;
            font-size: 14px;
            background: white;
            outline: none;
            transition: all 0.3s ease;
            color: #1e293b;
        }

        .diary-card select:focus {
            border-color: #1565c0;
            box-shadow: 0 0 0 4px rgba(21, 101, 192, 0.06);
        }

        .btn-save {
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 30px -8px rgba(21, 101, 192, 0.3);
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px -10px rgba(21, 101, 192, 0.4);
        }

        .btn-save:active {
            transform: scale(0.97);
        }

        .entry-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
            border-left: 5px solid #1565c0;
            box-shadow: 0 4px 20px -8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .entry-item:hover {
            box-shadow: 0 8px 30px -10px rgba(0, 0, 0, 0.06);
        }

        .entry-item .entry-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .entry-item .entry-mood {
            font-size: 1.5rem;
        }

        .entry-item .entry-user {
            font-size: 0.8rem;
            color: #94a3b8;
            background: rgba(21, 101, 192, 0.04);
            padding: 2px 12px;
            border-radius: 20px;
        }

        .entry-item .entry-date {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .entry-item .entry-content {
            margin-top: 8px;
            color: #1e293b;
            line-height: 1.6;
        }

        .entry-item .entry-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(0, 0, 0, 0.04);
        }

        .entry-item .entry-actions .btn-edit {
            color: #2563eb;
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 8px;
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .entry-item .entry-actions .btn-edit:hover {
            background: rgba(37, 99, 235, 0.08);
            color: #1d4ed8;
        }

        .entry-item .entry-actions .btn-delete {
            color: #dc2626;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 8px;
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .entry-item .entry-actions .btn-delete:hover {
            background: rgba(220, 38, 38, 0.08);
            color: #b91c1c;
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

        .section-title {
            font-weight: 700;
            color: #0d47a1;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .mood-badge {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .mood-badge.happy { background: #dcfce7; color: #16a34a; }
        .mood-badge.sad { background: #dbeafe; color: #2563eb; }
        .mood-badge.anxious { background: #fef3c7; color: #d97706; }
        .mood-badge.angry { background: #fee2e2; color: #dc2626; }
        .mood-badge.neutral { background: #f1f5f9; color: #64748b; }

        .alert-success {
            background: #dcfce7;
            border-left: 4px solid #22c55e;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 1rem;
            color: #166534;
        }

        @media (max-width: 480px) {
            .diary-container {
                padding: 1rem 0.75rem;
            }

            .diary-card {
                padding: 1.25rem;
            }

            .diary-card .form-row {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-save {
                width: 100%;
                justify-content: center;
            }

            .entry-item {
                padding: 1rem;
            }

            .entry-item .entry-actions {
                flex-wrap: wrap;
            }
        }
    </style>

    <div class="diary-container">
        <!-- Header -->
        <div class="diary-header">
            <h1>📖 My Diary</h1>
            <p>Your private space to express yourself. Only you and your doctor can see this.</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Create Entry -->
        <div class="diary-card">
            <div class="section-title">✏️ Write New Entry</div>

            <form method="POST" action="{{ route('diary.store') }}">
                @csrf

                <label class="form-label">How are you feeling today?</label>
                <textarea name="content" placeholder="Write your thoughts here..."></textarea>

                <div class="form-row">
                    <div>
                        <label class="form-label" style="margin-bottom: 4px;">Mood</label>
                        <select name="mood">
                            <option value="neutral">😐 Neutral</option>
                            <option value="happy">😊 Happy</option>
                            <option value="sad">😢 Sad</option>
                            <option value="anxious">😰 Anxious</option>
                            <option value="angry">😡 Angry</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-save">
                        💾 Save Entry
                    </button>
                </div>
            </form>
        </div>

        <!-- Entries List -->
        @if($diaries->count() > 0)
            @foreach($diaries as $diary)
                <div class="entry-item" style="border-left-color: 
                    @if($diary->mood == 'happy') #22c55e
                    @elseif($diary->mood == 'sad') #3b82f6
                    @elseif($diary->mood == 'anxious') #eab308
                    @elseif($diary->mood == 'angry') #ef4444
                    @else #94a3b8 @endif;">
                    
                    <div class="entry-header">
                        <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span class="entry-mood">
                                @if($diary->mood == 'happy') 😊
                                @elseif($diary->mood == 'sad') 😢
                                @elseif($diary->mood == 'anxious') 😰
                                @elseif($diary->mood == 'angry') 😡
                                @else 😐 @endif
                            </span>
                            <span class="mood-badge 
                                @if($diary->mood == 'happy') happy
                                @elseif($diary->mood == 'sad') sad
                                @elseif($diary->mood == 'anxious') anxious
                                @elseif($diary->mood == 'angry') angry
                                @else neutral @endif">
                                {{ ucfirst($diary->mood ?? 'neutral') }}
                            </span>
                            @if(Auth::user()->role === 'doctor')
                                <span class="entry-user">👤 {{ $diary->user->display_name ?? $diary->user->name }}</span>
                            @endif
                        </div>
                        <span class="entry-date">{{ $diary->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="entry-content">{{ $diary->content }}</div>

                    <!-- ===== EDIT & DELETE BUTTONS ===== -->
                    @if($diary->user_id === auth()->id() || Auth::user()->role === 'doctor')
                        <div class="entry-actions">
                            @if($diary->user_id === auth()->id())
                                <a href="{{ route('diary.edit', $diary) }}" class="btn-edit">
                                    ✏️ Edit
                                </a>
                            @endif
                            
                            @if($diary->user_id === auth()->id() || Auth::user()->role === 'doctor')
                                <form method="POST" action="{{ route('diary.destroy', $diary) }}" 
                                      onsubmit="return confirm('⚠️ Are you sure you want to delete this diary entry? This action cannot be undone.')" 
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        🗑️ Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="diary-card empty-state">
                <div class="icon">📝</div>
                <h3>No diary entries yet</h3>
                <p>Write your first entry above to start your journal.</p>
            </div>
        @endif
    </div>
</x-app-layout>
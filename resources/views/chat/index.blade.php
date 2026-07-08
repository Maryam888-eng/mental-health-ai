<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="bg-light py-4 min-vh-100">
        <div class="container-fluid px-4">
            <div class="row g-4">

                <div class="col-12 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="alert alert-primary small">
                                <strong>Step 1:</strong> Click <b>New Conversation</b><br>
                                <strong>Step 2:</strong> Type or choose a prompt<br>
                                <strong>Step 3:</strong> See your message + AI replies
                            </div>

                            <button id="newChat" class="btn btn-primary w-100 mb-4">
                                + New Conversation
                            </button>

                            <h6 class="fw-bold">User Prompts</h6>

                            <div class="d-grid gap-2 mb-4">
                                @foreach([
                                    'I feel anxious before exams. What can I do?',
                                    'I cannot sleep well because I keep overthinking.',
                                    'I feel stressed and overwhelmed with my studies.',
                                    'Can you give me a simple breathing exercise?',
                                    'How can I improve my mood today?',
                                    'I feel lonely. What should I do?'
                                ] as $prompt)
                                    <button class="suggestedQuestion btn btn-outline-secondary text-start btn-sm">
                                        {{ $prompt }}
                                    </button>
                                @endforeach
                            </div>

                            <h6 class="fw-bold">Your Conversations</h6>

                            <div id="conversationList" class="list-group">
                                @foreach($conversations as $conversation)
                                    <div class="conversation-row list-group-item d-flex justify-content-between align-items-center"
                                         data-id="{{ $conversation->id }}">
                                        <button class="conversationBtn btn btn-link text-start text-decoration-none p-0">
                                            {{ $conversation->title }}
                                        </button>

                                        <button class="deleteConversation btn btn-sm btn-outline-danger">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-9">
                    <div class="card shadow-sm d-flex flex-column" style="height: 78vh;">
                        <div class="card-header bg-white">
                            <h5 class="mb-1 fw-bold">Mental Health Multi-AI Chat</h5>
                            <div id="chatStatus" class="text-muted small">
                                No conversation selected. Click “New Conversation” or choose a user prompt.
                            </div>
                        </div>

                        <div id="chatArea" class="card-body overflow-auto bg-light">
                            <div id="emptyState" class="h-100 d-flex align-items-center justify-content-center text-center">
                                <div>
                                    <div class="display-4 mb-3">💬</div>
                                    <h4 class="fw-bold">Start a conversation</h4>
                                    <p class="text-muted">
                                        Click <b>New Conversation</b>, type your own message, or choose a user prompt.
                                    </p>
                                    <button id="emptyNewChat" class="btn btn-primary">
                                        Start Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white">
                            <form id="chatForm" class="d-flex gap-2">
                                @csrf

                                <input
                                    id="messageInput"
                                    class="form-control"
                                    placeholder="Start a conversation first..."
                                    autocomplete="off"
                                    disabled
                                >

                                <button id="sendBtn" class="btn btn-secondary" disabled>
                                    Send
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        let activeConversationId = null;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }
        function formatResponse(text) {
    if (!text) return '';

    text = escapeHtml(text);

    // Bold text (**text**)
    text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

    // Bullet points
    text = text.replace(/^- (.*)$/gm, '• $1');

    // New lines
    text = text.replace(/\n/g, '<br>');

    return text;
}

        function scrollBottom() {
            $('#chatArea').scrollTop($('#chatArea')[0].scrollHeight);
        }

        function enableChat() {
            $('#messageInput')
                .prop('disabled', false)
                .attr('placeholder', 'Write your message...');

            $('#sendBtn')
                .prop('disabled', false)
                .removeClass('btn-secondary')
                .addClass('btn-primary');

            $('#chatStatus').text('Conversation active. Type your message below.');
            $('#emptyState').remove();
        }

        function disableChat() {
            activeConversationId = null;

            $('#messageInput')
                .prop('disabled', true)
                .attr('placeholder', 'Start a conversation first...');

            $('#sendBtn')
                .prop('disabled', true)
                .removeClass('btn-primary')
                .addClass('btn-secondary');

            $('#chatStatus').text('No conversation selected.');
        }

        function highlightConversation(id) {
            $('.conversation-row').removeClass('active');
            $(`.conversation-row[data-id="${id}"]`).addClass('active');
        }

        function appendUserMessage(content) {
            $('#chatArea').append(`
                <div class="d-flex justify-content-end mb-3">
                    <div style="max-width:75%;">
                        <div class="small text-muted text-end mb-1">You</div>
                        <div class="bg-primary text-white rounded-4 px-3 py-2">
                            ${escapeHtml(content)}
                        </div>
                    </div>
                </div>
            `);

            scrollBottom();
        }

       function appendAiResponse(response) {
$('#chatArea').append(` <div class="d-flex justify-content-start mb-3"> <div class="card shadow-sm border-0" style="max-width:90%;"> <div class="card-body">


                <div class="text-dark"
                     style="white-space:pre-wrap;
                            word-break:break-word;
                            line-height:1.8;">
                    ${formatResponse(response.response || '')}
                </div>

                ${response.error_message ? `
                    <div class="alert alert-danger mt-3 mb-0 small"
                         style="white-space:pre-wrap;">
                        ${escapeHtml(response.error_message)}
                    </div>
                ` : ''}

            </div>
        </div>
    </div>
`);

scrollBottom();

}


        function appendInfoMessage(content) {
            $('#chatArea').append(`
                <div class="alert alert-info mb-3">
                    ${escapeHtml(content)}
                </div>
            `);

            scrollBottom();
        }

        function appendSafetyMessage(content) {
            $('#chatArea').append(`
                <div class="alert alert-danger mb-3" style="white-space:pre-wrap;">
                    ${escapeHtml(content)}
                </div>
            `);

            scrollBottom();
        }

        function appendLoading() {
            $('#chatArea').append(`
                <div id="loadingBox" class="alert alert-warning mb-3">
                    <strong>Working...</strong><br>
                    Sending your message to the active AI provider.
                </div>
            `);

            scrollBottom();
        }

        function removeLoading() {
            $('#loadingBox').remove();
        }

      function createConversation(callback = null) {
const startUrl = "{{ route('chat.start') }}";
$.post(startUrl, function (conversation) {
    activeConversationId = conversation.id;

    $('#chatArea').html('');
    enableChat();

    $('#conversationList').prepend(`
        <div class="conversation-row list-group-item d-flex justify-content-between align-items-center active"
             data-id="${conversation.id}">
            <button class="conversationBtn btn btn-link text-start text-decoration-none p-0">
                ${escapeHtml(conversation.title)}
            </button>

            <button class="deleteConversation btn btn-sm btn-outline-danger">
                ×
            </button>
        </div>
    `);

    highlightConversation(conversation.id);
    $('#messageInput').focus();

    if (typeof callback === 'function') {
        callback(conversation);
    }
});


}


        function sendMessage(message) {
            if (!message || !activeConversationId) {
                return;
            }

            $('#messageInput').val('');
            appendUserMessage(message);
            appendLoading();

            $.post(`/chat/${activeConversationId}/send`, {
                message: message
            }).done(function (data) {
                removeLoading();

                if (data.crisis) {
                    appendSafetyMessage(data.message);
                    return;
                }

                if (!data.responses || data.responses.length === 0) {
                    appendInfoMessage('No AI responses were returned. Please check active providers.');
                    return;
                }

                data.responses.forEach(function (response) {
                    appendAiResponse(response);
                });
            }).fail(function () {
                removeLoading();
                appendSafetyMessage('Something went wrong while contacting the AI provider.');
            });
        }

        function renderConversation(data) {
            $('#chatArea').html('');
            $('#chatStatus').text('Conversation active. Type your message below.');

            const messages = data.messages || [];

            if (messages.length === 0) {
                appendInfoMessage('This conversation is empty. Type your first message below.');
                return;
            }

            messages.forEach(function (message) {
                if (message.role === 'user') {
                    appendUserMessage(message.content);

                    const responses = message.ai_responses || [];

                    responses.forEach(function (response) {
                        appendAiResponse(response);
                    });
                }

                if (message.role === 'assistant') {
                    appendSafetyMessage(message.content);
                }
            });
        }

        $('#newChat, #emptyNewChat').on('click', function () {
            createConversation(function () {
                appendInfoMessage('New conversation created. Type your first message below.');
            });
        });

        $(document).on('click', '.suggestedQuestion', function () {
            const question = $(this).text().trim();

            if (!activeConversationId) {
                createConversation(function () {
                    sendMessage(question);
                });

                return;
            }

            sendMessage(question);
        });

        $(document).on('click', '.conversationBtn', function () {
            const id = $(this).closest('.conversation-row').data('id');

            activeConversationId = id;
            $('#chatArea').html('');
            enableChat();
            highlightConversation(id);

            $('#chatStatus').text('Loading conversation...');

            $.get(`/chat/${id}`, function (data) {
                renderConversation(data);
            }).fail(function () {
                $('#chatArea').html('');
                appendSafetyMessage('Could not load this conversation.');
            });
        });

        $(document).on('click', '.deleteConversation', function () {
            const row = $(this).closest('.conversation-row');
            const id = row.data('id');

            if (!confirm('Delete this conversation?')) {
                return;
            }

            $.ajax({
                url: `/chat/${id}`,
                method: 'DELETE',
                success: function () {
                    row.remove();

                    if (activeConversationId === id) {
                        disableChat();

                        $('#chatArea').html(`
                            <div class="h-100 d-flex align-items-center justify-content-center text-center">
                                <div>
                                    <div class="display-4 mb-3">💬</div>
                                    <h4 class="fw-bold">Select or start a conversation</h4>
                                    <p class="text-muted">Click New Conversation or choose another conversation.</p>
                                </div>
                            </div>
                        `);
                    }
                }
            });
        });

        $('#chatForm').on('submit', function (e) {
            e.preventDefault();

            const message = $('#messageInput').val().trim();

            if (!message) {
                return;
            }

            if (!activeConversationId) {
                alert('Please create or select a conversation first.');
                return;
            }

            sendMessage(message);
        });
    </script>
</x-app-layout>
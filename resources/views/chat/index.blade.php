<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Application</title>
    <!-- Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }
        .navbar {
            background-color: #4a5568;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .navbar-nav {
            display: flex;
            align-items: center;
        }
        .nav-item {
            margin-left: 20px;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-toggle {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 16px;
        }
        .dropdown-toggle:focus {
            outline: none;
        }
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
            min-width: 160px;
            z-index: 1;
        }
        .dropdown-menu.show {
            display: block;
        }
        .dropdown-item {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .dropdown-divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 5px 0;
        }
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #3182ce;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 8px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .chat-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: calc(100vh - 180px);
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            background-color: #4a5568;
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .online-indicator {
            display: flex;
            align-items: center;
            font-size: 14px;
        }
        .online-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #48bb78;
            margin-right: 5px;
        }
        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            display: flex;
            flex-direction: column;
        }
        .message {
            margin-bottom: 15px;
            max-width: 80%;
            display: flex;
            flex-direction: column;
        }
        .message-content {
            padding: 12px 16px;
            border-radius: 18px;
            display: inline-block;
            word-break: break-word;
        }
        .message-header {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
        }
        .message-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #3182ce;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 10px;
            margin-right: 8px;
        }
        .message-user {
            font-weight: bold;
            font-size: 13px;
        }
        .message-time {
            font-size: 11px;
            color: #718096;
            margin-top: 4px;
        }
        .message.sent {
            align-self: flex-end;
        }
        .message.sent .message-header {
            justify-content: flex-end;
        }
        .message.sent .message-avatar {
            order: 2;
            margin-right: 0;
            margin-left: 8px;
            background-color: #4299e1;
        }
        .message.sent .message-content {
            background-color: #4299e1;
            color: white;
        }
        .message.received .message-content {
            background-color: #e2e8f0;
            color: #2d3748;
        }
        .chat-input {
            display: flex;
            padding: 15px;
            border-top: 1px solid #e2e8f0;
        }
        .chat-input input {
            flex-grow: 1;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            margin-right: 10px;
            font-size: 16px;
        }
        .chat-input input:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        }
        .chat-input button {
            padding: 12px 20px;
            background-color: #4299e1;
            color: white;
            border: none;
            border-radius: 24px;
            cursor: pointer;
            font-weight: 600;
        }
        .chat-input button:hover {
            background-color: #3182ce;
        }
        .logout-form {
            margin: 0;
            padding: 0;
        }
        .logout-button {
            background: none;
            border: none;
            color: #333;
            cursor: pointer;
            text-align: left;
            width: 100%;
            padding: 10px 15px;
            font-size: 16px;
        }
        .logout-button:hover {
            background-color: #f8f9fa;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            .chat-container {
                height: calc(100vh - 150px);
            }
            .message {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="navbar-brand">Chat App</a>
        <div class="navbar-nav">
            <div class="dropdown">
                <button class="dropdown-toggle" onclick="toggleDropdown()">
                    <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    {{ auth()->user()->name }}
                </button>
                <div class="dropdown-menu" id="userDropdown">
                    <a href="#" class="dropdown-item">Profile</a>
                    <a href="#" class="dropdown-item">Settings</a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Chat Room</h1>
        </div>

        <div class="chat-container">
            <div class="chat-header">
                <span>Group Chat</span>
                <div class="online-indicator">
                    <div class="online-dot"></div>
                    Online
                </div>
            </div>
            <div class="chat-messages" id="chat-messages">
                @foreach($messages as $message)
                    <div class="message {{ $message->user_id === auth()->id() ? 'sent' : 'received' }}" data-id="{{ $message->id }}">
                        <div class="message-header">
                            <div class="message-avatar">{{ substr($message->user->name, 0, 1) }}</div>
                            <div class="message-user">{{ $message->user->name }}</div>
                        </div>
                        <div class="message-content">{{ $message->content }}</div>
                        <div class="message-time">{{ $message->created_at->format('M d, H:i') }}</div>
                    </div>
                @endforeach
            </div>
            <div class="chat-input">
                <input type="text" id="message-input" placeholder="Type your message...">
                <button id="send-button">Send</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('chat-messages');
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');
            let lastMessageId = 0;

            // Toggle dropdown menu
            window.toggleDropdown = function() {
                const dropdown = document.getElementById('userDropdown');
                dropdown.classList.toggle('show');
            }

            // Close dropdown when clicking outside
            window.addEventListener('click', function(event) {
                if (!event.target.matches('.dropdown-toggle') && !event.target.closest('.dropdown-toggle')) {
                    const dropdowns = document.getElementsByClassName('dropdown-menu');
                    for (let i = 0; i < dropdowns.length; i++) {
                        const openDropdown = dropdowns[i];
                        if (openDropdown.classList.contains('show')) {
                            openDropdown.classList.remove('show');
                        }
                    }
                }
            });

            // Scroll to bottom of messages
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Initial scroll to bottom
            scrollToBottom();

            // Get the last message ID
            const messages = document.querySelectorAll('.message');
            if (messages.length > 0) {
                const lastMessage = messages[messages.length - 1];
                lastMessageId = lastMessage.dataset.id || 0;
            }

            // Send message function
            function sendMessage() {
                const content = messageInput.value.trim();
                if (!content) return;

                fetch('{{ route('chat.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ content })
                })
                .then(response => response.json())
                .then(data => {
                    addMessage(data);
                    messageInput.value = '';
                    scrollToBottom();
                })
                .catch(error => console.error('Error:', error));
            }

            // Add message to the chat
            function addMessage(message) {
                const messageElement = document.createElement('div');
                messageElement.className = `message ${message.user_id === {{ auth()->id() }} ? 'sent' : 'received'}`;
                messageElement.dataset.id = message.id;

                // Create message header with avatar and username
                const headerElement = document.createElement('div');
                headerElement.className = 'message-header';

                const avatarElement = document.createElement('div');
                avatarElement.className = 'message-avatar';
                avatarElement.textContent = message.user.name.charAt(0);

                const userElement = document.createElement('div');
                userElement.className = 'message-user';
                userElement.textContent = message.user.name;

                headerElement.appendChild(avatarElement);
                headerElement.appendChild(userElement);

                // Create message content
                const contentElement = document.createElement('div');
                contentElement.className = 'message-content';
                contentElement.textContent = message.content;

                // Create message timestamp
                const timeElement = document.createElement('div');
                timeElement.className = 'message-time';

                // Format the date
                const date = new Date(message.created_at);
                const formattedDate = `${date.toLocaleString('default', { month: 'short' })} ${date.getDate()}, ${date.getHours()}:${date.getMinutes().toString().padStart(2, '0')}`;
                timeElement.textContent = formattedDate;

                // Assemble the message
                messageElement.appendChild(headerElement);
                messageElement.appendChild(contentElement);
                messageElement.appendChild(timeElement);

                // If it's a sent message, adjust the avatar position
                if (message.user_id === {{ auth()->id() }}) {
                    headerElement.style.justifyContent = 'flex-end';
                    avatarElement.style.order = '2';
                    avatarElement.style.marginRight = '0';
                    avatarElement.style.marginLeft = '8px';
                    avatarElement.style.backgroundColor = '#4299e1';
                }

                messagesContainer.appendChild(messageElement);
                lastMessageId = message.id;
            }

            // Poll for new messages
            function pollMessages() {
                fetch(`{{ route('chat.messages') }}?last_id=${lastMessageId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(message => {
                                addMessage(message);
                            });
                            scrollToBottom();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Set up event listeners
            sendButton.addEventListener('click', sendMessage);

            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            // Focus input field on page load
            messageInput.focus();

            // Poll for new messages every 3 seconds
            setInterval(pollMessages, 3000);
        });
    </script>
</body>
</html>

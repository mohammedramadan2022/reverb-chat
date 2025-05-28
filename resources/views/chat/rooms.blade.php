<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Rooms</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4299e1;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
        }
        .btn:hover {
            background-color: #3182ce;
        }
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .room-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .room-header {
            background-color: #4a5568;
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .room-body {
            padding: 15px;
        }
        .room-user {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .room-user-name {
            font-weight: 600;
            margin-left: 10px;
        }
        .room-footer {
            padding: 15px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .room-time {
            font-size: 12px;
            color: #718096;
        }
        .room-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .no-rooms {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            margin-top: 20px;
        }
        .no-rooms h3 {
            margin-bottom: 15px;
            color: #4a5568;
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
            <h1>Chat Rooms</h1>
            <a href="{{ route('chat.rooms.create') }}" class="btn">Start New Chat</a>
        </div>

        @if(count($chatRooms) > 0)
            <div class="rooms-grid">
                @foreach($chatRooms as $room)
                    <a href="{{ route('chat.room', $room->id) }}" class="room-link">
                        <div class="room-card">
                            <div class="room-header">
                                <div class="user-avatar">
                                    @php
                                        $otherUser = $room->getOtherUser(auth()->id());
                                        $initial = $otherUser ? substr($otherUser->name, 0, 1) : '?';
                                    @endphp
                                    {{ $initial }}
                                </div>
                                <span>Chat with {{ $otherUser ? $otherUser->name : 'Unknown User' }}</span>
                            </div>
                            <div class="room-body">
                                <div class="room-user">
                                    <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                                    <div class="room-user-name">{{ auth()->user()->name }}</div>
                                </div>
                                <div class="room-user">
                                    <div class="user-avatar">{{ $initial }}</div>
                                    <div class="room-user-name">{{ $otherUser ? $otherUser->name : 'Unknown User' }}</div>
                                </div>
                            </div>
                            <div class="room-footer">
                                <div class="room-time">Created: {{ $room->created_at->format('M d, Y') }}</div>
                                <div class="btn">Open Chat</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="no-rooms">
                <h3>No Chat Rooms Yet</h3>
                <p>Start a new chat to begin messaging with other users.</p>
                <a href="{{ route('chat.rooms.create') }}" class="btn">Start New Chat</a>
            </div>
        @endif
    </div>

    <script>
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
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Start New Chat</title>
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
            max-width: 800px;
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
        .btn-secondary {
            background-color: #718096;
        }
        .btn-secondary:hover {
            background-color: #4a5568;
        }
        .users-list {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .users-header {
            background-color: #4a5568;
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .users-container {
            padding: 15px;
        }
        .user-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .user-item:last-child {
            border-bottom: none;
        }
        .user-details {
            flex-grow: 1;
            margin-left: 15px;
        }
        .user-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .user-email {
            color: #718096;
            font-size: 14px;
        }
        .user-action {
            margin-left: 15px;
        }
        .no-users {
            padding: 30px;
            text-align: center;
            color: #718096;
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
            <h1>Start New Chat</h1>
            <a href="{{ route('chat.rooms') }}" class="btn btn-secondary">Back to Rooms</a>
        </div>

        <div class="users-list">
            <div class="users-header">
                <h2>Select a User to Chat With</h2>
            </div>
            <div class="users-container">
                @if(count($users) > 0)
                    @foreach($users as $user)
                        <div class="user-item">
                            <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                            <div class="user-details">
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                            <div class="user-action">
                                <form action="{{ route('chat.rooms.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="btn">Start Chat</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-users">
                        <p>No users available to chat with.</p>
                    </div>
                @endif
            </div>
        </div>
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

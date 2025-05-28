<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Chat Application</title>
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
            margin-bottom: 30px;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #4a5568;
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
        }
        .card-footer {
            padding: 15px 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
        }
        .btn {
            display: inline-block;
            background-color: #4299e1;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #3182ce;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            margin-right: 20px;
            text-align: center;
        }
        .stat-card:last-child {
            margin-right: 0;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #4299e1;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #718096;
            font-size: 14px;
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
            .stats {
                flex-direction: column;
            }
            .stat-card {
                margin-right: 0;
                margin-bottom: 15px;
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
            <h1>Welcome, {{ auth()->user()->name }}!</h1>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-value">{{ \App\Models\Message::count() }}</div>
                <div class="stat-label">Total Messages</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ \App\Models\User::count() }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ auth()->user()->messages()->count() }}</div>
                <div class="stat-label">Your Messages</div>
            </div>
        </div>

        <div class="card-grid">
            <div class="card">
                <div class="card-header">
                    Group Chat
                </div>
                <div class="card-body">
                    <p>Join our group chat to communicate with all users in real-time.</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('chat.index') }}" class="btn">Enter Chat</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Your Profile
                </div>
                <div class="card-body">
                    <p>View and update your profile information.</p>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn">View Profile</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Settings
                </div>
                <div class="card-body">
                    <p>Configure your account settings and preferences.</p>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn">Open Settings</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle dropdown menu
        function toggleDropdown() {
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

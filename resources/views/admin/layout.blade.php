<!DOCTYPE html>
<html lang="id">

<head>
    <title>Master Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }

        .sidebar a {
            color: #cfd2d6;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #495057;
            color: white;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3" style="width: 250px;">
            <h4 class="text-white text-center mb-4">Master Admin</h4>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}"
                        class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        👤 Manajemen User
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.lembur') }}"
                        class="{{ request()->routeIs('admin.lembur*') ? 'active' : '' }}">
                        📄 Data Lembur
                    </a>
                </li>
            </ul>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </div>

        <div class="flex-grow-1 p-4 bg-light">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración') - Ikagai</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #1a202c;
            min-height: 100vh;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .header-title {
            font-size: 14px;
            color: #718096;
            font-weight: 400;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            padding: 10px 15px 10px 40px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            width: 250px;
            font-size: 14px;
            background: #f7fafc;
            transition: all 0.3s;
        }

        .header-search input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
        }

        .header-search svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            border-radius: 12px;
            background: #f7fafc;
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-info:hover {
            background: #edf2f7;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            font-size: 14px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
        }

        .user-email {
            font-size: 12px;
            color: #718096;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 70px;
            left: 20px;
            width: 260px;
            height: calc(100vh - 90px);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            padding: 20px 0;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 15px;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            border-radius: 12px;
        }

        .sidebar-menu a:hover {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            color: #667eea;
            transform: translateX(5px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .menu-icon {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            display: inline-block;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 300px;
            margin-top: 90px;
            margin-right: 20px;
            padding: 30px;
            min-height: calc(100vh - 110px);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Breadcrumb */
        .breadcrumb {
            margin-bottom: 20px;
            color: #718096;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #4a5568;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #e53e3e;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a202c;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #f0f0f0;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .card-header {
            border-bottom: 2px solid #f7fafc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 25px;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th:first-child {
            border-top-left-radius: 12px;
        }

        th:last-child {
            border-top-right-radius: 12px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #4a5568;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
        }

        .btn-secondary {
            background: #718096;
            color: white;
        }

        .btn-secondary:hover {
            background: #4a5568;
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #fc8181 0%, #f56565 100%);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 13px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            color: #2d3748;
            font-weight: 500;
            font-size: 14px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #e53e3e;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
            color: #22543d;
        }

        .badge-warning {
            background: linear-gradient(135deg, #feebc8 0%, #fbd38d 100%);
            color: #7c2d12;
        }

        .badge-danger {
            background: linear-gradient(135deg, #fed7d7 0%, #fc8181 100%);
            color: #742a2a;
        }

        .badge-info {
            background: linear-gradient(135deg, #bee3f8 0%, #90cdf4 100%);
            color: #2c5282;
        }

        /* Alerts */
        .alert {
            padding: 18px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: slideInDown 0.3s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert::before {
            font-size: 24px;
        }

        .alert-success {
            background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
            color: #22543d;
            border-left: 4px solid #38a169;
        }

        .alert-success::before {
            content: '✓';
        }

        .alert-error {
            background: linear-gradient(135deg, #fed7d7 0%, #fc8181 100%);
            color: #742a2a;
            border-left: 4px solid #e53e3e;
        }

        .alert-error::before {
            content: '⚠';
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 5px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            text-decoration: none;
            color: #4a5568;
        }

        .pagination a:hover {
            background-color: #f7fafc;
            color: #e53e3e;
        }

        .pagination .active {
            background-color: #e53e3e;
            color: white;
            border-color: #e53e3e;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="logo">
                <div class="logo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a3 3 0 0 0-3 3v4a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"></path>
                        <path d="M12 9a9 9 0 0 1 9 9v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a9 9 0 0 1 9-9z"></path>
                    </svg>
                </div>
                <div>
                    <div style="font-size: 20px; font-weight: 700;">IKAGAI</div>
                    <div class="header-title">Panel de Control</div>
                </div>
            </div>
        </div>
        <div class="header-right">
            <div class="header-search">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" placeholder="Buscar...">
            </div>
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-small" style="box-shadow: none;">Salir</button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </span>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <span class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </span>
                    Productos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                    </span>
                    Categorías
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </span>
                    Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </span>
                    Pedidos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <span class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </span>
                    Reseñas
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script>
        // Cerrar alertas automáticamente
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);

        // Confirmación para eliminar
        function confirmDelete(message = '¿Estás seguro de eliminar este elemento?') {
            return confirm(message);
        }

        // Toggle modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('active');
            }
        }

        // CSRF Token para peticiones AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    </script>

    @yield('scripts')
</body>
</html>


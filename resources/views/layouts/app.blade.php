<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'FixITCh') }}</title>

    <!-- Fuente moderna -->
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Archivos de Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /*  Estilo moderno */
        aside {
            background: linear-gradient(180deg, #1565c0, #1e88e5);
        }

        .submenu-collapse {
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        }

        .submenu-collapse.show {
            animation: fadeIn 0.4s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .submenu-link {
            transition: all 0.2s ease;
        }

        .submenu-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(4px);
        }
    </style>
</head>

<body class="bg-light">
    <div class="d-flex" style="min-height: 100vh;">

        <!-- 🌈 Sidebar -->
<aside class="text-white d-flex flex-column shadow-lg" style="width: 250px;">
    <!-- Logo / Nombre App -->
    <div class="p-4 border-bottom border-light">
        <a href="{{ route('dashboard') }}" class="text-white text-decoration-none fs-4 fw-bold">
            Fix<span class="text-warning">IT</span>Ch
        </a>
    </div>

    <!-- Menú -->
    <nav class="flex-grow-1 p-3">

        {{-- 🔹 MANTENIMIENTOS SOLICITADOS = solicitudes.nuevas --}}
        <a href="{{ route('solicitudes.nuevas') }}" 
           class="d-block py-2 px-3 rounded text-white text-decoration-none mb-1
                  {{ request()->routeIs('solicitudes.nuevas') ? 'bg-white text-primary fw-bold' : '' }}">
           📋 Mantenimientos solicitados
        </a>

        {{-- 🔹 Submenú: Lista de solicitudes (asignadas + baúl) --}}
        <div class="mb-1">
            <button class="btn btn-primary w-100 text-start d-flex align-items-center justify-content-between"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#submenuSolicitudes"
                    aria-expanded="{{ request()->routeIs('solicitudes.index') || request()->routeIs('solicitudes.baul') ? 'true' : 'false' }}"
                    aria-controls="submenuSolicitudes">
                📝 Lista de solicitudes
                <span class="small">
                    {{ request()->routeIs('solicitudes.index') || request()->routeIs('solicitudes.baul') ? '▲' : '▼' }}
                </span>
            </button>

            <div class="collapse submenu-collapse {{ request()->routeIs('solicitudes.index') || request()->routeIs('solicitudes.baul') ? 'show' : '' }}" id="submenuSolicitudes">
                <ul class="list-unstyled ps-3 mt-2">
                    <li>
                        <a href="{{ route('solicitudes.index') }}"
                           class="submenu-link d-block py-2 px-3 rounded text-white text-decoration-none mb-1
                                  {{ request()->routeIs('solicitudes.index') ? 'bg-white text-primary fw-bold' : '' }}">
                            🧾 Solicitudes de mantenimiento
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('solicitudes.baul') }}"
                           class="submenu-link d-block py-2 px-3 rounded text-white text-decoration-none mb-1
                                  {{ request()->routeIs('solicitudes.baul') ? 'bg-white text-primary fw-bold' : '' }}">
                            📦 Baúl de solicitudes pendientes
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- 🔹 Submenú: Órdenes de mantenimiento --}}
        <div class="mb-1">
            <button class="btn btn-primary w-100 text-start d-flex align-items-center justify-content-between"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#submenuOrdenes"
                    aria-expanded="{{ request()->routeIs('ordenes.*') ? 'true' : 'false' }}"
                    aria-controls="submenuOrdenes">
                🛠️ Órdenes de mantenimiento
                <span class="small">{{ request()->routeIs('ordenes.*') ? '▲' : '▼' }}</span>
            </button>

            <div class="collapse submenu-collapse {{ request()->routeIs('ordenes.*') ? 'show' : '' }}" id="submenuOrdenes">
                <ul class="list-unstyled ps-3 mt-2">
                    <li>
                        <a href="{{ route('ordenes.activas') }}"
                           class="submenu-link d-block py-2 px-3 rounded text-white text-decoration-none mb-1
                                  {{ request()->routeIs('ordenes.activas') ? 'bg-white text-primary fw-bold' : '' }}">
                            ⚙️ Órdenes activas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ordenes.terminadas') }}"
                           class="submenu-link d-block py-2 px-3 rounded text-white text-decoration-none mb-1
                                  {{ request()->routeIs('ordenes.terminadas') ? 'bg-white text-primary fw-bold' : '' }}">
                            ✅ Solicitudes terminadas
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Registrar usuario --}}
        <a href="{{ route('users.create') }}" 
           class="d-block py-2 px-3 rounded text-white text-decoration-none mb-1
                  {{ request()->routeIs('users.create') ? 'bg-white text-primary fw-bold' : '' }}">
           👤 Registrar usuario
        </a>

        {{-- Todos los usuarios --}}
        <a href="{{ route('users.index') }}" 
           class="d-block py-2 px-3 rounded text-white text-decoration-none mb-1
                  {{ request()->routeIs('users.index') ? 'bg-white text-primary fw-bold' : '' }}">
           👥 Todos los usuarios
        </a>

        {{-- Inventario --}}
        <a href="{{ route('inventario.index') }}" 
           class="d-block py-2 px-3 rounded text-white text-decoration-none mb-1
                  {{ request()->routeIs('inventario.index') ? 'bg-white text-primary fw-bold' : '' }}">
           🖥️ Inventario
        </a>

        <!-- Programa de Mantenimiento Preventivo -->
        <a href="{{ route('programa.index') }}" 
        class="d-block py-2 px-3 rounded text-white text-decoration-none mb-1 {{ request()->routeIs('programa.index') ? 'bg-white text-primary fw-bold' : '' }}">
        📆 Programa preventivo
        </a>

    </nav>

    <div class="p-3 border-top border-light text-center small">
        © {{ date('Y') }} ITCh
    </div>
</aside>


               <!-- 🖥️ Contenido principal -->
        <div class="flex-grow-1 d-flex flex-column">
            
            <!-- Topbar -->
            <header class="bg-white shadow-sm p-3 d-flex justify-content-between align-items-center">
                <h1 class="fs-5 fw-bold text-primary mb-0">Panel de Administración</h1>

                <div class="d-flex align-items-center gap-3">

                    {{-- 🔔 Notificaciones de solicitudes nuevas --}}
                    <a href="{{ route('solicitudes.nuevas') }}" class="btn btn-outline-primary position-relative">
                        🔔
                        @if(($newRequestsCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $newRequestsCount }}
                            </span>
                        @endif
                    </a>

                    {{-- 🚪 Cerrar sesión --}}
                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            🚪 Cerrar sesión
                        </button>
                    </form>
                </div>
            </header>

            <!-- Contenido dinámico -->
            <main class="flex-grow-1 p-4">
                @yield('content')
            </main>
        </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-refresh en vistas de solicitudes y órdenes -->
    <script>
        @if(request()->routeIs('solicitudes.*') || request()->routeIs('ordenes.*'))
            // Refrescar cada 30 segundos (30000 ms)
            setInterval(function () {
                window.location.reload();
            }, 30000);
        @endif
    </script>
</body>
</html>

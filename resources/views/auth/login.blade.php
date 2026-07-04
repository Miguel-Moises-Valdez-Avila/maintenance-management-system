<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FixITCh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100"
      style="background: linear-gradient(135deg, #1976D2, #f29509);">

    <div class="card shadow-lg border-0 rounded-4" style="max-width: 400px; width: 100%; backdrop-filter: blur(12px); background: rgba(255,255,255,0.9);">
        <div class="card-body p-5">

            <!-- Logo / Título -->
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary">
                    Fix<span class="text-warning">IT</span>Ch
                </h1>
                <p class="text-muted">Inicia sesión para continuar</p>
            </div>

            <!-- Formulario -->
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <!-- Correo -->
                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" name="email" id="email" required
                           class="form-control rounded-3" placeholder="ejemplo@correo.com">
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" required
                           class="form-control rounded-3" placeholder="********">
                </div>

                <!-- Botón -->
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold">
                    🚀 Ingresar
                </button>
            </form>

            <!-- Errores -->
            @if ($errors->any())
                <div class="alert alert-danger mt-3 rounded-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


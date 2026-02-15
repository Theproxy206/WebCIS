<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>WebCIS API</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #0f172a, #020617);
            color: #e5e7eb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 720px;
            padding: 3rem;
            background: rgba(15, 23, 42, 0.85);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        h1 {
            margin-top: 0;
            font-size: 2.2rem;
            color: #38bdf8;
        }

        p {
            line-height: 1.6;
            color: #cbd5f5;
        }

        .badge {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.4rem 0.8rem;
            background: #020617;
            border: 1px solid #334155;
            border-radius: 999px;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .links {
            margin-top: 2rem;
        }

        .links a {
            color: #38bdf8;
            text-decoration: none;
            margin-right: 1.5rem;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        footer {
            margin-top: 2.5rem;
            font-size: 0.8rem;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>WebCIS Backend API</h1>

    <p>
        Bienvenido a la API de <strong>WebCIS</strong>.
        Este servicio actúa como el núcleo de comunicación entre las aplicaciones cliente
        y los sistemas internos del CIS.
    </p>

    <p>
        Si estás viendo esta página desde un navegador, todo está funcionando correctamente.
        Las rutas principales están pensadas para consumo vía <code>JSON</code>.
    </p>

    <span class="badge">
            Laravel {{ app()->version() }} · {{ config('app.env') }}
        </span>

    <div class="links">
        <a href="/api">API</a>
        <a href="https://laravel.com/docs" target="_blank">Laravel Docs</a>
    </div>

    <footer>
        © {{ date('Y') }} WebCIS · Backend Service
    </footer>
</div>

</body>
</html>

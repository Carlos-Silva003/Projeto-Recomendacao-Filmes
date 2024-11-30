<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Livros')</title>
    <!-- Adicione seus estilos ou bibliotecas aqui -->
</head>
<body>
    <header>
        <h1>Lista de Livros</h1>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Lista de Livros</p>
    </footer>
</body>
</html>

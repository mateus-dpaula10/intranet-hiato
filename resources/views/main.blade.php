<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de cultura - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="{{ asset('img/logos/ico-colorido.png') }}">
</head>
<body id="dashboard">
    <div>
        <aside>
            <nav>
                <a href="{{ route('dashboard.index') }}">
                    <img src="{{ asset('img/logos/colorido.png') }}" alt="Logo Hiato">
                </a>
    
                <ul>
                    <li>
                        <a href="{{ route('dashboard.index') }}">Dashboard</a>
                    </li>
                    @if(!auth()->user()->role === 'collaborator')
                        <li>
                            <a href="{{ route('diagnostic.index') }}">Diagnóstico</a>
                        </li>
                    @endif
                    @if(auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('vacation.index') }}">Controle de férias</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('usuario.user') }}">Usuários</a>
                    </li>
                    <li>
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit">Sair</button>
                        </form>
                    </li>
                </ul>
            </nav>
            <button type="button" id="toggle_button">
                <i class="bi bi-list"></i>
            </button>
        </aside>
    
        <main>
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
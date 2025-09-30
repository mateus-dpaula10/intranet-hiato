@extends('main')

@section('title', 'Férias')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif
            
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>           
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>     
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="mb-0">Férias</h3> 
                    @if($user->role === 'admin')
                        <a href="{{ route('vacation.create') }}"><i class="bi bi-plus-square me-2"></i>Cadastrar período</a>
                    @endif
                </div>

                @if ($user->role === 'admin')
                    <form method="GET" action="{{ route('vacation.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Buscar colaborador..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </form>
                @endif
                <div class="table-responsive">
                    <table class="table table-success table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Colaborador</th>
                                <th>Períodos de férias</th>
                                <th>Dias</th>
                                <th>Status</th>
                                @if ($user->role === 'admin')
                                    <th>Ações</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach($periods as $period)
                                <tr>
                                    <td>{{ $period['user_name'] }}</td>
                                    <td>
                                        {{ $period['start_date']->format('d/m/Y') }}
                                        até
                                        {{ $period['end_date']->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $period['days'] }}</td>
                                    <td>
                                        @if($period['status'] === 'Em gozo')
                                            <span class="badge bg-success">{{ $period['status'] }}</span>
                                        @elseif($period['status'] === 'Programada')
                                            <span class="badge bg-primary">{{ $period['status'] }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $period['status'] }}</span>
                                        @endif
                                    </td>
                                    @if ($user->role === 'admin')
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a class="btn btn-warning btn-sm" href="{{ route('vacation.edit', $period['vacation_id']) }}">
                                                    <i class="bi bi-pencil-square me-2"></i>Editar
                                                </a>
                                                <form action="{{ route('vacation.period.destroy', [
                                                    'vacation' => $period['vacation_id'], 
                                                    'index' => $period['period_index']
                                                ]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash me-2"></i>Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
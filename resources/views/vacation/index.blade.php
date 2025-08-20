@extends('main')

@section('title', 'Controle de férias')

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

                @php
                    $user = auth()->user();
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="mb-0">Controle de férias</h3> 
                    @if($user->role === 'admin')
                        <a href="{{ route('vacation.create') }}"><i class="bi bi-plus-square me-2"></i>Cadastrar período</a>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-success table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Colaborador</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($user->role === 'admin')
                                @foreach($vacations as $vacation)
                                    <tr>
                                        <td>{{ $vacation->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($vacation->start_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($vacation->end_date)->format('d/m/Y') }}</td>
                                        <td class="d-flex gap-1">
                                            <a class="btn btn-warning btn-sm" href="{{ route('vacation.edit', $vacation) }}"><i class="bi bi-pencil-square me-2"></i>Editar</a>
                                            <form action="{{ route('vacation.destroy', $vacation) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash me-2"></i>Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
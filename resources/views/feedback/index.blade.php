@extends('main')

@section('title', 'Feedbacks')

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

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="mb-0">Feedbacks</h3> 
                    @if($authUser->role === 'admin')
                        <a href="{{ route('feedback.create') }}"><i class="bi bi-plus-square me-2"></i>Cadastrar feedback</a>
                    @endif
                </div>

                @if($authUser->role === 'admin')
                    <form method="GET" action="{{ route('feedback.index') }}" class="mb-3">
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
                                <th>Usuário</th>
                                <th>Data da realização</th>
                                <th>Tipo do feedback</th>
                                @if ($authUser->role === 'admin')
                                    <th>Ações</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($feedbacks as $feedback)
                                <tr>
                                    <td data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $feedback->id }}" style="cursor: pointer">{{ $feedback->user->name }}</td>
                                    @foreach ($feedback->completion_dates as $index => $date)
                                        @if ($loop->last)
                                            <td data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $feedback->id }}" style="cursor: pointer">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>                                            
                                        @endif
                                    @endforeach      
                                    @foreach ($feedback->types as $index => $type)
                                        @php
                                            $typeFeedback = match($type) {
                                                'ponctual'     => 'Pontual',
                                                'mounth_one'   => '1 mês',
                                                'mounth_three' => '3 meses',
                                                'mounth_six'   => '6 meses',
                                                'year_one'     => '1 ano',
                                                'yearly'       => 'Anual',
                                                'default'      => $type
                                            };
                                        @endphp
                                        @if ($loop->last)
                                            <td data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $feedback->id }}" style="cursor: pointer">{{ $typeFeedback }}</td>                                            
                                        @endif
                                    @endforeach      
                                    @if ($authUser->role === 'admin')
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a class="btn btn-warning btn-sm" href="{{ route('feedback.edit', $feedback) }}"><i class="bi bi-pencil-square me-2"></i>Editar</a>
                                                <form action="{{ route('feedback.destroy', $feedback) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash me-2"></i>Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>

                                <div class="modal fade" id="feedbackModal{{ $feedback->id }}" tabindex="-1" aria-labelledby="feedbackModalLabel{{ $feedback->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="feedbackModalLabel{{ $feedback->id }}">
                                                    Visualização do(s) feedback(s)
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                @foreach ($feedback->completion_dates as $index => $date)
                                                    <div class="mb-4 border-bottom pb-3">
                                                        <div class="form-group">
                                                            <label>Data de realização</label>
                                                            <input readonly class="form-control" value="{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}">
                                                        </div>
                                    
                                                        <div class="form-group mt-3">
                                                            <label>Descrição</label>
                                                            <textarea class="form-control" rows="5" readonly>{{ $feedback->descriptions[$index] ?? '---' }}</textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
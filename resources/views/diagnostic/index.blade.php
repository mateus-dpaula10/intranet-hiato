@extends('main')

@section('title', 'Diagnóstico')

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
                    <h3 class="mb-0">Diagnóstico</h3> 

                    @if ($authUser->role === 'admin')
                        <a href="{{ route('diagnostic.create') }}">
                            <i class="bi bi-eye me-2"></i>                        
                            Visualizar diagnóstico
                        </a>
                    @else
                        @if ($authUser->answers_sum_points > 0)
                            <span class="fw-bold">Você já respondeu esse diagnóstico.</span>
                        @else
                            <a href="{{ route('diagnostic.create') }}">
                                <i class="bi bi-plus-square me-2"></i>                        
                                Responder diagnóstico
                            </a>
                        @endif
                    @endif                    
                </div>                
                
                @if($authUser->role === 'admin')
                    <form method="GET" action="{{ route('diagnostic.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Buscar colaborador..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="bg-secondary text-white">Usuário</th>
                                    <th class="bg-secondary text-white">Pontuação total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $user)
                                    @php
                                        if ($user->answers_sum_points < 12) {
                                            $bg = '#f8d7da';
                                            $color = '#842029';
                                            $num = 'Baixo';
                                        } elseif ($user->answers_sum_points < 24) {
                                            $bg = '#fff3cd';
                                            $color = '#664d03';
                                            $num = 'Médio';
                                        } else {
                                            $bg = '#d1e7dd';
                                            $color = '#0f5132';
                                            $num = 'Alto';
                                        }
                                    @endphp

                                    <tr 
                                        style="cursor: pointer"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewModalQuestions"
                                        data-user-id="{{ $user->id }}"
                                    >
                                        <td style="background-color: {{ $bg }}; color: {{ $color }}">{{ $user->name }}</td>                                        
                                        <td style="background-color: {{ $bg }}; color: {{ $color }}">{{ $user->answers_sum_points }} - {{ $num }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Nenhum usuário respondeu ainda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    @if($authUser->answers_sum_points > 0)
                        <div class="alert alert-info">
                            Sua pontuação: <strong>{{ $authUser->answers_sum_points }}</strong>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModalQuestions" tabindex="-1" aria-labelledby="viewModalQuestionsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewModalQuestionsLabel">Visualização das respostas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalQuestionsContent">
                    Carregando...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('viewModalQuestions');

            modal.addEventListener('show.bs.modal', function (event) {
                const tr = event.relatedTarget;
                const userId = tr.getAttribute('data-user-id');
                const container = document.getElementById('modalQuestionsContent');

                container.innerHTML = 'Carregando...';

                const baseUrl = "{{ url('usuarios') }}";

                fetch(`${baseUrl}/${userId}/respostas`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            container.innerHTML = '';
                            data.forEach(diagnostic => {
                                let html = `<strong>${diagnostic.question}</strong><br>`;
                                diagnostic.options.forEach(option => {
                                    html += `
                                        <label style="display: block">
                                            <input type="radio" disabled
                                                ${option.selected ? 'checked' : ''}>
                                            ${option.text} <span class="text-muted">(${option.points})</span>
                                        </label>
                                    `;
                                });
                                container.innerHTML += `<div class="mb-3">${html}</div>`;
                            });
                        } else {
                            container.innerHTML = '<p>Nenhuma resposta encontrada.</p>';
                        }
                    })
                    .catch(() => {
                        container.innerHTML = '<p class="text-danger">Erro ao carregar respostas.</p>';
                    });                
            });
        });
    </script>
@endpush
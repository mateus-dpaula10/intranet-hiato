@extends('main')

@section('title', 'Criar feedback')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
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
                    <h3>Feedbacks</h3> 
                    <a href="{{ route('feedback.index') }}"><i class="bi bi-arrow-left-square me-2"></i>Voltar</a>
                </div>

                <form action="{{ route('feedback.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="user_id">Nome</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="feedback-fields">
                        <div class="feedback-entry border p-3 mt-3">
                            <div class="form-group">
                                <label class="form-label">Data da realização</label>
                                <input type="date" name="completion_date[]" class="form-control" required>
                            </div>

                            <div class="form-group mt-3">
                                <label class="form-label">Tipo</label>
                                <select name="type[]" class="form-control" required>
                                    <option value="">Selecione um tipo de feedback</option>
                                    <option value="mounth_one">1 mês</option>
                                    <option value="mounth_three">3 meses</option>
                                    <option value="mounth_six">6 meses</option>
                                    <option value="year_one">1 ano</option>
                                    <option value="yearly">Anual</option>
                                </select>
                            </div>
        
                            <div class="form-group mt-3">
                                <label class="form-label">Descrição</label>
                                <textarea name="description[]" class="form-control" rows="10" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-secondary" type="button" onclick="addFeedbackEntry()">Adicionar outro feedback</button>
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-primary" type="submit">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function addFeedbackEntry() {
            const container = document.getElementById('feedback-fields');
            const entry = container.querySelector('.feedback-entry');
            const clone = entry.cloneNode(true);

            container.appendChild(clone);
        }
    </script>
@endpush
@extends('main')

@section('title', 'Criar usuário')

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
                    <h3>Editar feedback de '{{ $feedback->user->name }}'</h3> 
                    <a href="{{ route('feedback.index') }}"><i class="bi bi-arrow-left-square me-2"></i>Voltar</a>
                </div>

                <form action="{{ route('feedback.update', $feedback) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="user_id">Nome</label>
                        <select id="user_id" class="form-select" disabled>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $feedback->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="user_id" value="{{ $feedback->user_id }}">
                    </div>

                    <div id="feedback-fields">
                        @foreach ($feedback->completion_dates as $index => $date)
                            <div class="feedback-entry border p-3 mt-3">
                                <div class="form-group">
                                    <label for="completion_date">Data de realização</label>
                                    <input type="date" name="completion_date[]" class="form-control" value="{{ $date }}" required>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="form-label">Tipo</label>
                                    <select name="type[]" class="form-control" required>
                                        <option value="ponctual" {{ ($feedback->types[$index] ?? '' ) === 'ponctual' ? 'selected' : '' }}>Pontual</option>
                                        <option value="mounth_one" {{ ($feedback->types[$index] ?? '' ) === 'mounth_one' ? 'selected' : '' }}>1 mês</option>
                                        <option value="mounth_three" {{ ($feedback->types[$index] ?? '' ) === 'mounth_three' ? 'selected' : '' }}>3 meses</option>
                                        <option value="mounth_six" {{ ($feedback->types[$index] ?? '' ) === 'mounth_six' ? 'selected' : '' }}>6 meses</option>
                                        <option value="year_one" {{ ($feedback->types[$index] ?? '' ) === 'year_one' ? 'selected' : '' }}>1 ano</option>
                                        <option value="yearly" {{ ($feedback->types[$index] ?? '' ) === 'yearly' ? 'selected' : '' }}>Anual</option>
                                    </select>
                                </div>

                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="visible[{{ $index }}]" value="1" {{ ($feedback->visibles[$index] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        Colaborador pode visualizar?
                                    </label>
                                </div>
            
                                <div class="form-group mt-3">
                                    <label for="description">Descrição</label>
                                    <textarea name="description[]" class="form-control" rows="10" required>{{ $feedback->descriptions[$index] ?? '' }}</textarea>
                                </div>
                            </div>                            
                        @endforeach
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
        }[]
    </script>
@endpush
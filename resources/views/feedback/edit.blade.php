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
                                    <label for="description">Descrição</label>
                                    <textarea name="description[]" class="form-control" rows="10" required>{{ $feedback->descriptions[$index] ?? '' }}</textarea>
                                </div>
                            </div>                            
                        @endforeach
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
    
@endpush
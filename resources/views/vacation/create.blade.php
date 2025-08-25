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
                    <h3>Controle de férias</h3> 
                    <a href="{{ route('vacation.index') }}"><i class="bi bi-arrow-left-square me-2"></i>Voltar</a>
                </div>

                <form action="{{ route('vacation.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="user_id">Nome</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <label for="start_date">Data de início</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="end_date">Data de término</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-primary" type="submit">Salvar</button>
                    </div>
                </form>

                @if(session('warning'))
                    <div class="alert alert-warning mt-3">
                        {{ session('warning') }}
                        <form method="POST" action="{{ route('vacation.store') }}" class="mt-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ old('user_id') }}">
                            <input type="hidden" name="start_date" value="{{ old('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ old('end_date') }}">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-danger">Confirmar mesmo assim</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

    </script>
@endpush
@extends('main')

@section('title', 'Perfil de Cultura')

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

                <h3 class="mb-5">Diagnóstico de perfil de cultura para '{{ $user->name }}'</h3>    

                @if ($user->role !== 'admin')
                    <form action="{{ route('diagnostic.store') }}" method="POST">
                        @csrf

                        @foreach($diagnostics as $index => $diagnostic )
                            <div class="mb-3">
                                <strong>{{ $index + 1 }} - {{ $diagnostic->question }}</strong> <br>

                                @php
                                    $options = $diagnostic->options->shuffle(); 
                                @endphp

                                @foreach($options as $option)
                                    <label style="display: block">
                                        <input type="radio" name="answers[{{ $diagnostic->id }}]" value="{{ $option->id }}" required>
                                        {{ preg_replace('/\s*\(.*\)$/', '', $option->text) }}
                                    </label>
                                @endforeach
                            </div>
                        @endforeach

                        <button class="btn btn-primary">Enviar respostas</button>
                    </form>
                @else
                    @foreach($diagnostics as $index => $diagnostic )
                        <div class="mb-3">
                            <strong>{{ $index + 1 }} - {{ $diagnostic->question }}</strong> <br>

                            @php
                                $options = $diagnostic->options->shuffle(); 
                            @endphp

                            @foreach($options as $option)
                                <label style="display: block">
                                    <input type="radio" name="answers[{{ $diagnostic->id }}]" value="{{ $option->id }}" disabled required>
                                    {{ $option->text }} <span class="text-muted">({{ $option->points }})</span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                @endif                
            </div>
        </div>
    </div>
@endsection
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
                    <!-- @if($user->role === 'admin')
                        <a href="{{ route('usuario.create') }}"><i class="bi bi-plus-square me-2"></i>Cadastrar usuário</a>
                    @endif -->
                </div>
            </div>
        </div>
    </div>
@endsection
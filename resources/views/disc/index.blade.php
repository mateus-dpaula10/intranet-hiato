@extends('main')

@section('title', 'DISC')

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
                    <h3 class="mb-0">Avaliação Comportamental - DISC</h3> 

                    @if (!$hashAnswered)
                        <a href="{{ route('disc.create') }}">
                            <i class="bi bi-plus-square me-2"></i>                        
                            Responder diagnóstico
                        </a>
                    @endif
                </div>       
                
                <div class="alert alert-info mt-5">
                    <h5>Perfis do DISC</h5>
                    <ul class="mb-0">
                        @foreach($discProfiles as $key => $disc)
                            <li>
                                <strong>{{ $disc['name'] }} → {{ $key }}:</strong> {{ $disc['description'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-3">
                    <ul>
                        <li>D (Dominância): {{ $totals['D'] }}</li>
                        <li>I (Influência): {{ $totals['I'] }}</li>
                        <li>S (Estabilidade): {{ $totals['S'] }}</li>
                        <li>C (Conformidade): {{ $totals['C'] }}</li>
                    </ul>

                    @if($profile)
                        <p><strong>Perfil predominante:</strong> {{ $profile }}</p>
                    @else
                        <p>Você ainda não respondeu o diagnóstico.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
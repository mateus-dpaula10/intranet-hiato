@extends('main')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid" id="index_dashboard">
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

                <div class="row justify-content-between pt-2">
                    <div class="col-lg-6">
                        <div id="block_birthday">
                            <h4>Aniversariantes do mês</h4>

                            @if ($birthdays->isNotEmpty())
                                <ul>
                                    @foreach ($birthdays as $birthday)
                                        <li>{{ $birthday['user']->name }} ({{ $birthday['user']->position }}) - ({{ $birthday['date']->format('d/m/Y') }})</li>                                            
                                    @endforeach
                                </ul>
                            @else
                                <p class="mb-0">Não há aniversariantes neste mês</p>   
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-5 mt-5 mt-lg-0">
                        <h4>Notificações</h4>
                        
                        @if ($vacations->isNotEmpty())
                            <div class="alert alert-warning mt-4">
                                <h5 class="mb-4">Aviso de férias</h5>
                                <ul class="p-0 m-0">
                                    @foreach ($vacations as $vacation)
                                        <li class="d-flex align-items-center justify-content-between gap-1">
                                            <strong>
                                                {{ $vacation['user']->name }} - 
                                                {{ $vacation['start_date']->format('d/m/Y') }} até
                                                {{ $vacation['end_date']->format('d/m/Y') }}
                                            </strong>

                                            @if ($authUser->role === 'admin')
                                                <form action="{{ route('vacations.markAsRead', ['vacation' => $vacation['vacation_id'], 'periodIndex' => $vacation['period_index']]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-warning">Marcar como lido</button>
                                                </form>                                                
                                            @endif
                                        </li>  
                                        @if (!$loop->last)
                                            <hr style="margin: .5rem 0">                                             
                                        @endif
                                    @endforeach
                                </ul>
                            </div>     
                        @endif
                        
                        @if ($feedbacks->isNotEmpty())
                            <div class="alert alert-warning mt-4">
                                <h5 class="mb-4">Aviso de feedback</h5>
                                <ul class="p-0 m-0">
                                    @foreach ($feedbacks as $feedback)
                                        <li class="d-flex align-items-center justify-content-between gap-1">
                                            <strong>{{ $feedback['user']->name }} - {{ $feedback['rule'] }}</strong>
                                            <span>{{ $feedback['days_left'] }} dias restantes ({{ \Carbon\Carbon::parse($feedback['date'])->format('d/m/Y') }})</span>
                                        </li>  
                                        @if (!$loop->last)
                                            <hr style="margin: .5rem 0">                                             
                                        @endif
                                    @endforeach
                                </ul>
                            </div>                            
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
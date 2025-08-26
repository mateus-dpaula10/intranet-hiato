@extends('main')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="mb-0">Dashboard</h3>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        
                    </div>

                    <div class="col-lg-4">
                        <h4>Notificações</h4>
                        
                        @if ($vacations->isNotEmpty())
                            <div class="alert alert-warning mt-4">
                                <h5 class="mb-4">Aviso de férias dos próximos dias até @php $now = now()->addDays(30) @endphp {{ Carbon\Carbon::parse($now)->format('d/m/Y') }}</h5>
                                <ul class="p-0 m-0">
                                    @foreach ($vacations as $vacation)
                                        <li class="d-flex align-items-center justify-content-between">
                                            <strong>{{ $vacation->user->name }} - {{ \Carbon\Carbon::parse($vacation->start_date)->format('d/m/Y') }}</strong>

                                            @if ($authUser->role === 'admin')
                                                <form action="{{ route('vacations.markAsRead', $vacation) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-warning">Marcar como lido</button>
                                                </form>                                                
                                            @endif
                                        </li>  
                                        @if ($vacations->count() > 1)
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
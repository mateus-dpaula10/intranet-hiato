@extends('main')

@section('title', 'Editar férias')

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
                    <h3>Editar período de férias de '{{ $vacation->user->name }}'</h3> 
                    <a href="{{ route('vacation.index') }}"><i class="bi bi-arrow-left-square me-2"></i>Voltar</a>
                </div>

                <form action="{{ route('vacation.update', $vacation) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="user_id">Nome</label>
                        <select id="user_id" class="form-select" disabled>
                            @foreach ($users as $user)
                                <option value="{{ $vacation->user->id }}" selected>{{ $vacation->user->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="user_id" value="{{ $vacation->user->id }}">
                    </div>

                    <div id="periods-container">
                        @foreach ($vacation->periods ?? [] as $i => $period)
                            <div class="period-entry border p-3 mt-3">
                                <div class="form-group">
                                    <label for="start_date">Data de início</label>
                                    <input type="date" name="start_date[]" class="form-control" value="{{ old("start_date.$i", $period['start_date']) }}" required>
                                </div>
            
                                <div class="form-group mt-3">
                                    <label for="end_date">Data de término</label>
                                    <input type="date" name="end_date[]" class="form-control" value="{{ old("end_date.$i", $period['end_date']) }}" required>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm mt-2 remove-period">Remover</button>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-secondary" type="button" id="add-period">Adicionar período</button>
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-primary" type="submit">Salvar</button>
                    </div>
                </form>

                @if(session('warning'))
                    <div class="alert alert-warning mt-3">
                        {{ session('warning') }}
                        <form method="POST" action="{{ route('vacation.update', $vacation) }}" class="mt-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="user_id" value="{{ $vacation->user->id }}">
                            @foreach (old('start_date', array_column($vacation->periods ?? [], 'start_date')) as $i => $start)
                                <input type="hidden" name="start_date[]" value="{{ $start }}">                                
                            @endforeach
                            @foreach (old('end_date', array_column($vacation->periods ?? [], 'end_date')) as $i => $end)
                                <input type="hidden" name="end_date[]" value="{{ $end }}">                                
                            @endforeach
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
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('periods-container');

            document.getElementById('add-period').addEventListener('click', () => {
                const template = document.createElement('div');
                template.classList.add('period-entry', 'border', 'p-3', 'mt-3');
                template.innerHTML = `
                    <div class="form-group">
                        <label>Data de início</label>
                        <input type="date" name="start_date[]" class="form-control" required>
                    </div>

                    <div class="form-group mt-3">
                        <label>Data de término</label>
                        <input type="date" name="end_date[]" class="form-control" required>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-period">Remover</button>
                `;
                container.appendChild(template);
                updateRemoveButtons();
            });

            container.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-period')) {
                    e.target.closest('.period-entry').remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const periods = container.querySelectorAll('.period-entry');
                periods.forEach((period, index) => {
                    const btn = period.querySelector('.remove-period');
                    if (periods.length === 1) {
                        if (btn) btn.style.display = 'none';
                    } else {
                        if (btn) btn.style.display = 'inline-block';
                    }
                });
            }

            updateRemoveButtons();
        });
    </script>
@endpush
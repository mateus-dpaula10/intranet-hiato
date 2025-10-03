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

                <h3>Avaliação Comportamental - DISC para '{{ $authUser->name }}'</h3>   
                <small>Escolha por linha de 1 - mais combina a 4 - menos combina</small> 

                <form action="{{ route('disc.store') }}" method="POST" class="mt-5">
                    @csrf

                    @foreach ($discs as $rowIndex => $row)
                        <div class="mb-4 border p-3 rounded" data-row={{ $rowIndex }}>
                            <strong>Bloco {{ $rowIndex + 1 }}</strong>

                            <div class="row mt-2">
                                @foreach ($row->blocks as $dimension => $options)
                                    <div class="col-md-3">
                                        {{-- <strong>
                                            @if($dimension === 'D') Dominância (D) 
                                            @elseif($dimension === 'I') Influência (I) 
                                            @elseif($dimension === 'S') Estabilidade (S) 
                                            @elseif($dimension === 'C') Conformidade (C) 
                                            @endif
                                        </strong> --}}
                                        <ul>
                                            @foreach ($options as $option)
                                                <li>{{ $option }}</li>
                                            @endforeach
                                        </ul>

                                        <select name="scores[{{ $rowIndex }}][{{ $dimension }}]" class="form-select score-select" required>
                                            <option value="">Escolha uma opção</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <button class="btn btn-primary">Enviar respostas</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-row]').forEach(function(row) {
                const selects = row.querySelectorAll('.score-select');

                selects.forEach(select => {
                    select.addEventListener('change', function() {
                        const selectedValues = Array.from(selects)
                            .map(s => s.value)
                            .filter(v => v !== '');

                        selects.forEach(s => {
                            Array.from(s.options).forEach(opt => {
                                if (opt.value !== "" && opt.value !== s.value && selectedValues.includes(opt.value)) {
                                    opt.disabled = true;
                                } else {
                                    opt.disabled = false;
                                }
                            });
                        });
                    });
                });
            });
        });
    </script>
@endpush
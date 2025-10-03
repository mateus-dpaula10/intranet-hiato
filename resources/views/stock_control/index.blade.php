@extends('main')

@section('title', 'Controle de Estoque')

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
                    <h3 class="mb-0">Controle de Estoque</h3> 
                </div>  
                
                <form action="{{ route('estoque.updateAll') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <table class="table table-bordered align-middle text-center" id="stockTable">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $stock)
                                <tr>
                                    <td>
                                        <input type="text" name="stocks[{{ $stock->id }}][product_name]" class="form-control" value="{{ $stock->product_name }}">
                                    </td>
                                    <td>
                                        <select name="stocks[{{ $stock->id }}][type]" class="form-select">
                                            <option value="units" {{ $stock->type === 'units' ? 'selected' : '' }}>Unidades</option>
                                            <option value="packages" {{ $stock->type === 'packages' ? 'selected' : '' }}>Pacotes</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="stocks[{{ $stock->id }}][quantidade]" class="form-control" step="0.1" value="{{ $stock->quantidade }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row">Excluir</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" id="addRow" class="btn btn-secondary">Adicionar outro item</button>
                    <button type="submit" class="btn btn-primary ms-auto">Salvar</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let tableBody = document.querySelector('#stockTable tbody');
            let addRowBtn = document.getElementById('addRow');

            let newRowId = 0;

            addRowBtn.addEventListener('click', function () {
                newRowId++;

                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <input type="text" name="new[${newRowId}][product_name]" class="form-control">
                    </td>
                    <td>
                        <select name="new[${newRowId}][type]" class="form-select">
                            <option value="units">Unidades</option>
                            <option value="packages">Pacotes</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="new[${newRowId}][quantidade]" class="form-control" step="0.1">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Excluir</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            tableBody.addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-row")) {
                    e.target.closest("tr").remove();
                }
            });
        });
    </script>
@endpush
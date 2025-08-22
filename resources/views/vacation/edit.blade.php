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
                                <option value="{{ $user->id }}" {{ old('user_id', $vacation->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="user_id" value="{{ $vacation->user_id }}">
                    </div>

                    <div class="form-group mt-3">
                        <label for="start_date">Data de início</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $vacation->start_date) }}">
                    </div>

                    <div class="form-group mt-3">
                        <label for="end_date">Data de término</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $vacation->end_date) }}">
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
                            <input type="hidden" name="user_id" value="{{ old('user_id', $vacation->user_id) }}">
                            <input type="hidden" name="start_date" value="{{ old('start_date', $vacation->start_date) }}">
                            <input type="hidden" name="end_date" value="{{ old('end_date', $vacation->end_date) }}">
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
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('strength-bar');
            const generateBtn = document.getElementById('generate-password');
            const generatedText = document.getElementById('generated-password');
            const copyBtn = document.getElementById('copy-password');

            const calculateStrength = (password) => {
                let score = 0;

                if (password.length >= 8) score += 20;

                if (/[A-Z]/.test(password)) score += 20;

                if (/[a-z]/.test(password)) score += 20;
                
                if (/[0-9]/.test(password)) score += 20;
                
                if (/[@$!%*?&]/.test(password)) score += 20;

                return score;
            }

            const updateStrengthBar = (password) => {
                const strength = calculateStrength(password);
                strengthBar.style.width = `${strength}%`;

                strengthBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');

                if (strength < 100) {
                    if (strength < 40) {
                        strengthBar.classList.add('bg-danger');
                    } else {
                        strengthBar.classList.add('bg-warning');
                    }
                } else {
                    strengthBar.classList.add('bg-success');
                }
            };

            passwordInput.addEventListener('input', function() {
                updateStrengthBar(passwordInput.value);
            });

            const gerarSenha = (tamanho = 12) => {
                const minusculas = 'abcdefghijklmnopqrstuvwxyz';
                const maiusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                const numeros = '0123456789';
                const especiais = '@$!%*?&';
                
                let senha = minusculas[Math.floor(Math.random() * minusculas.length)]
                        + maiusculas[Math.floor(Math.random() * maiusculas.length)]
                        + numeros[Math.floor(Math.random() * numeros.length)]
                        + especiais[Math.floor(Math.random() * especiais.length)];

                const todos = minusculas + maiusculas + numeros + especiais;
                for (let i = senha.length; i < tamanho; i++) {
                    senha += todos[Math.floor(Math.random() * todos.length)];
                }

                return senha.split('').sort(() => 0.5 - Math.random()).join('');
            }

            generateBtn.addEventListener('click', () => {
                const novaSenha = gerarSenha();
                generatedText.value = novaSenha;
            });

            copyBtn.addEventListener('click', () => {
                generatedText.select();
                generatedText.setSelectionRange(0, 99999); 
                document.execCommand('copy');
                alert('Senha copiada para a área de transferência!');
            });
        })        
    </script>
@endpush
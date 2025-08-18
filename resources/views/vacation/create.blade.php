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
                    <h3>Usuários</h3> 
                    <a href="{{ route('usuario.user') }}"><i class="bi bi-arrow-left-square me-2"></i>Voltar</a>
                </div>

                <form action="{{ route('usuario.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="role" class="form-label">Função</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">Selecione uma função</option>
                            <option value="user">Usuário</option>
                            <option value="admin">Administrador</option>
                            <option value="collaborator">Colaborador</option>
                        </select>
                    </div>

                    <div class="form-group mt-3">
                        <button type="button" id="generate-password" class="btn btn-secondary">Gerar senha forte</button>
                    </div>

                    <div class="form-group mt-3">
                        <label for="generated-password" class="form-label">Senha gerada (copiar):</label>
                        <div class="input-group">
                            <input type="text" id="generated-password" class="form-control" readonly>
                            <button type="button" id="copy-password" class="btn btn-primary">Copiar</button>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="password_confirmation" class="form-label">Confirmação da senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="password-strength">Força da senha</label>
                        <div id="password-strength" class="progress">
                            <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-primary" type="submit">Salvar</button>
                    </div>
                </form>
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
@extends('main')

@section('title', 'Editar usuário')

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
                    <h3>Editar usuário '{{ $user->name }}'</h3> 
                    <a href="{{ route('usuario.user') }}"><i class="bi bi-arrow-left-square me-2"></i>Voltar</a>
                </div>

                <form action="{{ route('usuario.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="name" class="form-label">Nome*</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="email" class="form-label">E-mail*</label>
                        @if(auth()->user()->role === 'admin')
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @else
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" readonly required>
                        @endif
                    </div>

                    <div class="form-group mt-3">
                        <label for="role" class="form-label">Função*</label>
                        @if(auth()->user()->role === 'admin')
                            <select name="role" id="role" class="form-select" required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuário</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="collaborator" {{ old('role', $user->role) === 'collaborator' ? 'selected' : '' }}>Colaborador</option>
                            </select>
                        @else
                            <select name="role" id="role" class="form-select" disabled required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuário</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="collaborator" {{ old('role', $user->role) === 'collaborator' ? 'selected' : '' }}>Colaborador</option>
                            </select>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @endif
                    </div>

                    <div class="mt-3 d-none" id="div_admission_date">
                        <div class="form-group">
                            <label for="birth_date" class="form-label">Data de nascimento*</label>
                            <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date) }}">
                        </div>

                        <div class="form-group mt-3">
                            <label for="admission_date" class="form-label">Data de admissão*</label>                            
                            <input type="date" name="admission_date" id="admission_date" class="form-control" value="{{ old('admission_date', $user->admission_date) }}" {{ $authUser->role === 'admin' ? '' : 'readonly'  }}>
                        </div>

                        <div class="form-group mt-3">
                            <label for="position" class="form-label">Cargo*</label>
                            <input type="text" name="position" id="position" class="form-control" value="{{ old('position', $user->position) }}" {{ $authUser->role === 'admin' ? '' : 'readonly'  }}>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="is_management" value="0">
                            <input class="form-check-input" type="checkbox" name="is_management" value="1" id="is_management" {{ old('is_management', $user->is_management) ? 'checked' : '' }} {{ $authUser->role === 'admin' ? '' : 'disabled' }}>
                            <label class="form-check-label" for="is_management">Gestão?</label>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="cep" class="form-label">CEP*</label>
                        <input type="text" name="cep" id="cep" class="form-control" value="{{ old('cep', $user->cep) }}" required>
                    </div>
                    <small>Preencha o CEP que automaticamente o endereço será preenchido</small>

                    <div class="form-group mt-3">
                        <label for="address" class="form-label">Endereço*</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address) }}" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="number" class="form-label">Número*</label>
                        <input type="number" name="number" id="number" class="form-control" value="{{ old('number', $user->number) }}" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="complement" class="form-label">Complemento</label>
                        <input type="text" name="complement" id="complement" class="form-control" value="{{ old('complement', $user->complement) }}">
                    </div>

                    <div class="form-group mt-3">
                        <label for="phone" class="form-label">Telefone*</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="emergency_contact" class="form-label">Nome do contato de emergência*</label>
                        <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" value="{{ old('emergency_contact', $user->emergency_contact) }}" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="emergency_phone" class="form-label">Telefone do contato de emergência*</label>
                        <input type="text" name="emergency_phone" id="emergency_phone" class="form-control" value="{{ old('emergency_phone', $user->emergency_phone) }}" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="convenio" class="form-label">Convênio*</label>
                        <select name="convenio" id="convenio" class="form-select" required>
                            <option value="sim" {{ old('convenio', $user->convenio ? 'sim' : 'nao') === 'sim' ? 'selected' : '' }}>Sim</option>
                            <option value="nao" {{ old('convenio', $user->convenio ? 'sim' : 'nao') === 'nao' ? 'selected' : '' }}>Não</option>
                        </select>
                    </div>

                    <div class="form-group mt-3" id="convenio_qual_container" style="{{ old('convenio', $user->convenio ? 'sim' : 'nao') === 'sim' ? '' : 'display: none' }}">
                        <label for="convenio_qual" class="form-label">Qual?*</label>
                        <input type="text" name="convenio_qual" id="convenio_qual" class="form-control" value="{{ old('convenio_qual', $user->convenio_qual) }}" {{ old('convenio', $user->convenio ? 'sim' : 'nao') === 'sim' ? 'required' : '' }}>
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
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="form-group mt-3">
                        <label for="password_confirmation" class="form-label">Confirmação da senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
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
            const selectRole = document.getElementById('role');
            const divAdmissionDate = document.getElementById('div_admission_date');

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

            selectRole.addEventListener('change', function() {
                if (selectRole.value === 'collaborator' || selectRole.value === 'admin') {
                    divAdmissionDate.classList.remove('d-none');  

                    ['birth_date', 'admission_date', 'position'].forEach(id => {
                        const field = document.getElementById(id);
                        if (field) {
                            field.setAttribute('required', 'required');
                        }
                    });
                } else {
                    divAdmissionDate.classList.add('d-none');

                    ['birth_date', 'admission_date', 'position'].forEach(id => {
                        const field = document.getElementById(id);
                        if (field) {
                            field.removeAttribute('required');
                            field.value = '';
                        }
                    });

                    const isManagement = document.getElementById('is_management');
                    if (isManagement) {
                        isManagement.checked = false;
                    }
                }
            });

            selectRole.dispatchEvent(new Event('change'));

            const inputCep = document.getElementById('cep');
            const inputAddress = document.getElementById('address');

            inputCep.addEventListener('input', function () {
                let value = inputCep.value.replace(/\D/g, '');
                if (value.length > 8) value = value.substring(0, 8);
                if (value.length > 5) value = value.replace(/(\d{5})(\d)/, "$1-$2");
                inputCep.value = value;
            });

            inputCep.addEventListener('blur', function () {
                const cep = inputCep.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                const endereco = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                                inputAddress.value = endereco;
                            } else {
                                alert('CEP não encontrado!');
                                inputAddress.value = '';
                            }
                        })
                        .catch(() => {
                            alert('Erro ao buscar o CEP!');
                        });
                }
            });

            const convenioSelect = document.getElementById('convenio');
            const convenioQualContainer = document.getElementById('convenio_qual_container');

            convenioSelect.addEventListener('change', function () {
                if (convenioSelect.value === 'sim') {
                    convenioQualContainer.style.display = 'block';
                    document.getElementById('convenio_qual').setAttribute('required', 'required');
                } else {
                    convenioQualContainer.style.display = 'none';
                    document.getElementById('convenio_qual').value = '';
                    document.getElementById('convenio_qual').removeAttribute('required');
                }
            });

            function maskPhone(event) {
                let input = event.target;
                let value = input.value.replace(/\D/g, '');

                if (!value) {
                    input.value = '';
                    return;
                }

                if (value.length > 11) value = value.substring(0, 11);

                if (value.length > 10) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
                } else if (value.length > 5) {
                    value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{0,5})/, "($1) $2");
                } else {
                    value = value.replace(/^(\d*)/, "($1");
                }
                input.value = value;
            }

            document.getElementById('phone').addEventListener('input', maskPhone);
            document.getElementById('emergency_phone').addEventListener('input', maskPhone);
        })        
    </script>
@endpush
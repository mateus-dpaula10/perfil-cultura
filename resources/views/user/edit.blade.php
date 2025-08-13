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
                    <h3>Editar usuário '{{ $user->name }}'</h3> 
                    <a href="{{ route('usuario.user') }}"><i class="bi bi-arrow-left-square me-2"></i>Voltar</a>
                </div>

                <form action="{{ route('usuario.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="email" class="form-label">E-mail</label>
                        @if(auth()->user()->role === 'admin')
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @else
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" readonly required>
                        @endif
                    </div>

                    <div class="form-group mt-3">
                        <label for="role" class="form-label">Função</label>
                        @if(auth()->user()->role === 'admin')
                            <select name="role" id="role" class="form-select">
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuário</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        @else
                            <select name="role" id="role" class="form-select" disabled>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuário</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @endif
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
        })           
    </script>
@endpush
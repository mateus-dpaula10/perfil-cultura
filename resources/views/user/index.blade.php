@extends('main')

@section('title', 'Usuários')

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

                @php
                    $user = auth()->user();
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="mb-0">Usuários</h3> 
                    @if($user->role === 'admin')
                        <a href="{{ route('usuario.create') }}"><i class="bi bi-plus-square me-2"></i>Cadastrar usuário</a>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-success table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>E-mail</th>
                                <th>Função</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($user->role === 'admin')
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        @php
                                            $role = match($user->role) {
                                                'admin' => 'Administrador',
                                                'user'  => 'Usuário',
                                                default => ''
                                            }
                                        @endphp
                                        <td>{{ $role }}</td>
                                        <td class="d-flex gap-1">
                                            <a class="btn btn-warning btn-sm" href="{{ route('usuario.edit', $user) }}"><i class="bi bi-pencil-square me-2"></i>Editar</a>
                                            <form action="{{ route('usuario.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash me-2"></i>Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    @php
                                        $role = match($user->role) {
                                            'admin' => 'Administrador',
                                            'user'  => 'Usuário',
                                            default => ''
                                        }
                                    @endphp
                                    <td>{{ $role }}</td>
                                    <td>
                                        <a class="btn btn-warning" href="{{ route('usuario.edit', $user) }}"><i class="bi bi-pencil-square me-2"></i>Editar</a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
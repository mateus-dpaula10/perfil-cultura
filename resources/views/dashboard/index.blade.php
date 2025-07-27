@extends('main')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
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

                <h3 class="mb-5">Dashboard</h3>  
                
                @php
                    $user = auth()->user();
                @endphp
                
                @if($user->role === 'admin')
                    <div class="table-responsive">
                        <table class="table table-success table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Pontuação total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->answers_sum_points ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">Nenhum usuário respondeu ainda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    @php
                        $userAnswer = $results->where('id', $user->id)->first();
                    @endphp
                    
                    @if($userAnswer)
                        <p>Olá, {{ $user->name }}!</p>
                        <p>Sua pontuação total é: <strong>{{ $userAnswer->answers_sum_points ?? 0 }}</strong></p>
                    @else
                        <p>Você ainda não respondeu ao diagnóstico.</p>
                        <a href="{{ route('diagnostic.index') }}">Responder agora</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
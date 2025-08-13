@extends('main')

@section('title', 'Dashboard')

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

                <h3 class="mb-5">Dashboard</h3>  
                
                @php
                    $user = auth()->user();
                @endphp
                
                @if($user->role === 'admin')
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="bg-secondary text-white">Usuário</th>
                                    <th class="bg-secondary text-white">Pontuação total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $user)
                                    @php
                                        if ($user->answers_sum_points < 12) {
                                            $bg = '#f8d7da';
                                            $color = '#842029';
                                            $num = 'Fora';
                                        } elseif ($user->answers_sum_points < 24) {
                                            $bg = '#fff3cd';
                                            $color = '#664d03';
                                            $num = 'Tende a melhorar';
                                        } else {
                                            $bg = '#d1e7dd';
                                            $color = '#0f5132';
                                            $num = 'Dentro';
                                        }
                                    @endphp

                                    <tr>
                                        <td style="background-color: {{ $bg }}; color: {{ $color }}">{{ $user->name }}</td>                                        
                                        <td style="background-color: {{ $bg }}; color: {{ $color }}">{{ $user->answers_sum_points }} - {{ $num }}</td>
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
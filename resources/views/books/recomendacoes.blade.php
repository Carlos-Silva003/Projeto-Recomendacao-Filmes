@extends('adminlte::page')
@section('title', 'Recomendações de Filmes')
@section('content')
<div class="container">
    <h1>Recomendações de Filmes para "{{ $book->title }}"</h1>
    <p><strong>Autor:</strong> {{ $book->author }}</p>

    <h3>Filmes Recomendados:</h3>
    <div class="row">
        @foreach ($movieDetails as $movie)
        <div class="col-md-4">
            <div class="card">
                <img src="{{ $movie['poster'] }}" class="card-img-top" alt="{{ $movie['title'] }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $movie['title'] }} ({{ $movie['year'] }})</h5>
                    <p class="card-text">{{ $movie['plot'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <a href="{{ route('books.index') }}" class="btn btn-primary">Voltar</a>
</div>
@endsection

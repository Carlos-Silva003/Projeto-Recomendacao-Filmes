@extends('adminlte::page')

@section('content')
<div class="container">
    <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">Adicionar Livro</a>
    
    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Sinopse</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->synopsis ?? 'Sinopse não gerada' }}</td>
                    <td>
                        <a href="{{ route('books.recommend-movies', $book) }}" class="btn btn-info">Recomendar Filmes</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

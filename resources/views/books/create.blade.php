@extends('adminlte::page')

@section('content')
    <h1>Adicionar Livro</h1>
    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        <label for="title">Título:</label>
        <input type="text" name="title" id="title" required>
        <br>
        <label for="author">Autor:</label>
        <input type="text" name="author" id="author" required>
        <br>
        <button type="submit">Salvar</button>
    </form>
@endsection

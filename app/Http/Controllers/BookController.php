<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
        ]);

        // Gerar a sinopse automaticamente
        $synopsis = $this->openAIService->generateSynopsis($book->title, $book->author);
        $book->synopsis = $synopsis;
        $book->save();

        return redirect()->route('books.index')->with('message', 'Livro adicionado e sinopse gerada automaticamente.');
    }

    public function generateSynopsis(Book $book)
    {
        if ($book->synopsis) {
            return redirect()->route('books.index')->with('message', 'A sinopse já foi gerada!');
        }

        $synopsis = $this->openAIService->generateSynopsis($book->title, $book->author);
        $book->update(['synopsis' => $synopsis]);

        Log::info("Sinopse gerada para o livro: " . $book->title);

        return redirect()->route('books.index')->with('message', 'Sinopse gerada com sucesso!');
    }

    public function recommendMovies(Book $book)
    {
        try {
            $recommendations = $this->openAIService->recommendMovies($book->title, author: $book->author);

            if (is_string($recommendations)) {
                $recommendedMovies = explode("\n", $recommendations);
            } else {
                Log::error('Formato inválido de recomendação de filmes', ['recommendations' => $recommendations]);
                return redirect()->route('books.index')->with('error', 'Erro ao recomendar filmes.');
            }

            $movieDetails = [];

            foreach ($recommendedMovies as $movieDescription) {
                if (preg_match('/"([^"]+)"/', $movieDescription, $matches)) {
                    $movieTitle = trim($matches[1]);
                } else {
                    continue;
                }

                // Verificar se o título contém palavras irrelevantes
                if (str_contains($movieTitle, 'Travelling')) {
                    continue;
                }

                $response = Http::get('https://api.themoviedb.org/3/search/movie', [
                    'query' => $movieTitle,
                    'api_key' => env('TMDB_API_KEY'),
                ]);

                $data = $response->json();

                if (isset($data['results'][0])) {
                    $movie = $data['results'][0];

                    if (empty($movie['poster_path']) || empty($movie['title'])) {
                        continue;
                    }

                    $movieDetails[] = [
                        'title' => $movie['title'],
                        'poster' => 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'],
                        'year' => $movie['release_date'] ?? 'N/A',
                        'plot' => $movie['overview'] ?? 'Sem descrição',
                    ];
                } else {
                    Log::error('Filme não encontrado no TMDb', ['title' => $movieTitle]);
                }
            }

            return view('books.recomendacoes', [
                'book' => $book,
                'movieDetails' => $movieDetails,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao recomendar filmes: ' . $e->getMessage());
            return redirect()->route('books.index')->with('error', 'Erro ao recomendar filmes.');
        }
    }
}

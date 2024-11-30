<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
    }

    public function recommendMovies($title, $author)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl, [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um especialista em cinema. Retorne apenas títulos reais de filmes.'],
                ['role' => 'user', 'content' => "Recomende até 6 filmes baseados no tema do livro \"$title\" de \"$author\". e de classificação etária parecida"],
            ],
            'max_tokens' => 150,
            'temperature' => 0.8,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return 'Não foi possível obter recomendações.';
    }

    public function generateSynopsis($title, $author)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Você é um assistente que gera sinopses de livros.'],
                    ['role' => 'user', 'content' => "Por favor, gere uma sinopse resumida para o livro \"$title\" de \"$author\"."],
                ],
                'max_tokens' => 150,
                'temperature' => 0.7,
            ]);

            $responseBody = $response->json();

            if (isset($responseBody['choices'][0]['message']['content'])) {
                return trim($responseBody['choices'][0]['message']['content']);
            }

            return 'Não foi possível gerar a sinopse.';
        } catch (\Exception $e) {
            Log::error('Erro ao gerar sinopse: ' . $e->getMessage());
            return 'Erro ao gerar a sinopse.';
        }
    }
}

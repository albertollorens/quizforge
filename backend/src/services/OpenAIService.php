<?php
namespace App\Services;

use App\Config\Settings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class OpenAIService
{
    private Client $client;
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = Settings::openaiKey();

        if (empty($this->apiKey)) {
            throw new \Exception("OpenAI API Key no definida. Revisa tu .env o configuración de Settings.");
        }

        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ],
            'timeout' => 30
        ]);
    }

    public function generateQuestions(string $topic, string $level, int $numQuestions = 5): array
    {
        $prompt = "Genera $numQuestions preguntes tipus test sobre \"$topic\" nivell \"$level\".
                Format JSON exacte:
                {
                    \"questions\": [
                        {
                            \"question\": \"...\",
                            \"options\": [\"...\", \"...\", \"...\", \"...\"],
                            \"correct_answer\": \"...\",
                            \"explanation\": \"...\"
                        }
                    ]
                }
                No afegisques text fora del JSON.";

        try {
            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo', // usar gpt-3.5 para cuentas nuevas sin acceso a GPT-4
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 300
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Parsear el JSON que devuelve OpenAI
            $questionsJson = $data['choices'][0]['message']['content'] ?? '{}';
            $questions = json_decode($questionsJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("OpenAI no ha retornat JSON vàlid: " . $questionsJson);
            }

            return $questions['questions'] ?? [];

        } catch (ClientException $e) {
            $status = $e->getResponse()->getStatusCode();
            $body = $e->getResponse()->getBody()->getContents();

            throw new \Exception("OpenAI API error $status: $body");
        }
    }
}
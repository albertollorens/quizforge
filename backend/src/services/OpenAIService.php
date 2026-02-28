<?php

namespace App\Services;

use App\Config\Settings;
use Exception;
use OpenAI;
use OpenAI\Client;
use Throwable;

class OpenAIService
{
    private Client $client;
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = Settings::openaiKey();

        if (empty($this->apiKey)) {
            throw new Exception("OpenAI API Key no definida");
        }

        try {
            $this->client = OpenAI::client($this->apiKey);
        } catch (Throwable $e) {
            throw new Exception("Error inicializando cliente OpenAI: " . $e->getMessage());
        }
    }

    public function generateQuestions(string $topic, string $level, string $language, int $numQuestions = 5): array
    {
        if (empty($topic)) {
            throw new Exception("El topic es obligatorio");
        }

        if (empty($level)) {
            $level = "basic";
        }

        $prompt = "Genera $numQuestions preguntes tipus test de diferents tipus com per exemple selecció única o múltiple, resposta curta, verdader/fals, emparellaments, assaig, etc.. 
                sobre \"$topic\" de nivell \"$level\" en idioma \"$language\".
                Format JSON exacte:
                {
                    \"questions\": [
                        {
                            \"question\": \"...\",
                            \"question_type\": \"...\",
                            \"options\": [\"...\", \"...\", \"...\", \"...\"],
                            \"correct_answer\": \"...\",
                            \"explanation\": \"...\"
                        }
                    ]
                }
                No afegisques text fora del JSON.";

        try {
            $response = $this->client->responses()->create([
                'model' => 'gpt-4o-mini',
                'input' => $prompt,
                'temperature' => 0.7,
                'max_output_tokens' => 800
            ]);

            //  Extraer texto correctamente
            $text = $response->outputText;

            if (!$text) {
                throw new Exception("OpenAI no devolvió contenido");
            }

            // Limpiar markdown ```json ```
            $text = trim($text);
            $text = preg_replace('/^```json/', '', $text);
            $text = preg_replace('/^```/', '', $text);
            $text = preg_replace('/```$/', '', $text);
            $text = trim($text);

            // Decodificar JSON
            $decoded = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("JSON inválido: " . $text);
            }

            return $decoded;

        } catch (Throwable $e) {
            throw new Exception(
                "Error OpenAI: " . $e->getMessage()
            );
        }
    }
}
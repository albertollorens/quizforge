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
        $prompt = "Ets un generador automàtic de quizzes educatius. Crea un quiz aleatori amb preguntes sobre el tema \"$topic\" en l'idioma \"$language\" i nivell \"$level\", amb un total de $numQuestions preguntes. 
                Les preguntes han de ser de diferents tipus: singlechoice, multichoice, truefalse, shortanswer, essay, i matching. El resultat ha de ser EXACTAMENT en aquest format JSON i només JSON:
                    {
                        \"questions\": [
                            {
                                \"type\": \"singlechoice\",
                                \"title\": \"Títol de la pregunta\",
                                \"statement\": \"Text de la pregunta\",
                                \"answers\": [
                                    {\"text\": \"Resposta correcta\", \"correct\": true, \"weight\": 100},
                                    {\"text\": \"Resposta incorrecta 1\", \"correct\": false, \"weight\": 0},
                                    {\"text\": \"Resposta incorrecta 2\", \"correct\": false, \"weight\": 0},
                                    {\"text\": \"Resposta incorrecta 3\", \"correct\": false, \"weight\": 0}
                                ]
                            },
                            {
                                \"type\": \"multichoice\",
                                \"title\": \"Títol de la pregunta\",
                                \"statement\": \"Text de la pregunta\",
                                \"answers\": [
                                    {\"text\": \"Resposta correcta 1\", \"correct\": true, \"weight\": 50},
                                    {\"text\": \"Resposta correcta 2\", \"correct\": true, \"weight\": 50},
                                    {\"text\": \"Resposta incorrecta 1\", \"correct\": false, \"weight\": -10},
                                    {\"text\": \"Resposta incorrecta 2\", \"correct\": false, \"weight\": -10}
                                ]
                            },
                            {
                                \"type\": \"truefalse\",
                                \"title\": \"Títol de la pregunta\",
                                \"statement\": \"Text de la pregunta\",
                                \"answers\": [
                                    {\"text\": \"True\", \"correct\": true},
                                    {\"text\": \"False\", \"correct\": false}
                                ]
                            },
                            {
                                \"type\": \"shortanswer\",
                                \"title\": \"Títol de la pregunta\",
                                \"statement\": \"Text de la pregunta\",
                                \"answers\": [
                                    {\"text\": \"Resposta correcta 1\", \"correct\": true},
                                    {\"text\": \"Resposta correcta 2\", \"correct\": true}
                                ]
                            },
                            {
                                \"type\": \"essay\",
                                \"title\": \"Títol de la pregunta\",
                                \"statement\": \"Text de la pregunta\"
                            },
                            {
                                \"type\": \"matching\",
                                \"title\": \"Títol de la pregunta\",
                                \"statement\": \"Text de la pregunta\",
                                \"answers\": [
                                    {\"text\": \"Element esquerre 1\", \"match_pair\": \"Element dret 1\"},
                                    {\"text\": \"Element esquerre 2\", \"match_pair\": \"Element dret 2\"}
                                ]
                            }
                        ]
                    }

                    REGLES IMPORTANTS:

                    1. Genera EXACTAMENT aquest JSON, amb tots els camps correctes.
                    2. No afegis res de text fora del JSON.
                    3. Alterna els tipus de preguntes aleatòriament.
                    4. Singlechoice només una resposta correcta amb weight 100.
                    5. Multichoice diverses correctes, repartint el pes total (100%) equitativament entre les respostes correctes i penalitzant les incorrectes amb weight negatiu (-5% o -10%).
                    6. TrueFalse: usa \"True\" i \"False\" amb correct boolean.
                    7. ShortAnswer: múltiples respostes correctes possibles.
                    8. Essay: només title i statement.
                    9. Matching: usa camps text i match_pair.
                    10. Només genera el JSON, llest per guardar directament a la base de dades.";

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
<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\OpenAIService;

class OpenAIController {
    private OpenAIService $openAI;

    public function __construct(OpenAIService $openAI)
    {
        $this->openAI = $openAI;
    }

    public function generate(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $topic = $data['topic'] ?? null;
            $level = $data['level'] ?? 'medium';

            if (!$topic) {
                $response->getBody()->write(json_encode([
                    'error' => 'Topic requerit'
                ]));

                return $response
                    ->withStatus(400)
                    ->withHeader('Content-Type', 'application/json');
            }

            $result = $this->openAI->generateQuestions(
                $topic,
                $level
            );

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => json_decode($result)
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));

            return $response
                ->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}
<?php
namespace App\Controllers;

use App\Models\User;
use App\Config\Settings;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController {

    private User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    // POST /login
    public function login(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
                
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->user->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $response->getBody()->write(json_encode([
                'error' => 'Credenciales incorrectas'
            ]));
            return $response->withStatus(401)
                            ->withHeader('Content-Type', 'application/json');
        }

        // Generar JWToken real
        $payload = [
            'iss' => 'quizforge.local',          // Emisor
            'iat' => time(),                      // Temps d'emisió
            'exp' => time() + 3600,               // Expira en 1 hora
            'sub' => $user['id'],                 // ID d'usuari
            'email' => $user['email'],            // Email d'usuari
            'username' => $user['name'],      // Username d'usuari
        ];

        $jwt = JWT::encode($payload, Settings::jwtSecret(), 'HS256');

        $response->getBody()->write(json_encode([
            'message' => 'Login correcto',
            'token' => $jwt
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    // POST /register
    public function register(Request $request, Response $response): Response {
        $data = $request->getParsedBody();

        if (!isset($data['username'], $data['email'], $data['password'])) {
            $response->getBody()->write(json_encode([
                'error' => 'Datos incompletos'
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        $created = $this->user->create(
            $data['username'],
            $data['email'],
            $data['password']
        );

        if (!$created) {
            $response->getBody()->write(json_encode([
                'error' => 'No se pudo registrar'
            ]));
            return $response->withStatus(500)
                            ->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'message' => 'Usuario registrado correctamente'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    // Validar token JWT (per a usar com middleware)
    public static function validateToken(string $token) {
        try {
            $decoded = JWT::decode($token, new Key(Settings::jwtSecret(), 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}

<?php
namespace App\Controllers;

use App\Models\User;
use App\Helpers\GoogleTokenHelper;
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
        $plan = $data['plan'] ?? '';

        $user = $this->user->findByEmail($email);
        $user['quizzes'] = $this->user->getNumQuizzes($user['id']);

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
            'username' => $user['name'],          // Username d'usuari
            'rol' => $user['rol'],               //Rol de l'usuari
            'plan' => $user['plan'],               //Rol de l'usuari
            'quizzes' => $user['quizzes'],               //Quizzes creats
            //'completedquizzes' => $user['completedquizzes']               //Quizzes completats
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

        $user = $this->user->findByEmail($data['email']);

        if($user) {
            $response->getBody()->write(json_encode([
                'error' => 'Usuario existente'
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        } else {
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
    }

    // POST /api/auth/google
    public function authWithGoogle(Request $request, Response $response): Response {
        $data = $request->getParsedBody();

        if (!isset($data['token'])) {
            $response->getBody()->write(json_encode([
                'error' => 'Token de Google requerido'
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        try {
            $googleToken = $data['token'];

            // Detectar entorno
            $isDevelopment = ($_ENV['APP_ENV'] ?? 'development') === 'development';

            // Verificar token de Google
            $decodedToken = GoogleTokenHelper::verifyAndDecode($googleToken, !$isDevelopment);

            if (!$decodedToken) {
                throw new \Exception('Token de Google inválido');
            }

            // Extraer datos
            $googleId = $decodedToken['sub'] ?? null;
            $email = $decodedToken['email'] ?? null;
            $name = $decodedToken['name'] ?? $decodedToken['given_name'] ?? 'Usuario Google';
            $picture = $decodedToken['picture'] ?? null;

            if (!$googleId || !$email) {
                throw new \Exception('Datos incompletos del token de Google');
            }

            /* =====================================
            LÓGICA UNIFICADA (clave)
            ===================================== */

            // 1. Buscar por google_id
            $user = $this->user->findByGoogleId($googleId);

            if ($user) {
                // ✔ Usuario ya existe con Google → login directo

                // Actualizar foto si cambia
                if ($picture && $user['profile_picture'] !== $picture) {
                    $this->user->updateWithGoogle($user['id'], $googleId, $picture);
                }

            } else {
                // 2. Buscar por email
                $user = $this->user->findByEmail($email);

                if ($user) {
                    // ✔ Usuario existe (registro local) → vincular Google
                    $this->user->updateWithGoogle($user['id'], $googleId, $picture);
                    $user = $this->user->findById($user['id']);

                } else {
                    // 3. Usuario nuevo → crear
                    $created = $this->user->createWithGoogle($googleId, $email, $name, $picture);

                    if (!$created) {
                        throw new \Exception('No se pudo crear usuario');
                    }

                    $user = $this->user->findByGoogleId($googleId);
                }
            }

            // Añadir datos extra
            $user['quizzes'] = $this->user->getNumQuizzes($user['id']);

            /* =====================================
            JWT
            ===================================== */

            $payload = [
                'iss' => 'quizforge.local',
                'iat' => time(),
                'exp' => time() + 3600,
                'sub' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'], // 🔥 corregido (no username)
                'rol' => $user['rol'],
                'plan' => $user['plan'],
                'quizzes' => $user['quizzes'],
                'provider' => $user['provider']
            ];

            $jwt = JWT::encode($payload, Settings::jwtSecret(), 'HS256');

            $response->getBody()->write(json_encode([
                'message' => 'Autenticación con Google exitosa',
                'token' => $jwt,
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'provider' => $user['provider']
                ]
            ]));

            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Error de autenticación: ' . $e->getMessage()
            ]));

            return $response->withStatus(401)
                            ->withHeader('Content-Type', 'application/json');
        }
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

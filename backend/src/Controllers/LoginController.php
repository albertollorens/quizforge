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
            'plan' => $user['plan'],               //Plan de l'usuari
            'quizzes' => $user['quizzes']               //Quizzes creats
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
            $created_id = $this->user->create(
                $data['username'],
                $data['email'],
                $data['password']
            );
            error_log("Register: Usuario creado - ID: " . ($created_id ?? 'null'));

            if (!$created_id) {
                $response->getBody()->write(json_encode([
                    'error' => 'No se pudo registrar'
                ]));
                return $response->withStatus(500)
                                ->withHeader('Content-Type', 'application/json');
            } else {
                $user = $this->user->findByEmail($data['email']);
                // Generar JWToken real
                $payload = [
                    'iss' => 'quizforge.local',          // Emisor
                    'iat' => time(),                      // Temps d'emisió
                    'exp' => time() + 3600,               // Expira en 1 hora
                    'sub' => $user['id'],                 // ID d'usuari
                    'email' => $user['email'],            // Email d'usuari
                    'username' => $user['name'],          // Username d'usuari
                    'rol' => $user['rol'],               //Rol de l'usuari
                    'plan' => $user['plan']               //Plan de l'usuari
                ];
                $jwt = JWT::encode($payload, Settings::jwtSecret(), 'HS256');
    
                $response->getBody()->write(json_encode([
                    'message' => 'Usuario registrado correctamente',
                    'token' => $jwt
                ]));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }
    }

    // POST /api/auth/google
    public function authWithGoogle(Request $request, Response $response): Response {
        //error_log(file_get_contents("php://input"));
        $data = $request->getParsedBody();

        if (!isset($data['token'])) {
            //error_log("AuthWithGoogle: Token no proporcionado");
            $response->getBody()->write(json_encode([
                'error' => 'Token de Google requerido'
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        try {
            $googleToken = $data['token'];
            //error_log("AuthWithGoogle: Token recibido - " . $googleToken);

            // Detectar entorno
            //$isDevelopment = ($_ENV['APP_ENV'] ?? 'development') === 'development';

            // Verificar token de Google
            $decodedToken = json_decode(
                file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=" . $googleToken),
                true
            );//GoogleTokenHelper::verifyAndDecode($googleToken, !$isDevelopment);

            if (!$decodedToken) {
                //error_log("AuthWithGoogle:  Token de Google inválido");
                throw new \Exception('Token de Google inválido');
            }

            // Extraer datos
            $googleId = $decodedToken['sub'] ?? null;
            $email = $decodedToken['email'] ?? null;
            $name = $decodedToken['name'] ?? $decodedToken['given_name'] ?? 'Usuario Google';
            $picture = $decodedToken['picture'] ?? null;

            if (!$googleId || !$email) {
                //error_log("AuthWithGoogle: Datos incompletos del token de Google - googleId: " . ($googleId ?? 'null') . ", email: " . ($email ?? 'null'));
                throw new \Exception('Datos incompletos del token de Google');
            }

            /* =====================================
            LÓGICA UNIFICADA (clave)
            ===================================== */

            // 1. Buscar por google_id
            $user = $this->user->findByGoogleId($googleId);

            if ($user) {
                // ✔ Usuario ya existe con Google → login directo
                //error_log("AuthWithGoogle: Usuario encontrado por google_id: " . $user['id']);

                // Actualizar foto si cambia
                if ($picture && $user['profile_picture'] !== $picture) {
                    $this->user->updateWithGoogle($user['id'], $googleId, $picture);
                }

            } else {
                // 2. Buscar por email
                //error_log("AuthWithGoogle: Usuario no encontrado por google_id, buscando por email: " . $email);
                $user = $this->user->findByEmail($email);

                if ($user) {
                    // ✔ Usuario existe (registro local) → vincular Google
                    //error_log("AuthWithGoogle: Usuario encontrado por email, vinculando Google - ID: " . $user['id']);
                    $this->user->updateWithGoogle($user['id'], $googleId, $picture);
                    $user = $this->user->findById($user['id']);

                } else {
                    // 3. Usuario nuevo → crear
                    //error_log("AuthWithGoogle: Creando nuevo usuario con Google - ID: " . $googleId);
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
                'username' => $user['name'],
                'picture' => $user['profile_picture'],
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
            error_log("AuthWithGoogle: Autenticación exitosa, JWT generado para usuario ID: " . $user['id']);

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

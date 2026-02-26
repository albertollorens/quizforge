<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Config\Settings;


class AuthMiddleware {

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface {
        $authHeader = $request->getHeaderLine('Authorization');

        // Comprovar header
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)) {
            $res = new Response();
            $res
                ->getBody()
                ->write(json_encode(['error' => 'Token no encontrado']));
            return $res
                    ->withStatus(401)
                    ->withHeader('Content-Type', 'application/json');
        }

        $token = $matches[1];

        // Obtindre clau secreta des de config/Settings        
        $secret = Settings::jwtSecret();        

        if (!$secret || !is_string($secret)) {
            $res = new Response();
            $res
                ->getBody()
                ->write(json_encode(['error' => 'JWT secret no configurado correctamente']));
            return $res
                ->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
        }

        try {
            // Decodificar el token utilitzant Firebase JWT
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            // 4️⃣ Comprobar que existe sub (user_id)
            $user_id = $decoded->sub ?? null;
            if (!$user_id) {
                $res = new Response();
                $res->getBody()->write(json_encode(['error' => 'Token inválido: sub no encontrado']));
                return $res->withStatus(401)->withHeader('Content-Type', 'application/json');
            }

            // Pasar user_id al request per al QuizController
            $request = $request->withAttribute('user_id', $decoded->sub);

            // Continuar amb l'execució del controller
            return $handler->handle($request);

        } catch (\Firebase\JWT\ExpiredException $e) {
            $res = new Response();
            $res
                ->getBody()
                ->write(json_encode(['error' => 'Token expirado']));
            return $res
                    ->withStatus(401)
                    ->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            $res = new Response();
            $res
                ->getBody()
                ->write(json_encode(['error' => 'Token inválido']));
            return $res
                    ->withStatus(401)
                    ->withHeader('Content-Type', 'application/json');
        }
    }
}
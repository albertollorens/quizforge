<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class GoogleTokenHelper {
    
    private static $googleCertsUrl = 'https://www.googleapis.com/oauth2/v1/certs';
    private static $googleKeysUrl = 'https://www.googleapis.com/oauth2/v3/certs';
    
    /**
     * Verificar y decodificar token de Google de forma segura
     */
    public static function verifyAndDecode(string $token, bool $verifySignature = true): ?array {
        try {
            $parts = explode('.', $token);
            
            if (count($parts) !== 3) {
                return null;
            }

            // Decodificar el header para obtener 'kid' (key ID)
            $header = json_decode(
                base64_decode(strtr($parts[0], '-_', '+/')),
                true
            );

            // Decodificar el payload
            $payload = json_decode(
                base64_decode(strtr($parts[1], '-_', '+/')),
                true
            );

            // Verificar firma si está habilitado
            if ($verifySignature) {
                $keyId = $header['kid'] ?? null;
                
                if ($keyId) {
                    $publicKeys = self::getGooglePublicKeys();
                    
                    if (!isset($publicKeys[$keyId])) {
                        error_log('Key ID no encontrado en certificados de Google');
                        return null;
                    }

                    try {
                        $decoded = JWT::decode(
                            $token,
                            new Key($publicKeys[$keyId], 'RS256')
                        );
                        return (array) $decoded;
                    } catch (ExpiredException $e) {
                        error_log('Token de Google expirado');
                        return null;
                    }
                }
            }

            // En desarrollo o sin verificación, retornar el payload decodificado
            return $payload;

        } catch (\Exception $e) {
            error_log('Error decodificando token de Google: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener claves públicas de Google
     * Cachea el resultado por 1 hora
     */
    private static function getGooglePublicKeys(): array {
        $cacheFile = sys_get_temp_dir() . '/google_certs_' . date('YmdH') . '.json';

        if (file_exists($cacheFile)) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if ($cached && isset($cached['keys'])) {
                return self::formatPublicKeys($cached['keys']);
            }
        }

        // Obtener nuevas claves de Google
        $response = @file_get_contents(self::$googleKeysUrl);
        
        if (!$response) {
            error_log('No se pudieron obtener las claves públicas de Google');
            // Fallback: usar la URL antigua
            $response = @file_get_contents(self::$googleCertsUrl);
        }

        if (!$response) {
            return [];
        }

        $keys = json_decode($response, true);

        // Cachear
        file_put_contents($cacheFile, json_encode($keys));

        return isset($keys['keys']) 
            ? self::formatPublicKeys($keys['keys'])
            : [];
    }

    /**
     * Formatear claves públicas de Google
     */
    private static function formatPublicKeys(array $keys): array {
        $formatted = [];

        foreach ($keys as $key) {
            if (isset($key['kid']) && isset($key['x5c'][0])) {
                // Construir certificado PEM
                $cert = "-----BEGIN CERTIFICATE-----\n";
                $cert .= wordwrap($key['x5c'][0], 64, "\n", true);
                $cert .= "\n-----END CERTIFICATE-----";
                
                $formatted[$key['kid']] = $cert;
            }
        }

        return $formatted;
    }

    /**
     * Decodificar token sin verificar firma (DEBUG ONLY)
     */
    public static function decodeWithoutVerification(string $token): ?array {
        try {
            $parts = explode('.', $token);
            
            if (count($parts) !== 3) {
                return null;
            }

            $payload = json_decode(
                base64_decode(strtr($parts[1], '-_', '+/')),
                true
            );

            return $payload;

        } catch (\Exception $e) {
            error_log('Error decodificando token sin verificación: ' . $e->getMessage());
            return null;
        }
    }
}

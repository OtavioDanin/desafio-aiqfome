<?php

declare(strict_types=1);

namespace App\Middleware;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Throwable;

use function Hyperf\Support\env;

class JwtAuthMiddleware implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container, protected HttpResponse $response) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (empty($authHeader) || !preg_match('/^Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $this->response->json(['message' => 'Token não fornecido ou mal formatado'])->withStatus(401);
        }
        $token = $matches[1];
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $request = $request->withAttribute('jwt_payload', $decoded);
            $request = $request->withAttribute('user_id', $decoded->sub);
            return $handler->handle($request);
        } catch (ExpiredException $expiredEx) {
            return $this->response->json(['message' => 'Token expirado'])->withStatus(401);
        } catch (Throwable $thEx) {
            return $this->response->json(['message' => 'Token inválido', 'error' => $e->getMessage()])->withStatus(401);
        }
    }
}

<?php

declare(strict_types=1);

namespace Modules\Common\UI;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Modules\Clients\Application\ClientService;
use Modules\Clients\Domain\ClientServiceInterface;
use Modules\Common\Application\JwtService;
use Modules\Common\Domain\JwtServiceInterface;
use Throwable;

class AuthenticationController extends AbstractController
{
    /**
     * @var ClientServiceInterface
     */
    /**
     * @var JwtServiceInterface
     */
    public function __construct(
        protected JwtService $jwtService,
        protected ClientService $clientService,
    ) {}

    public function generate(RequestInterface $request, ResponseInterface $response): \Psr\Http\Message\ResponseInterface
    {
        try {
            $email = $request->input('email');
            $nome = $request->input('nome');
            $cliente = $this->clientService->checkClienteByNomeAndEmail($nome, $email);
            if (!$cliente) {
                return $response->json(['message' => 'Credenciais inválidas'])->withStatus(401);
            }
            $token = $this->jwtService->generateToken();
            return $response
                ->json(['token' => $token])
                ->withStatus(200);
        } catch (Throwable $thEx) {
            return $response
                ->json(['message' => 'Ocorreu um erro fora do previsto, tente novamente em alguns instantes. ' . $thEx->getMessage()])
                ->withStatus(500);
        }
    }
}

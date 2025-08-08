<?php

declare(strict_types=1);

namespace Modules\Clients\UI\Controllers;

use Hyperf\Database\Model\ModelNotFoundException;
use Modules\Clients\Application\ClientService;
use Modules\Clients\Domain\ClientServiceInterface;
use Throwable;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class ClientController extends AbstractController
{
    /**
     * @var ClientServiceInterface
     */
    public function __construct(protected ClientService $clientService) {}

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $clients = $this->clientService->getAll();
            return $response
                ->json($clients)
                ->withStatus(200);
        } catch (Throwable $thEx) {
            return $response
                ->json(['message' => $thEx->getMessage()])
                ->withStatus(500);
        }
    }

    public function show(ResponseInterface $response, int $id)
    {
        try {
            $client = $this->clientService->getById($id);
            return $response
                ->json($client)
                ->withStatus(200);
        } catch (ModelNotFoundException $moEx) {
            return $response->json(['data' => [],'message' => 'Client not found'])
                ->withStatus(404);
        } catch (Throwable $thEx) {
            return $response
                ->json(['message' => $thEx->getMessage()])
                ->withStatus(500);
        }
    }
}

<?php

declare(strict_types=1);

namespace Modules\Clients\UI\Controllers;

use Hyperf\Database\Exception\QueryException;
use Hyperf\Database\Model\ModelNotFoundException;
use Modules\Clients\Application\ClientService;
use Modules\Clients\Domain\ClientServiceInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\ValidationException;
use Modules\Clients\Application\ClientRequestValidation;
use Modules\Clients\Domain\ClientException;
use Throwable;

class ClientController extends AbstractController
{
    /**
     * @var ClientServiceInterface
     */
    public function __construct(protected ClientService $clientService) {}

    public function index(ResponseInterface $response)
    {
        try {
            $clients = $this->clientService->getAll();
            return $response
                ->json($clients)
                ->withStatus(200);
        } catch (Throwable) {
            return $response
                ->json(['message' => 'Ocorreu um erro fora do previsto, tente novamente em alguns instantes.'])
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
        } catch (ModelNotFoundException) {
            return $response->json(['data' => [], 'message' => 'Client not found'])
                ->withStatus(404);
        } catch (Throwable) {
            return $response
                ->json(['message' => 'Ocorreu um erro fora do previsto, tente novamente em alguns instantes.'])
                ->withStatus(500);
        }
    }

    public function store(ClientRequestValidation $request, ResponseInterface $response)
    {
        try {
            $request->validated();
            $dataClient = $request->all();
            $this->clientService->save($dataClient);
            return $response
                ->json(['data' => $dataClient, 'message' => 'Success in saving the client.'])
                ->withStatus(201);
        } catch (ValidationException) {
            return $response
                ->json(['message' => 'This email address is already registered.'])
                ->withStatus(400);
        } catch (ClientException $cliEx) {
            return $response
                ->json(['message' => $cliEx->getMessage()])
                ->withStatus($cliEx->getCode());
        } catch (QueryException) {
            return $response
                ->json(['message' => 'Failed to save Client.'])
                ->withStatus(503);
        } catch (Throwable $thEx) {
            return $response
                ->json(['message' => 'Ocorreu um erro fora do previsto, tente novamente em alguns instantes.'])
                ->withStatus(500);
        }
    }

    public function update(ClientRequestValidation $request, ResponseInterface $response, int $id)
    {
        try {
            $request->validated();
            $dataClient = $request->all();
            $this->clientService->update($id, $dataClient);
            return $response
                ->json(['data' => $dataClient, 'message' => 'Success in update the client.'])
                ->withStatus(201);
        } catch (ValidationException) {
            return $response
                ->json(['message' => 'Failed on Update, this email address has already been registered.'])
                ->withStatus(400);
        } catch (QueryException) {
            return $response
                ->json(['message' => 'Failed to update the Client.'])
                ->withStatus(503);
        } catch (Throwable) {
            return $response
                ->json(['message' => 'Ocorreu um erro fora do previsto, tente novamente em alguns instantes.'])
                ->withStatus(500);
        }
    }

    public function destroy(ResponseInterface $response, int $id)
    {
        try {
            $this->clientService->delete($id);
        } catch (ModelNotFoundException) {
            return $response
                ->json(['message' => 'Client not found for Delete.'])
                ->withStatus(404);
        } catch (QueryException) {
            return $response
                ->json(['message' => 'Failed to update the Client.'])
                ->withStatus(503);
        } catch (Throwable) {
            return $response
                ->json(['message' => 'Ocorreu um erro fora do previsto, tente novamente em alguns instantes.'])
                ->withStatus(500);
        }
    }
}

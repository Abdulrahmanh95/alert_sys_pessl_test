<?php

namespace App\Action;

use App\Domain\Service\AlertService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AlertAction
{
    private $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        // Collect input from the HTTP request
        $data = (array) $request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $alertId = $this->alertService->createAlert($data);

        // Transform the result into the JSON representation
        $result = [
            'alert_id' => $alertId
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
<?php

namespace App\Action;

use App\Domain\Service\StationPayloadService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class StationPayloadAction
{
    private $stationPayloadService;

    public function __construct(StationPayloadService $stationPayloadService)
    {
        $this->stationPayloadService = $stationPayloadService;
    }

    public function store(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface
    {
        $this->stationPayloadService->storeAll($request->getUploadedFiles()['file'], (int)$args['userId']);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
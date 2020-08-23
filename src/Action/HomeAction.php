<?php

namespace App\Action;

use App\Domain\Repository\EmailQueueRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HomeAction
{
    /**
     * @var EmailQueueRepository
     */
    private $emailQueueRepository;

    public function __construct(EmailQueueRepository $emailQueueRepository)
    {
        $this->emailQueueRepository = $emailQueueRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $response->getBody()
            ->write(json_encode(['success' => true, 'message' => 'Hey All']));

        $this->emailQueueRepository->sendAlertEmail();

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
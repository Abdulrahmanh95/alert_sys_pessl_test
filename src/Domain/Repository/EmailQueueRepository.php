<?php

namespace App\Domain\Repository;

use App\Message\Mail\SendAlertEmail;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Repository.
 */
class EmailQueueRepository
{
    private static $queue = 'emails';

    /**
     * @var PersistentFactory
     */
    private $persistentFactory;

    /**
     * @var Producer
     */
    private $producer;

    /**
     * @var AlertRepository
     */
    private $alertRepository;

    public function __construct(PersistentFactory $persistentFactory, AlertRepository $alertRepository)
    {
        $this->persistentFactory = $persistentFactory;
        $eventDispatcher = new EventDispatcher();
        $this->producer = new Producer($persistentFactory, $eventDispatcher);
        $this->alertRepository = $alertRepository;
    }

    public function sendAlertEmail($user, array $warnings)
    {
        $alerts = [];
        foreach ($warnings as $key => $warning) {
            $alerts[] = ['user_id' => $user['id'], 'type' => $key];
        }
        $this->alertRepository->insertAlerts($alerts);
        $this->producer->produce(new SendAlertEmail($user, $warnings), self::$queue);
    }
}
<?php

use Bernard\QueueFactory\PersistentFactory;
use Bernard\Router\ClassNameRouter;
use Bernard\Consumer;
use Bernard\Queue\RoundRobinQueue;
use Bernard\Message;
use DI\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;

require __DIR__ . '/../vendor/autoload.php';

// Prepare container
$container = $containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

$queueFactory = $container->get(PersistentFactory::class);

// Queues to handle
$queues = ['emails'];

// Jobs
$handlers = [
    App\Message\Mail\SendAlertEmail::class => App\Handler\SendAlertEmailHandler::class,
];

$router = new ClassNameRouter();
$router->add(Message::class, function(Message $message) use ($handlers, $container) {
    $handlerClass = $handlers[$message->getName()];
    $handler = $container->get($handlerClass);
    $handler($message);
});

$queues = array_map(
    function ($queueName) use ($queueFactory) {
        return $queueFactory->create($queueName);
    },
    $queues
);

$eventDispatcher = new EventDispatcher();


$eventDispatcher->addListener(
    Bernard\BernardEvents::INVOKE,
    function(Bernard\Event\EnvelopeEvent $envelopeEvent) {
        echo PHP_EOL . 'Processing: ' . $envelopeEvent->getEnvelope()->getClass();
    }
);

$eventDispatcher->addListener(
    Bernard\BernardEvents::ACKNOWLEDGE,
    function(Bernard\Event\EnvelopeEvent $envelopeEvent) {
        echo PHP_EOL . 'Processed: ' . $envelopeEvent->getEnvelope()->getClass();
    }
);

$eventDispatcher->addListener(
    Bernard\BernardEvents::REJECT,
    function(Bernard\Event\RejectEnvelopeEvent $envelopeEvent) {
        echo PHP_EOL . 'Failed: ' . $envelopeEvent->getEnvelope()->getClass();
        echo $envelopeEvent->getException()->getMessage();
    }
);

// Create a Consumer and start the loop.
$consumer = new Consumer($router, $eventDispatcher);
$consumer->consume(new RoundRobinQueue($queues));
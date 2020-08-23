<?php

use App\Domain\Repository\AlertRepository;
use App\Domain\Repository\EmailQueueRepository;
use App\Handler\SendAlertEmailHandler;
use Bernard\Driver\PredisDriver;
use Bernard\Normalizer\EnvelopeNormalizer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Serializer;
use Normalt\Normalizer\AggregateNormalizer;
use Predis\Client;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details']
        );
    },

    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];

        $host       = $settings['host'];
        $dbname     = $settings['database'];
        $username   = $settings['username'];
        $password   = $settings['password'];
        $charset    = $settings['charset'];
        $flags      = $settings['flags'];
        $dsn        = "mysql:host=$host;dbname=$dbname;charset=$charset";

        return new PDO($dsn, $username, $password, $flags);
    },

    PredisDriver::class => function (ContainerInterface $container) {
        $predis = new Client('tcp://localhost', array(
            'prefix' => 'bernard:',
        ));

        return new PredisDriver($predis);
    },

    PersistentFactory::class => function (ContainerInterface $container) {
        $driver = $container->get(PredisDriver::class);

        return new PersistentFactory(
            $driver,
            new Serializer(
                new AggregateNormalizer([
                    new EnvelopeNormalizer(),
                    new Symfony\Component\Serializer\Serializer(
                        [new ObjectNormalizer()],
                        [new JsonEncoder()]
                    ),
                ])

            )
        );
    },

    AlertRepository::class => function (ContainerInterface $container) {
        $pdo = $container->get(PDO::class);
        return new AlertRepository($pdo);
    },

    EmailQueueRepository::class => function (ContainerInterface $container) {
        $persistentFactory = $container->get(PersistentFactory::class);
        $alertRepository = $container->get(AlertRepository::class);
        return new EmailQueueRepository($persistentFactory, $alertRepository);
    },

    SendAlertEmailHandler::class => function (ContainerInterface $container) {
        $alertRepository = $container->get(AlertRepository::class);
        return new SendAlertEmailHandler($alertRepository);
    }
];
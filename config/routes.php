<?php

use App\Action\AlertAction;
use App\Action\HomeAction;
use App\Action\StationPayloadAction;
use Slim\App;

return function (App $app) {
    // Home
    $app->get('/', HomeAction::class)->setName('home');
    // Alert
    $app->post('/alert', AlertAction::class);
    // Station Payloads
    $app->post('/station/payloads/{userId}', StationPayloadAction::class . ":store");
};
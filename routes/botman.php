<?php

use BotMan\BotMan\Middleware\Dialogflow;

$botman = resolve('botman');

$dialogflow = Dialogflow::create(config('services.dialogflow.token'));

$botman->middleware->received($dialogflow);
$botman->middleware->matching($dialogflow);

$botman->hears('Feedback Intent', function ($bot) {
    $bot->reply($bot->getMessage()->getExtras('apiReply'));
});
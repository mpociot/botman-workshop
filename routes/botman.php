<?php

use GuzzleHttp\Client;

$botman = resolve('botman');

$botman->hears('deploy {projectname}', function ($bot, $projectname) {

    $client = new Client();
    $response = $client->get('http://workshop-api.dev.beyondco.de/api/projects');
    $projects = collect(json_decode($response->getBody()->getContents(), true));

    $project = $projects->first(function ($project) use ($projectname) {
        return strcasecmp($project['name'], $projectname) === 0;
    });

    if (is_null($project)) {
        $bot->reply('A project called "'.$projectname.'" is not available');
        return false;
    }

    $bot->reply('Deploying '.$project['slug']);

});
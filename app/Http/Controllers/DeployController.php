<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Http\Conversations\DeploymentConversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class DeployController
{

    private function getProjects()
    {
        $response = (new Client())->get('http://botman-project-api.test/api/projects');
        return collect(json_decode($response->getBody()->getContents(), true));
    }

    public function deployProject(BotMan $bot, $projectname)
    {
        $project = $this->getProjects()->first(function ($project) use ($projectname) {
            return strtolower($project['name']) === $projectname;
        });

        if (is_null($project)) {
            $bot->reply('A project called "' . $projectname . '" is not available');
            return false;
        }

        $bot->reply('Deploying ' . $project['slug']);
    }

    public function deploy(BotMan $bot)
    {
        $projects = $this->getProjects();

        $bot->startConversation(new DeploymentConversation($projects));
    }

}
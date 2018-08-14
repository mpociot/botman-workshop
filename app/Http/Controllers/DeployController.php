<?php

namespace App\Http\Controllers;

use BotMan\BotMan\Messages\Incoming\Answer;
use GuzzleHttp\Client;
use BotMan\BotMan\BotMan;

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

        $message = 'Which project do you want to deploy?' . PHP_EOL;

        foreach ($projects as $key => $project) {
            $message .= ++$key.')' . $project['name'] . PHP_EOL;
        }

        $bot->ask($message,  function (Answer $answer) use ($projects) {
            $key = (int)trim($answer->getText());
            if (isset($projects[$key-1])) {
                $this->say('You selected '.$projects[$key-1]['name']);

                // Deployment logic goes here...

                return;
            }

            $this->repeat('Invalid selection. Please try again.');
        });

    }

}
<?php

namespace App\Http\Controllers;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
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

        $question = Question::create('Which project do you want to deploy?')
            ->callbackId('deploy_project');

        foreach ($projects as $key => $project) {
            $question->addButton(
                Button::create($project['name'])->value($project['slug'])
            );
        }

        $bot->ask($question,  function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->say('Deploying '.$answer->getValue());

                return;
            }

            $this->say('Invalid selection. Please try again.');

            $this->repeat();
        });

    }

}
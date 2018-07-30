<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class DeploymentConversation extends Conversation
{
    /** @var Collection */
    private $projects;

    public function __construct(Collection $projects)
    {
        $this->projects = $projects;
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askProject();
    }

    protected function askProject()
    {
        $question = Question::create('Which project do you want to deploy?')
            ->callbackId('deploy_project');

        foreach ($this->projects as $key => $project) {
            $question->addButton(
                Button::create($project['name'])->value($project['slug'])
            );
        }

        $this->ask($question, function (Answer $answer) {
            if (! $answer->isInteractiveMessageReply()) {
                $this->say('Invalid selection. Please try again.');

                $this->repeat();

                return;
            }

            $this->confirm($answer);
        });
    }

    protected function confirm(Answer $answer)
    {
        $confirmationQuestion = Question::create('Are you sure you want to deploy "'.$answer->getValue().'"?')
            ->callbackId('confirm_deployment');

        $confirmationQuestion->addButtons([
            Button::create('Yes')
                ->value('yes')
                ->additionalParameters([
                    'style' => 'danger'
                ]),
            Button::create('No')
                ->value('no')
        ]);

        $this->ask($confirmationQuestion, function (Answer $answer) {
            if ($answer->getValue() === 'yes') {
                $this->say('Deploying');
            } else {
                $this->say('Alright, your choice.');
            }
        });
    }
}

<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ExampleConversation extends Conversation
{

    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function askName($name)
    {
        $this->ask('Entrer la réponse souhaité', function(Answer $answer) use($name) {
            $contents = file_get_contents('question.json');
            $contents = json_decode($contents);
            array_push($contents, ["$name", "$answer"]);
            $contentsEncoded = json_encode($contents);
            file_put_contents('question.json', $contentsEncoded);

            $this->say("La réponse a été ajouté.");
        });
    }

    public function askAdmin($name)
    {
        if($contents = file_get_contents('askAdmin.json') == false){
            $contents = '[]';
        }
        else {
            $contents = file_get_contents('askAdmin.json');
        }
        
        $contents = json_decode($contents);
        array_push($contents, "$name");
        $contentsEncoded = json_encode($contents);
        file_put_contents('askAdmin.json', $contentsEncoded);

        $this->say("J'analyse votre demande.");
    }


    /**
     * First question
     */
    public function askReason()
    {
        $name = $this->name;
        $question = Question::create("Je ne comprend pas, voulez-vous que je me renseigne ?")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Oui')->value('y'),
                Button::create('Non')->value('n'),
            ]);

        return $this->ask($question, function (Answer $answer) use($name) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'y') {
                    $this->askAdmin($name);
                } else {
                    $this->say('Posez une autre question.');
                }
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}

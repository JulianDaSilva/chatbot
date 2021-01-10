<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Interfaces\Middleware\Matching\CustomMatchingMiddleware;

$botman = resolve('botman');

$content = '../public/question.json';
$c = file_get_contents($content);
$c = json_decode($c);

foreach ($c as $s) {
    $variable = $s[1];
    $vstring = strval($s[0]);

    $regex = '^.*(';
    $explodeString = explode(" ", $vstring);
    foreach ($explodeString as $word) {
        $regex .= $word . '|';
    }
    $regex = substr($regex, 0, -1);
    $regex .= ').*$';

    // print $regex . '<br>';
    
    $botman->hears($regex, function ($bot) use($variable) {
        $bot->reply($variable);
    });
}

$botman->hears('^.*(foo|bar|baz).*$', function ($bot) use($variable) {
    $bot->reply('test reg ex');
});

$botman->fallback(BotManController::class.'@startConversation');

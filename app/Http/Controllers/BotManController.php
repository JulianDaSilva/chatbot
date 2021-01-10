<?php

namespace App\Http\Controllers;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use App\Conversations\SearchKeyword;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    public function logout() {
        session_start();
        unset($_SESSION['auth']);
        return redirect('/');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

     /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view('login');
    }

    public function loginAuth(Request $request)
    {
        session_start();
        if (($_POST['login'] == "admin") && ($_POST['pass'] == "admin")) {
            $_SESSION['auth'] = true;
            return redirect('/botman/admin');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function admin()
    {
        $contents = file_get_contents('askAdmin.json');
        $contents = json_decode($contents);
        return view('admin', ['ask' => 'test']);
    }                                                                                                                                                                                             

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $name = $bot->getMessage()->getText();
        $bot->startConversation(new ExampleConversation($name));
    }

    public function store(Request $request)
    {
        if (isset($_POST['viewdata'])) {
            $explode = explode(',', $_POST['viewdata']);
        }
        else {
            $explode = '';
        }
        
        $data = $request->all();
        #create or update your data here
        if ($_POST['file'] == 'askAdmin.json') {
            $infoJson = json_encode($_POST['data']);
        }
        else {
            $infoJson = $_POST['data'];
        }
        file_put_contents($_POST['file'], $infoJson);

        return response()->json(['success' => $_POST['data'], 'file' => $_POST['file']]);
    }

    public static function toJson($ask)
    {
        return implode(',', $ask);
    }
}

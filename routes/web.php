<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::put('/', 'BotManController@logout');

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
Route::get('/botman/admin', function()
{
    $contents = file_get_contents('askAdmin.json');
    $contents = json_decode($contents);
    $contentsExist = file_get_contents('question.json');
    $contentsExist = json_decode($contentsExist);
    $keywordexist = [];
    $existAnswer = [];
    foreach ($contentsExist as $value) {
        $keywordexist[] = $value[0];
        $existAnswer[$value[0]] = $value[1]; 
    }
    return view('admin', ['ask' => $contents, 'exist' => $keywordexist, 'existAnswer' => $existAnswer]);
})->middleware('App\Http\Middleware\Auth');
Route::post('/botman/admin', 'BotManController@store');
Route::get('/botman/login', 'BotManController@login');
Route::put('/botman/login', 'BotManController@loginAuth');

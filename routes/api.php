<?php


use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Routes pour event

Route::resource('events', 'EventController'); //Créer les GET / POST / GET {} Associés
Route::post('createevents', 'EventController@newevent'); //POST Créer un event
Route::delete('leaveevent/{user}', 'EventController@leaveevent'); //POST Créer un event
Route::get('myevents/{user}', 'EventController@myevents'); //GET des events qu'on a créé
Route::get('joinedevents/{user}', 'EventController@joinedevents'); //GET des events qu'on a créé

//Routes pour Notation

Route::resource('notation', 'NotationController');
Route::get('mynotes/{user}', 'NotationController@mynotes'); //GET des events qu'on a créé

//Routes pour Commentaire

Route::resource('commentaire', 'CommentaireController');

//Routes pour Join

Route::resource('join', 'JoinController');
Route::post('leave', 'JoinController@leave');

//Routes pour profil

Route::resource('profil', 'ProfilController');
Route::get('toutesvilles', 'ProfilController@toutesvilles'); //GET des events qu'on a créé


//Creation Connexion
Route::post('connexion', 'LoginController@connexion');
Route::post('inscription', 'LoginController@inscription');
Route::post('suppression', 'LoginController@suppression');


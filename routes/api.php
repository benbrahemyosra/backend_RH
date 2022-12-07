<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController; 

/* ************************** AUTHENTIFICATION ************************** */
Route::post('register', [AuthController::class, 'register']) ;
Route::post('login', [AuthController::class, 'login']) ;
Route::get('/sendemail', 'congesController@sendemail');
Route::get('test', 'congesController@test');
route::get('select_use/{id}', 'congesController@select_use');
route::get('congeTraitement', 'congesController@traitement1');
route::get('congeTest', 'congesController@traitement2');
Route::get('traitement','congesController@traitement');
Route::get('resultatRapport','congesController@resultatRapport');
Route::get('showPlanning/', 'planningController@showPlanning');
Route::get('show', 'congesController@traitement');


/* ************************ MIDDLEWARE SANCTUM ************************ */
Route::middleware(['auth:sanctum'])->group(function() {

/* ************************ LOGOUT ************************* */
Route::post('logout', [AuthController::class, 'logout']) ;

/* ************************ USERS ************************** */
Route::get('users','userController@index');
Route::get('get_my_info','userController@get_my_info'); 
Route::get('get_info_user/{id}','userController@get_info_user');
Route::post('create_user','userController@add_user');
Route::put('update_user/{id}','userController@update_user');
Route::post('mailing_user/{id}','userController@send_user_email');
Route::post('mailing_users','userController@send_users_email');
Route::post('mailing_rh','userController@send_rh_email');
Route::get('search_user','userController@search_user');
Route::delete('delete_user/{id}','userController@delete_user') ;

/* ************************ CONGES *************************** */ 
Route::resource('conges','congesController');
Route::get('/mes_conges/{id}', 'congesController@affiche_my_conge');
Route::get('/congesByStatus', 'congesController@congeByStatus');
Route::get('/affiche_conge_user/{id}', 'congesController@affiche_conge_user');
Route::get('/date', 'congesController@date');
Route::post('/update_Conge/{id}', 'congesController@update');
Route::get('search_conge', 'congesController@search_conge');
Route::post('acceptconges/{id}', 'congesController@AcceptConge');
Route::post('refusconges/{id}', 'congesController@RefusConge');
Route::get('congesPris','congesController@congesPris');
Route::get('congesRestants','congesController@congesRestants');
Route::get('destroy_demand/{id}','congesController@destroy_demand');
Route::get('modif_demand/{id}','congesController@modif_demand');
Route::get('conges_modification','congesController@conges_modification');
Route::get('conges_creation','congesController@conges_creation');
Route::get('conges_accept','congesController@conges_accept');
Route::get('conges_suppression','congesController@conges_suppression');
Route::get('conges_accept_creation','congesController@conges_accept_creation');
Route::get('conges_accept_modification','congesController@conges_accept_modification');
Route::get('conges_attent','congesController@conges_attent');
Route::get('conges_attent_modification','congesController@conges_attent_modification');
Route::get('conges_attent_creation','congesController@conges_attent_creation');
Route::get('conges_refus','congesController@conges_refus');
Route::get('conges_refus_creation','congesController@conges_refus_creation');
Route::get('conges_refus_modification','congesController@conges_refus_modification');
Route::get('/verif_status', 'congesController@verif_status');
Route::get('/verif_demand', 'congesController@verif_demand');
Route::get('search_Conges','congesController@search_conges');

/* ************************ TYPE CONGES *************************** */ 
Route::resource('typeconge','typecongeController');

/* ************************ DEMANDE ************************** */
Route::resource('demande','demandeController');
Route::get('/mes_demandes', 'demandeController@affiche_mes_demande');
Route::get('/affiche_demande_user/{id}', 'demandeController@affiche_demande_user');
Route::post('/update_Conge/{id}', 'congesController@UpdateConge');
Route::get('search_demande', 'demandeController@search_demande') ;
Route::post('acceptdemande/{id}', 'demandeController@AcceptDemande');
Route::post('refusdemande/{id}', 'demandeController@RefusDemande');
Route::get('/demande_modification', 'demandeController@demande_modification');
Route::get('/demande_creation', 'demandeController@demande_creation');
Route::get('/demande_suppression', 'demandeController@demande_suppression') ;
Route::get('/demande_accept', 'demandeController@demande_accept');
Route::get('/demande_accept_creation', 'demandeController@demande_accept_creation');
Route::get('/demande_accept_modification', 'demandeController@demande_accept_modification');
Route::get('/demande_attent', 'demandeController@demande_attent');
Route::get('/demande_attent_creation', 'demandeController@demande_attent_creation');
Route::get('/demande_attent_modification', 'demandeController@demande_attent_modification');
Route::get('/demande_refus_creation', 'demandeController@demande_refus_creation');
Route::get('/demande_refus_modification', 'demandeController@demande_refus_modification');
Route::delete('/destroy_demand', 'demandeController@destroy_demand');
Route::get('/verif_status', 'demandeController@verif_status');
Route::get('/verif_demand', 'demandeController@verif_demand');

/* ************************ PARAMETRE ************************** */
Route::resource('parametre', 'parametreController');

/* ************************ TYPE DEMANDE ************************** */
Route::resource('typedemande', 'typedemandeController');
//Route::get('search_demande', 'planningController@search_planning');

/* ************************ POSTE ************************** */
Route::resource('poste', 'posteController');

/* ************************ PLANNING ************************** */
Route::resource('planning', 'planningController');
Route::get('search_planning', 'planningController@search_planning');
/* ************************ TACHE ************************** */
Route::resource('tache','tacheController');
Route::get('show1/{id}', 'tacheController@show1');
/* ************************ TYPE PLANNING ************************** */
Route::resource('typeplanning', 'typeplanningController');

/* ************************ TYPE EMPLOYEE ************************** */
Route::resource('typeemployee', 'typeemployeeController');

/* ************************ POINTAGE ************************** */
Route::resource('pointage', 'pointageController');

Route::get('positionPoint/{id}', 'pointageController@getPosition');

Route::get('search_pointage', 'pointageController@search_pointage');
Route::get('calcul_heures/{id}', 'pointageController@calcul_heures');
Route::get('typeplanning/{name}', 'typeplanigController@show');

//Route::resource('pointage/{id}', 'pointageController@show');

});                                                                                                         




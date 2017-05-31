<?php

use TinyLara\Routing\Router as Route;

Route::get('/', 'HomeController@home');
Route::get('record/(:any)', 'HomeController@home');
Route::any('record', 'RecordapiController@record');
Route::get('testqueue', 'RecordapiController@amqpPublisher');
Route::get('receive', 'RecordapiController@amqpConsummer');


Route::dispatch();
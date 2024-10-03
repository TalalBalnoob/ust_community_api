<?php

use App\Models\Major;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return view('welcome');
});


Route::get('/test', function () {
	$major = Major::all()->random();
	dd($major['years_of_study']);
});

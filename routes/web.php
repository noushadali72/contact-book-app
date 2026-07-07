<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/',[ContactController::class,'index']);
Route::get('/create',[ContactController::class,'create']);
Route::get('/edit',[ContactController::class,'edit']);

Route::post('/store',[ContactController::class,'store']);
Route::put('/update',[ContactController::class,'update']);
Route::delete('/delete',[ContactController::class,'destroy']);
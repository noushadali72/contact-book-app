<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/',[ContactController::class,'index']);
Route::get('/create',[ContactController::class,'create']);
Route::get('/edit/{id}',[ContactController::class,'edit']);

Route::post('/store',[ContactController::class,'store'])->name("contacts.store");
Route::put('/update',[ContactController::class,'update'])->name("contacts.update");
Route::delete('/delete/{id}',[ContactController::class,'destroy'])->name("contacts.delete");
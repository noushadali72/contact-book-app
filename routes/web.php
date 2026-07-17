<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/',[ContactController::class,'index'])->name('contacts.index');
Route::get('/create',[ContactController::class,'create'])->name('contacts.create');
Route::get('/edit/{contact}',[ContactController::class,'edit'])->name('contacts.edit');

Route::post('/store',[ContactController::class,'store'])->name("contacts.store");
Route::put('/update/{contact}',[ContactController::class,'update'])->name("contacts.update");
Route::delete('/delete/{id}',[ContactController::class,'destroy'])->name("contacts.delete");

Route::get("/groups/search",[ContactController::class,'searchGroup'])->name('searchGroup');
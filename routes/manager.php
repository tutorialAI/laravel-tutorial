<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// /manager/{id}
Route::get('/{id}', function (int $id) {
    return "Manager page" . $id;
});

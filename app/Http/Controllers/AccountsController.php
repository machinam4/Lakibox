<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountsController extends Controller
{
    protected $fillable = [
        'phone',
        'balance',
    ];
}

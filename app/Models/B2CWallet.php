<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2CWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'shortcode',
        'initiator',
        'SecurityCredential',
        'key',
        'secret',
        'passkey',
    ];
}
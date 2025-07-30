<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountModel extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'token',
        'updated_at'
    ];
}

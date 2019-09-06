<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
  protected $table = 'users';

  protected $fillable = [
    'id',
    'name',
    'email',
    'password',
    'recovery_password"',
    'status',
    'create_at',
    'update_at',
  ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
  protected $table = 'permissions';
  protected $hidden = ['created_at', 'updated_at'];
  protected $fillable = [
    'id',
    'user_id',
    'application_id',
  ];
}

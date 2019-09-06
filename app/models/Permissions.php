<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
  protected $table = 'permissions';

  protected $fillable = [
    'id',
    'user_id',
    'applications_id',
  ];
}

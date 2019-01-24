<?php

namespace Approval\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class RegularUser extends Model
{
    protected $fillable = [
      'firstname',
      'lastname',
      'email',
      'birth_at',
    ];
}

<?php

namespace Approval\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialUser extends Model
{
    protected $fillable = [
      'firstname',
      'lastname',
      'email',
      'birth_at',
    ];
}

<?php

namespace Approval\Tests\Models;

use Approval\Traits\ApprovesChanges;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use ApprovesChanges;

    protected $fillable = [
      'name',
    ];
}

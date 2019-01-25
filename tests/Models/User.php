<?php

namespace Approval\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Approval\Traits\ApprovesChanges;

class User extends Authenticatable
{
    use ApprovesChanges;

    protected $fillable = [
      'name',
    ];
}

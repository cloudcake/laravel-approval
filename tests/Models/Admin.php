<?php

namespace Approval\Tests\Models;

use Approval\Traits\ApprovesChanges;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use ApprovesChanges;

    protected $fillable = [
      'name',
    ];
}

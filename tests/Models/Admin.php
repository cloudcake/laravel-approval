<?php

namespace Approval\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Approval\Traits\ApprovesChanges;

class Admin extends Model
{
    use ApprovesChanges;
    
    protected $fillable = [
      'name',
    ];
}

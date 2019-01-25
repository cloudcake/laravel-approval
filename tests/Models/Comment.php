<?php

namespace Approval\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Approval\Traits\RequiresApproval;

class Comment extends Model
{
    use RequiresApproval;
    
    protected $fillable = [
      'content',
    ];
}

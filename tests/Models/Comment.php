<?php

namespace Approval\Tests\Models;

use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use RequiresApproval;

    protected $fillable = [
      'content',
    ];
}

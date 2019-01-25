<?php

namespace Approval\Tests\Models;

use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use RequiresApproval;

    protected $fillable = [
      'title',
      'content',
    ];
}

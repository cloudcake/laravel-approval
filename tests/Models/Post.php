<?php

namespace Approval\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Approval\Traits\RequiresApproval;

class Post extends Model
{
    use RequiresApproval;
    
    protected $fillable = [
      'title',
      'content',
    ];
}

<?php

namespace Approval\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
      'title',
      'content',
    ];
}

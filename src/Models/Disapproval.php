<?php

namespace Approval\Models;

use Illuminate\Database\Eloquent\Model;

class Disapproval extends Model
{
    protected $guarded = ['id'];
    
    public function modification()
    {
        return $this->belongsTo(\Approval\Models\Modification::class);
    }
}

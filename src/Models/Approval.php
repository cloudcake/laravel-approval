<?php

namespace Approval\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    /**
     * The attributes that can't be filled.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get models that the approval belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function approver()
    {
        return $this->morphTo();
    }

    /**
     * Return Modification relation via direct relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modification()
    {
        return $this->belongsTo(\Approval\Models\Modification::class);
    }
}

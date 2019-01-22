<?php

namespace Approval\Models;

use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    /**
    * The attributes that can't be filled.
    *
    * @var array
    */
    protected $guarded = ['id'];

    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    protected $casts = [
        'modifications' => 'json'
    ];

    /**
    * Get models that the modification belongs to.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphTo
    */
    public function modifiable()
    {
        return $this->morphTo();
    }

    /**
    * Return Approval relations via direct relation.
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function approvals()
    {
        return $this->hasMany(\Approval\Models\Approval::class);
    }

    /**
    * Return Disapproval relations via direct relation.
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function disapprovals()
    {
        return $this->hasMany(\Approval\Models\Disapproval::class);
    }

    /**
    * Get the number of approvals reamaining for the changes
    * to be approved and approval will close.
    *
    * @return integer
    */
    public function getApproversRemainingAttribute()
    {
        return ($this->approvers_required - $this->approvals()->count());
    }

    /**
    * Get the number of disapprovals reamaining for the changes
    * to be disapproved and approval will close.
    *
    * @return integer
    */
    public function getDisapproversRemainingAttribute()
    {
        return ($this->disapprovers_required - $this->disapprovals()->count());
    }

    /**
    * Convenience alias of ApproversRemaining attribute.
    *
    * @return integer
    */
    public function getApprovalsRemainingAttribute()
    {
        return $this->approversRemaining;
    }

    /**
    * Convenience alias of DisapproversRemaining attribute.
    *
    * @return integer
    */
    public function getDisapprovalsRemainingAttribute()
    {
        return $this->disapproversRemaining;
    }
}

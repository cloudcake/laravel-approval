<?php

namespace Properties\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    /**
    * Get all of the approval's relations.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphTo
    */
    public function approvables()
    {
        return $this->morphTo();
    }

    /**
    * Get the item that is the subject of the approval changes.
    *
    * @return integer
    */
    public function item()
    {
        return $this->approvables()
                    ->wherePivot('is_approver', false);
    }

    /**
    * Get all of the approval's relations where the they are an approver
    * and the approver approved.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphTo
    */
    public function approvers()
    {
        $this->approvables()
             ->wherePivot('is_approver', true)
             ->wherePivot('approved', true);
    }

    /**
    * Get all of the approval's relations where the they are an approver
    * and the approver disapproved.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphTo
    */
    public function disapprovers()
    {
        $this->approvables()
             ->wherePivot('is_approver', true)
             ->wherePivot('approved', false);
    }

    /**
    * Get the total number of approvals required for the changes
    *  to be approved.
    *
    * @return integer
    */
    public function approvalsRequired()
    {
        return $this->approversRequired;
    }

    /**
    * Get the number of approvals reamaining for the changes
    * to be approved and approval will close.
    *
    * @return integer
    */
    public function approvalsRemaining()
    {
        return ($this->approversRequired - $this->approvers()->count());
    }

    /**
    * Get the number of disapprovals reamaining for the changes
    * to be disapproved and approval will close.
    *
    * @return integer
    */
    public function disapprovalsRemaining()
    {
        return ($this->approversRequired - $this->disapprovers()->count());
    }
}

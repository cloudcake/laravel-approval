<?php

namespace Properties\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    public function approvables()
    {
        return $this->morphTo();
    }

    public function approvers()
    {
        $this->approvables()
             ->wherePivot('is_approver', true)
             ->wherePivot('approved', true);
    }

    public function disapprovers()
    {
        $this->approvables()
             ->wherePivot('is_approver', true)
             ->wherePivot('approved', false);
    }

    public function approvalsRequired()
    {
        return $this->approversRequired;
    }

    public function approvalsRemaining()
    {
        return ($this->approversRequired - $this->approvers()->count());
    }

    public function disapprovalsRemaining()
    {
        return ($this->approversRequired - $this->disapprovers()->count());
    }
}

<?php

namespace Approval\Traits;

trait RequiresApproval
{
    /**
    * Number of approvers this model requires in order
    * to mark the modifications as accepted.
    *
    * Default: 2
    *
    * This setting overrides the configuration file setting.
    *
    * @var integer
    */
    protected $approversRequired = 2;

    /**
    * Boolean to mark whether or not this model should be updated
    * automatically upon receiving the required number of approvals.
    *
    * Default: true
    *
    * This setting overrides the configuration file setting.
    *
    * @var boolean
    */
    protected $updateWhenApproved = true;

    /**
    * Function that defines the rule of when an approval process
    * should be actioned for this model.
    *
    * @param array $modifications
    * @return boolean
    */
    protected function requiresApprovalsWhen($modifications) : boolean
    {
        return true;
    }

    /**
    * Return Approval relations via moprhMany.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphMany
    */
    public function approvals()
    {
        return $this->morphMany(\Approvals\Models\Approval::class, 'approvable');
    }
}

<?php

namespace Approval\Traits;

trait RequiresApproval
{
    /**
    * Number of approvers this model requires in order
    * to mark the modifications as accepted.
    *
    * This setting overrides the configuration file setting.
    *
    * @var integer|null
    */
    protected $approversRequired = null;

    /**
    * Boolean to mark whether or not this model should be updated
    * automatically upon receiving the required number of approvals.
    *
    * This setting overrides the configuration file setting.
    *
    * @var boolean|null
    */
    protected $updateWhenApproved = null;


    /**
    * Boolean to mark whether or not the approval model should be deleted
    * automatically when the approval is disapproved wtih the required number
    * of disapprovals.
    *
    * This setting overrides the configuration file setting.
    *
    * @var boolean|null
    */
    protected $deleteWhenDisapproved = null;

    /**
    * Boolean to mark whether or not the approval model should be deleted
    * automatically when the approval is approved wtih the required number
    * of approvals.
    *
    * This setting overrides the configuration file setting.
    *
    * @var boolean|null
    */
    protected $deleteWhenApproved = null;


    /**
    * Boot the RequiresApproval trait. Listen for events and perform logic.
    *
    */
    public static function bootRequiresApproval()
    {
        static::updating(function ($item) {
            if ($item->requiresApprovalWhen($item->getChanges()) === true) {
                $approval = new \Approval\Models\Approval();
                $approval->is_open = true;
                $approval->modifications = $item->getChanges();
                $approval->save();

                $item->approvals()->attach($approval);

                return false;
            }
        });
    }


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

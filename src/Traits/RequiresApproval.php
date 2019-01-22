<?php

namespace Approval\Traits;

trait RequiresApproval
{
    /**
    * Number of approvers this model requires in order
    * to mark the modifications as accepted.
    *
    * @var integer
    */
    protected $approversRequired = 1;

    /**
    * Number of disapprovers this model requires in order
    * to mark the modifications as rejected.
    *
    * @var integer
    */
    protected $disapproversRequired = 1;

    /**
    * Boolean to mark whether or not this model should be updated
    * automatically upon receiving the required number of approvals.
    *
    * @var boolean
    */
    protected $updateWhenApproved = true;

    /**
    * Boolean to mark whether or not the approval model should be deleted
    * automatically when the approval is disapproved wtih the required number
    * of disapprovals.
    *
    * @var boolean
    */
    protected $deleteWhenDisapproved = false;

    /**
    * Boolean to mark whether or not the approval model should be deleted
    * automatically when the approval is approved wtih the required number
    * of approvals.
    *
    * @var boolean
    */
    protected $deleteWhenApproved = true;


    /**
    * Boot the RequiresApproval trait. Listen for events and perform logic.
    *
    */
    public static function bootRequiresApproval()
    {
        static::updating(function ($item) {
            if ($item->requiresApprovalWhen($item->getDirty()) === true) {
                $diff = collect($item->getDirty())
                        ->transform(function ($change, $key) use ($item) {
                            return [
                              'original' => $item->getOriginal($key),
                              'modified' => $item->$key,
                            ];
                        })
                        ->all();

                $modification = new \Approval\Models\Modification();
                $modification->active = true;
                $modification->modifications = $diff;
                $modification->approvers_required = $item->approversRequired;
                $modification->disapprovers_required = $item->disapproversRequired;
                $modification->save();

                $item->modifications()->save($modification);

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
    protected function requiresApprovalWhen($modifications) : bool
    {
        return true;
    }

    /**
    * Return Modification relations via moprhMany.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphMany
    */
    public function modifications()
    {
        return $this->morphMany(\Approval\Models\Modification::class, 'modifiable');
    }
}

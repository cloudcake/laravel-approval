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
            if (!isset($item->forcedApprovalUpdate) && $item->requiresApprovalWhen($item->getDirty()) === true) {
                $diff = collect($item->getDirty())
                        ->transform(function ($change, $key) use ($item) {
                            return [
                              'original' => $item->getOriginal($key),
                              'modified' => $item->$key,
                            ];
                        })
                        ->all();

                $modifier = $item->modifier();

                $modification = new \Approval\Models\Modification();
                $modification->active = true;
                $modification->modifications = $diff;
                $modification->approvers_required = $item->approversRequired;
                $modification->disapprovers_required = $item->disapproversRequired;

                if ($modifier && ($modifierClass = get_class($modifier))) {
                    $modifierInstance = new $modifierClass();

                    $modification->modifier_id = $modifier->{$modifierInstance->getKeyName()};
                    $modification->modifier_type = $modifierClass;
                }

                $modification->save();

                $item->modifications()->save($modification);

                return false;
            }

            unset($item->forcedApprovalUpdate);

            return true;
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

    /**
    * Returns the model that should be used as the modifier of the modified model.
    *
    * @return mixed
    */
    protected function modifier()
    {
        return auth()->user();
    }

    /**
    * Apply modification to model.
    *
    * @return void
    */
    public function applyModificationChanges(\Approval\Models\Modification $modification, bool $approved)
    {
        if ($approved && $this->updateWhenApproved) {
            $this->forcedApprovalUpdate = true;

            foreach ($modification->modifications as $key => $mod) {
                $this->{$key} = $mod['modified'];
            }

            $this->save();

            if ($this->deleteWhenApproved) {
                $modification->delete();
            } else {
                $modification->active = false;
                $modification->save();
            }
        } elseif ($approved == false) {
            if ($this->deleteWhenDispproved) {
                $modification->delete();
            } else {
                $modification->active = false;
                $modification->save();
            }
        }
    }
}

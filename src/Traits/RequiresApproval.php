<?php

namespace Approval\Traits;

trait RequiresApproval
{
    /**
     * Number of approvers this model requires in order
     * to mark the modifications as accepted.
     *
     * @var int
     */
    protected $approversRequired = 1;

    /**
     * Number of disapprovers this model requires in order
     * to mark the modifications as rejected.
     *
     * @var int
     */
    protected $disapproversRequired = 1;

    /**
     * Boolean to mark whether or not this model should be updated
     * automatically upon receiving the required number of approvals.
     *
     * @var bool
     */
    protected $updateWhenApproved = true;

    /**
     * Boolean to mark whether or not the approval model should be deleted
     * automatically when the approval is disapproved wtih the required number
     * of disapprovals.
     *
     * @var bool
     */
    protected $deleteWhenDisapproved = false;

    /**
     * Boolean to mark whether or not the approval model should be deleted
     * automatically when the approval is approved wtih the required number
     * of approvals.
     *
     * @var bool
     */
    protected $deleteWhenApproved = true;

    /**
     * Boolean to mark whether or not the approval model should be saved
     * forcefully.
     *
     * @var bool
     */
    private $forcedApprovalUpdate = false;

    /**
     * Boot the RequiresApproval trait. Listen for events and perform logic.
     */
    public static function bootRequiresApproval()
    {
        static::updating(function ($item) {
            if (!$item->isForcedApprovalUpdate() && $item->requiresApprovalWhen($item->getDirty()) === true) {
                $diff = collect($item->getDirty())
                        ->transform(function ($change, $key) use ($item) {
                            return [
                              'original' => $item->getOriginal($key),
                              'modified' => $item->$key,
                            ];
                        })
                        ->all();

                $hasModificationPending = $item->modifications()
                                               ->activeOnly()
                                               ->where('md5', md5(json_encode($diff)))
                                               ->first();

                $modifier = $item->modifier();

                $modification = $hasModificationPending ?? new \Approval\Models\Modification();
                $modification->active = true;
                $modification->modifications = $diff;
                $modification->approvers_required = $item->approversRequired;
                $modification->disapprovers_required = $item->disapproversRequired;
                $modification->md5 = md5(json_encode($diff));

                if ($modifier && ($modifierClass = get_class($modifier))) {
                    $modifierInstance = new $modifierClass();

                    $modification->modifier_id = $modifier->{$modifierInstance->getKeyName()};
                    $modification->modifier_type = $modifierClass;
                }

                $modification->save();

                if (!$hasModificationPending) {
                    $item->modifications()->save($modification);
                }

                $item->setForcedApprovalUpdate(false);

                return false;
            }

            return true;
        });
    }

    /**
     * Function that defines the rule of when an approval process
     * should be actioned for this model.
     *
     * @param array $modifications
     *
     * @return bool
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
            $this->setForcedApprovalUpdate(true);

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
        } elseif ($approved === false) {
            if ($this->deleteWhenDisapproved) {
                $modification->delete();
            } else {
                $modification->active = false;
                $modification->save();
            }
        }
    }

    /**
     * Returns true if the model is being force updated.
     *
     * @return bool
     */
    public function isForcedApprovalUpdate()
    {
        return $this->forcedApprovalUpdate;
    }

    /**
     * Setter for forcedApprovalUpdate.
     *
     * @return bool
     */
    public function setForcedApprovalUpdate($forced = true)
    {
        return $this->forcedApprovalUpdate = $forced;
    }

    /**
     * Abstract save.
     *
     * @return mixed
     */
    abstract public function save();
}

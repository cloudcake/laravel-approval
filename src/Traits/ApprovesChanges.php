<?php

namespace Approval\Traits;

trait ApprovesChanges
{
    /**
    * Defines if this model is allowed to cast their approval
    * should be actioned for this model.
    *
    * @param \Approval\Models\Modification $modification
    * @return boolean
    */
    protected function authorizedToApprove(\Approval\Models\Modification $modification) : bool
    {
        return true;
    }

    /**
    * Defines if this model is allowed to cast their disapproval
    * should be actioned for this model.
    *
    * @param \Approval\Models\Modification $modification
    * @return boolean
    */
    protected function authorizedToDisapprove(\Approval\Models\Modification $modification) : bool
    {
        return true;
    }

    /**
    * Approve a modification.
    *
    * @param \Approval\Models\Modification $modification
    * @return bool
    */
    public function approve(\Approval\Models\Modification $modification) : bool
    {
        if ($this->authorizedToApprove($modification)) {

          // Prevent disapproving and approving
            if ($disapproval = $this->disapprovals()->where([
                'disapprover_id'   => $this->{$this->primaryKey},
                'disapprover_type' => get_class(),
                'modification_id'  => $modification->id
            ])->first()) {
                $disapproval->delete();
            }

            // Prevent duplicates
            \Approval\Models\Approval::firstOrCreate([
                'approver_id'     => $this->{$this->primaryKey},
                'approver_type'   => get_class(),
                'modification_id' => $modification->id
            ]);

            return true;
        }

        return false;
    }

    /**
    * Disapprove a modification.
    *
    * @param \Approval\Models\Modification $modification
    * @return bool
    */
    public function disapprove(\Approval\Models\Modification $modification) : bool
    {
        if ($this->authorizedToDisapprove($modification)) {

            // Prevent approving and disapproving
            if ($approval = $this->approvals()->where([
                'approver_id'     => $this->{$this->primaryKey},
                'approver_type'   => get_class(),
                'modification_id' => $modification->id
            ])->first()) {
                $approval->delete();
            }

            // Prevent duplicates
            \Approval\Models\Disapproval::firstOrCreate([
                'disapprover_id'   => $this->{$this->primaryKey},
                'disapprover_type' => get_class(),
                'modification_id'  => $modification->id
            ]);

            return true;
        }

        return false;
    }

    /**
    * Return Approval relations via moprhMany.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphMany
    */
    public function approvals()
    {
        return $this->morphMany(\Approval\Models\Approval::class, 'approver');
    }

    /**
    * Return Disapproval relations via moprhMany.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphMany
    */
    public function disapprovals()
    {
        return $this->morphMany(\Approval\Models\Disapproval::class, 'disapprover');
    }
}

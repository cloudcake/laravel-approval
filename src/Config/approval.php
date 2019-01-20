<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Approvers Required
    |--------------------------------------------------------------------------
    |
    | This value is the default number of approvers required to approve an
    | approval in order for it to close with approved status.
    |
    | Setting the approvalsRequired var on the model will override this value.
    |
    */

    'approversRequired' => 2,

    /*
    |--------------------------------------------------------------------------
    | Automatically Update
    |--------------------------------------------------------------------------
    |
    | Set to true to automatically update the model with the approved changes
    | when the approval is approved by the required number of approvals.
    |
    | Setting the updateWhenApproved var on the model will override this value.
    |
    */

    'updateWhenApproved' => true,


    /*
    |--------------------------------------------------------------------------
    | Delete Disapproved Approvals
    |--------------------------------------------------------------------------
    |
    | Set to true to automatically delete the approval when the approval is
    | disapproved by the required number of approvals.
    |
    | Setting the deleteWhenDisapproved var on the model will override this value.
    |
    */

    'deleteWhenDisapproved'  => true,

    /*
    |--------------------------------------------------------------------------
    | Delete Approved Approvals
    |--------------------------------------------------------------------------
    |
    | Set to true to automatically delete the approval when the approval is
    | approved and the model changes are made.
    |
    | Setting the deleteWhenApproved var on the model will override this value.
    |
    */

    'deleteWhenApproved'  => false,

];

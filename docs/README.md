<h6 align="center">
    <img src="https://raw.githubusercontent.com/stephenlake/laravel-approval/master/docs/assets/laravel-approval.png" width="450"/>
</h6>

<h6 align="center">
    Attach modification approvals to any model to prevent unauthorised updates.
</h6>

# Getting Started

## Install the package via composer

```bash
composer require stephenlake/laravel-approval
```

## Register the service provider

This package makes use of Laravel's auto-discovery of service providers. If you are an using earlier version of Laravel (&lt; 5.4) you will need to manually register the service provider.

Add `Approval\ApprovalServiceProvider::class` to the `providers` array in `config/app.php`.

That's it. See the usage section for examples.

# Usage

## Setup approval model(s)
Any model you wish to attach to an approval process simply requires the `RequiresApproval` trait, for example:

```php
use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use RequiresApproval;
}
```
Once added, by default any updates made to the model will have to be approved by at least 1 approver for the modifications to be actioned.

### Conditional Approvals
There may be instances where you don't always want your model to go through an approval process, for this reason the the `requiresApprovalWhen` is available for your convenience:

```php
/**
* Function that defines the rule of when an approval process
* should be actioned for this model.
*
* @param array $modifications
*
* @return boolean
*/
protected function requiresApprovalWhen(array $modifications) : bool
{
    // Handle some logic that determines if this change requires approval
    return true;
}
```

### Optional Attributes
Approval models come with a few optional attributes to make your approval process as flexible as possible. The following attributes are define by default with the set defaults, you may alter them per model as you please.

```php
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
```

#### Approvers Required
Adding the `$approversRequired` variable to your model will set the total number of approvals required before a modification is marked as accepted and no longer accepting approvals and disapprovals.

```php
use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use RequiresApproval;

    // 5 unique approvals will be required. Defaults to 1.
    protected $approvalsRequired = 5;
}
```

#### Disapprovers Required
Adding the `$disapproversRequired` variable to your model will set the total number of disapprovals required before a modification is marked as denied and no longer accepting approvals and disapprovals.

```php
use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use RequiresApproval;

    // 5 unique disapprovals will be required. Defaults to 1.
    protected $disapprovalsRequired = 5;
}
```

#### Update When Approved
Setting the `$updateWhenApproved` value to true will cause the package to automatically merge modifications into the approving model once the configured `$approvalsRequired` numer is reached.

```php
use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use RequiresApproval;

    // Update the Post model as soon as the modification has been approved.
    protected $updateWhenApproved = true;
}
```

#### Delete When Disapproved
Setting the `$deleteWhenDisapproved` value to true will cause approval modification to be deleted as soon as the `$disapprovalsRequired` number is reached.

```php
use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use RequiresApproval;

    // Delete the Post's modification model when disapproved. Default: true
    protected $deleteWhenDisapproved = true;
}
```


#### Delete When Approved
Setting the `$deleteWhenDisapproved` value to true will cause approval modification to be deleted as soon as the `$disapprovalsRequired` number is reached.

```php
use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use RequiresApproval;

    // Delete the Post's modification model when approved. Default: false
    protected $deleteWhenApproved = true;
}
```

## Setup approver model(s)
Any other model (not just a user model) can approve models by simply adding the `ApprovesChanges` trait to it, for example:

```php
use Approval\Traits\ApprovesChanges;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use ApprovesChanges;
}
```
Any model with the `ApprovesChanges` trait inherits the approval access function.

### Approver Authorization (Optional)
By default, any model with the `ApprovesChanges` trait will be able to approve and disapprove modifications. You can customize your authorization to approve/disapprove modifications however you please by adding the `authorizedToApprove` method on the specific approver model:


```php
use Approval\Traits\ApprovesChanges;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use ApprovesChanges;

    protected function authorizedToApprove(\Approval\Models\Modification $mod) : bool
    {
        // Return true to authorize approval, false to deny
        return true;
    }
}
```


### Disapprover Authorization (Optional)
Similarly to the approval process, the disapproval authorization method for disapproving modifications follows the same logic:

```php
use Approval\Traits\ApprovesChanges;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use ApprovesChanges;

    protected function authorizedToApprove(\Approval\Models\Modification $mod) : bool
    {
        // Return true to authorize approval, false to deny
        return true;
    }

    protected function authorizedToDispprove(\Approval\Models\Modification $mod) : bool
    {
        // Return true to authorize disapproval, false to deny
        return true;
    }
}
```

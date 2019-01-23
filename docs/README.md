<h6 align="center">
    <img src="https://raw.githubusercontent.com/stephenlake/laravel-approval/master/docs/assets/laravel-approval.png" width="450"/>
</h6>

<h6 align="center">
    Attach modification approvals to any model to prevent unauthorised updates.
</h6>

# Getting Started

## Install the package via composer.

```bash
composer require stephenlake/laravel-approval
```

## Register the service provider.

This package makes use of Laravel's auto-discovery of service providers. If you are an using earlier version of Laravel (&lt; 5.4) you will need to manually register the service provider.

Add `Approval\ApprovalServiceProvider::class` to the `providers` array in `config/app.php`.

That's it. See the usage section for examples.

# Usage

WORK IN PROGRESS!

## Setup approval model(s)
Any model you wish to attach to an approval process simply requires the `RequiresApproval` trait, for example:

```php
<?php

use Approval\Traits\RequiresApproval;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use RequiresApproval;
}
```
Once added, by default any updates made to the model will have to be approved by at least 1 approver for the modifications to be actioned.

## Setup approver model(s)
Any other model (not just a user model) can approve models by simply adding the `ApprovesChanges` trait to it, for example:

```php
<?php

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
<?php

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
<?php

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

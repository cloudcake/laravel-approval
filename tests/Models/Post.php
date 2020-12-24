<?php

namespace Approval\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Approval\Traits\RequiresApproval;

class Post extends Model
{
  use RequiresApproval;

  protected $guarded = [];
  /**
   * Function that defines the rule of when an approval process
   * should be actioned for this model.
   *
   * @param array $modifications
   *
   * @return bool
   */
  protected function requiresApprovalWhen($modifications): bool
  {
    return $modifications['title'] == 'Trigger Approval';
  }
}

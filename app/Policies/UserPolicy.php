<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use BasePolicy;
    use HandlesAuthorization;
}

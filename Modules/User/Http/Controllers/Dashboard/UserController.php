<?php

namespace Modules\User\Http\Controllers\Dashboard;

use Illuminate\Routing\Controller;
use Modules\Core\Traits\Dashboard\CrudDashboardController;
use Modules\Package\Entities\Package;
use Modules\User\Entities\User;

class UserController extends Controller
{
    use CrudDashboardController;

}

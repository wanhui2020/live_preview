<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:SystemUser');
    }


}

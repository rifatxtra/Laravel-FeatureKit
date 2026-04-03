<?php

namespace App\Core;

use Illuminate\Routing\Controller;
use App\Core\Traits\ApiResponseTrait;

abstract class BaseController extends Controller
{
    use ApiResponseTrait;
}
<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventType;
use App\Models\PageBanner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function home()
    {

        return view('admin.auth.login');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\FrontendUser;
use App\Models\Offer;
use App\Models\Property;
use App\Models\Partner;
use App\Models\Rate;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeemployees = Employee::where(['status' => 1])->get();
        $activeEmployeeCount = count($activeemployees);

        $inactiveemployees = Employee::where(['status' => 0])->get();
        $inactiveEmployeeCount = count($inactiveemployees);


        return Inertia::render('Dashboard/Index', ['activeEmployeeCount'=> $activeEmployeeCount, 'inactiveEmployeeCount' => $inactiveEmployeeCount]);
    }
}

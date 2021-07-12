<?php

namespace App\Http\Controllers\DashboardAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    /** Show dash board page
     * @param no
     */
    public function dashboard(){
        return view('dashboard-admin.index');
    }
}

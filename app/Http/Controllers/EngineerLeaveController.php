<?php

namespace App\Http\Controllers;

use App\Models\EngineerLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EngineerLeaveController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now()->toDateString();
        $engineerLeaves = EngineerLeave::where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->get();

        return response()->json($engineerLeaves);
    }
}

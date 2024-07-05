<?php

namespace App\Http\Controllers;

use App\Models\DailyRecord;

class DailyRecordController extends Controller
{
    public function index()
    {
        $dailyRecords = DailyRecord::orderBy('date', 'desc')->get();
        return view('dailyrecords.index', compact('dailyRecords'));
    }
}

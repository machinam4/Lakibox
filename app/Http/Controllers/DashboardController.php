<?php

namespace App\Http\Controllers;

use App\Models\Deposits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $dailyTotals =  $dailyTotals = Deposits::select(
            DB::raw("(sum(TransAmount)) as TransAmount"),
            DB::raw("(DATE_FORMAT(TransTime, '%d-%M-%Y')) as TransTime")
        )->groupBy(DB::raw("DATE_FORMAT(TransTime, '%d-%M-%Y')"))->get();
        $totalToday = Deposits::whereDate('TransTime', date('Y-m-d'))->sum('TransAmount');

        return view('dashboard', ['dailyTotals' => $dailyTotals, 'totalToday' => $totalToday]);
    }

    public function players()
    {
        // return view('session');
        $last_index = Deposits::latest()->first();
        $players = Deposits::whereDate('TransTime', date('Y-m-d'))->where('ResultCode', 0)->with('player')->limit(20)->get();
        return view('players', ['last_index' => $last_index->id, "players" => $players]);
    }

    public function online($index)
    {
        $data = [
            'new_players' => Deposits::where('id', '>', $index)->where('ResultCode', 0)->with('player')->get(),
            'totalAmount' => Deposits::whereDate('TransTime', date('Y-m-d'))->sum('TransAmount'),
        ];
        return $data;
    }
}

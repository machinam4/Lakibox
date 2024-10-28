<?php

namespace App\Http\Controllers;

use App\Models\B2CResponse;
use App\Models\Deposits;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $dailyTotals = $dailyTotals = Deposits::select(
                DB::raw('(sum(TransAmount)) as TransAmount'),
                DB::raw("(DATE_FORMAT(TransTime, '%d-%M-%Y')) as TransTime")
            )->groupBy(DB::raw("DATE_FORMAT(TransTime, '%d-%M-%Y')"))->get();
            $totalToday = Deposits::whereDate('TransTime', date('Y-m-d'))->sum('TransAmount');
            $totalWinnings = B2CResponse::whereDate('created_at', date('Y-m-d'))->sum('transaction_amount');
        } else {
            $dailyTotals = $dailyTotals = Deposits::select(
                DB::raw('(sum(TransAmount)) as TransAmount'),
                DB::raw("(DATE_FORMAT(TransTime, '%d-%M-%Y')) as TransTime")
            )->groupBy(DB::raw("DATE_FORMAT(TransTime, '%d-%M-%Y')"))->where('SmsShortcode', Auth::user()->role)->get();
            $totalToday = Deposits::whereDate('TransTime', date('Y-m-d'))->where('SmsShortcode', Auth::user()->role)->sum('TransAmount');
            $totalWinnings = B2CResponse::whereDate('created_at', date('Y-m-d'))->where('SmsShortcode', Auth::user()->role)->sum('transaction_amount');
        }

        return view('dashboard', ['dailyTotals' => $dailyTotals, 'totalToday' => $totalToday, 'totalWinnings' => $totalWinnings]);
    }

    public function players()
    {
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            // return view('session');
            $last_index = Deposits::latest()->first();
            $players = Deposits::whereDate('TransTime', date('Y-m-d'))->where('ResultCode', 0)->with('player')->orderBy('TransTime', 'DESC')->limit(20)->get();

            $totalWinnings = B2CResponse::whereDate('created_at', date('Y-m-d'))->sum('transaction_amount');
        } else {
            $last_index = Deposits::latest()->first();
            $players = Deposits::whereDate('TransTime', date('Y-m-d'))->where('ResultCode', 0)->where('SmsShortcode', Auth::user()->role)->with('player')->orderBy('TransTime', 'DESC')->limit(20)->get();

            $totalWinnings = B2CResponse::whereDate('created_at', date('Y-m-d'))->where('SmsShortcode', Auth::user()->role)->sum('transaction_amount');
        }

        return view('players', ['last_index' => $last_index->id, 'players' => $players, 'totalWinnings' => $totalWinnings]);
    }

    public function online($index)
    {
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $data = [
                'new_players' => Deposits::where('id', '>', $index)->where('ResultCode', 0)->with('player')->get(),
                'totalAmount' => Deposits::whereDate('TransTime', date('Y-m-d'))->sum('TransAmount'),
            ];
        } else {
            $data = [
                'new_players' => Deposits::where('id', '>', $index)->where('ResultCode', 0)->where('SmsShortcode', Auth::user()->role)->with('player')->get(),
                'totalAmount' => Deposits::whereDate('TransTime', date('Y-m-d'))->where('SmsShortcode', Auth::user()->role)->sum('TransAmount'),
            ];
        }

        return $data;
    }

    public function winners()
    {
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $totalWinnings = B2CResponse::whereDate('created_at', date('Y-m-d'))->sum('transaction_amount');
            $winners = B2CResponse::whereDate('created_at', date('Y-m-d'))->where('result_code', 0)->orderBy('id', 'DESC')->get();
        } else {
            $totalWinnings = B2CResponse::whereDate('created_at', date('Y-m-d'))->where('SmsShortcode', Auth::user()->role)->sum('transaction_amount');
            $winners = B2CResponse::whereDate('created_at', date('Y-m-d'))->where('result_code', 0)->where('SmsShortcode', Auth::user()->role)->orderBy('id', 'DESC')->get();
        }

        return view('winners', ['totalWinnings' => $totalWinnings, 'winners' => $winners]);
    }

    public function radios()
    {
        $radios = User::where('role', '!=', 'Admin')->get();

        return view('radios', ['radios' => $radios]);
    }

    public function create_radio(Request $request)
    {
        $radios = User::create([
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('radios');
    }

    public function filter(Request $request)
    {
        $from_date = Carbon::parse($request->from_date);
        $to_date = Carbon::parse($request->to_date);
        $role = Auth::user()->role;

        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $role = $request->role;
        }

        $dailyTotals = $dailyTotals = Deposits::select(
            DB::raw('(sum(TransAmount)) as TransAmount'),
            DB::raw("(DATE_FORMAT(TransTime, '%d-%M-%Y')) as TransTime")
        )->groupBy(DB::raw("DATE_FORMAT(TransTime, '%d-%M-%Y')"))->where('SmsShortcode', $role)->get();
        $totalToday = Deposits::where('TransTime', '>=', $from_date)->where('TransTime', '<=', $to_date)->where('SmsShortcode', $role)->sum('TransAmount');
        $totalWinnings = B2CResponse::where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)->where('SmsShortcode', $role)->sum('transaction_amount');
        $players = Deposits::where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)->where('ResultCode', 0)->where('SmsShortcode', $role)->with('player')->orderBy('TransTime', 'DESC')->limit(20)->get();

        return view('filter', ['players' => $players, 'totalToday' => $totalToday, 'totalWinnings' => $totalWinnings, 'fromDate' => $from_date,
            'toDate' => $to_date, ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\B2CResponse;
use App\Models\Deposits;
use App\Models\User;
use Illuminate\Http\Request;
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
}

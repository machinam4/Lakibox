<?php

namespace App\Http\Controllers;

use App\Models\B2CResponse;
use App\Models\B2CWallet;
use App\Models\Deposits;
use App\Models\MobileIncoming;
use App\Models\MobileOutgoing;
use App\Models\PaybillWallet;
use App\Models\Platforms;
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
            $players = Deposits::whereDate('TransTime', date('Y-m-d'))->where('ResultCode', 0)->with(['player', 'platform'])->orderBy('TransTime', 'DESC')->limit(20)->get();

            $totalWinnings = B2CResponse::whereDate('created_at', date('Y-m-d'))->sum('transaction_amount');
        } else {
            $last_index = Deposits::latest()->first();
            $players = Deposits::whereDate('TransTime', date('Y-m-d'))->where('ResultCode', 0)->where('SmsShortcode', Auth::user()->role)->with(['player', 'platform'])->orderBy('TransTime', 'DESC')->limit(20)->get();

            $totalWinnings = B2CResponse::whereDate('created_at', date('Y-m-d'))->where('SmsShortcode', Auth::user()->role)->sum('transaction_amount');
        }

        $platforms = Platforms::all();

        return view('players', ['last_index' => $last_index->id, 'players' => $players, 'totalWinnings' => $totalWinnings, 'platforms' => $platforms]);
    }

    public function online($index)
    {
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $data = [
                'new_players' => Deposits::where('id', '>', $index)->where('ResultCode', 0)->with(['player', 'platform'])->get(),
                'totalAmount' => Deposits::whereDate('TransTime', date('Y-m-d'))->sum('TransAmount'),
                'totalWinnings' => B2CResponse::whereDate('created_at', date('Y-m-d'))->sum('transaction_amount'),
            ];
        } else {
            $data = [
                'new_players' => Deposits::where('id', '>', $index)->where('ResultCode', 0)->where('SmsShortcode', Auth::user()->role)->with(['player', 'platform'])->get(),
                'totalAmount' => Deposits::whereDate('TransTime', date('Y-m-d'))->where('SmsShortcode', Auth::user()->role)->sum('TransAmount'),
                'totalWinnings' => B2CResponse::whereDate('created_at', date('Y-m-d'))->where('SmsShortcode', Auth::user()->role)->sum('transaction_amount'),
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
        $platforms = Platforms::all();

        return view('radios', ['radios' => $radios, 'platforms' => $platforms]);
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

        // Check if the user is an Admin or Developer, allow them to specify the role
        if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Developer') {
            $role = $request->role;
        }

        // Modify queries based on the role
        $totalTodayQuery = Deposits::where('TransTime', '>=', $from_date)->where('TransTime', '<=', $to_date);

        $totalWinningsQuery = B2CResponse::where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date);

        $playersQuery = Deposits::where('created_at', '>=', $from_date)->where('created_at', '<=', $to_date)
            ->where('ResultCode', 0)
            ->with(['player', 'platform'])
            ->orderBy('TransTime', 'DESC')
            ->limit(20);

        // Apply SmsShortcode filter if the role is not 'All'
        if ($role !== 'ALL') {
            $totalTodayQuery->where('SmsShortcode', $role);
            $totalWinningsQuery->where('SmsShortcode', $role);
            $playersQuery->where('SmsShortcode', $role);
        }

        // Execute the queries
        $totalToday = $totalTodayQuery->sum('TransAmount');
        $totalWinnings = $totalWinningsQuery->sum('transaction_amount');
        $players = $playersQuery->get();

        return view('filter', [
            'players' => $players,
            'totalToday' => $totalToday,
            'totalWinnings' => $totalWinnings,
            'fromDate' => $from_date,
            'toDate' => $to_date,
        ]);
    }

    public function paybills()
    {
        $paybills = PaybillWallet::all();

        return view('paybills', ['paybills' => $paybills]);
    }

    public function create_paybill(Request $request)
    {

        $data = [
            'name' => $request->orgname,
            'shortcode' => $request->shortcode,
            'initiator' => $request->initiator,
            'SecurityCredential' => $request->SecurityCredential,
            'key' => $request->key,
            'secret' => $request->secret,
            'passkey' => $request->passkey,
        ];
        $paybills = PaybillWallet::create($data);

        return redirect()->route('paybills');
    }

    public function b2cs()
    {
        $b2cs = B2CWallet::all();

        return view('b2cs', ['b2cs' => $b2cs]);
    }

    public function create_b2c(Request $request)
    {

        $data = [
            'name' => $request->orgname,
            'shortcode' => $request->shortcode,
            'initiator' => $request->initiator,
            'SecurityCredential' => $request->SecurityCredential,
            'key' => $request->key,
            'secret' => $request->secret,
            'passkey' => $request->passkey,
        ];
        $b2cs = B2CWallet::create($data);

        return redirect()->route('b2cs');
    }

    public function incomings()
    {
        $incomings = MobileIncoming::all();

        return view('incomings', ['incomings' => $incomings]);
    }

    public function create_incoming(Request $request)
    {

        $data = [
            'csp' => $request->csp,
            'type' => $request->type,
            'shortcode' => $request->shortcode,
            'api_pass' => $request->api_pass,
            'api_user' => $request->api_user,
            'api_url' => $request->api_url,
            'api_key' => $request->api_key,
        ];
        $b2cs = MobileIncoming::create($data);

        return redirect()->route('incomings');
    }

    public function outgoings()
    {
        $outgoings = MobileOutgoing::all();

        return view('outgoings', ['outgoings' => $outgoings]);
    }

    public function create_outgoing(Request $request)
    {

        $data = [
            'csp' => $request->csp,
            'type' => $request->type,
            'shortcode' => $request->shortcode,
            'api_pass' => $request->api_pass,
            'api_user' => $request->api_user,
            'api_url' => $request->api_url,
            'api_key' => $request->api_key,
        ];
        $b2cs = MobileOutgoing::create($data);

        return redirect()->route('outgoings');
    }

    public function platforms()
    {
        $platforms = Platforms::all();
        $paybills = PaybillWallet::all();
        $b2cs = B2CWallet::all();
        $senders = MobileOutgoing::all();
        $incomings = MobileIncoming::all();

        return view('platforms', [
            'platforms' => $platforms,
            'paybills' => $paybills,
            'b2cs' => $b2cs,
            'senders' => $senders,
            'incomings' => $incomings
        ]);
    }

    public function create_platform(Request $request)
    {

        $data = [
            'platform' => $request->platform,
            'mobile_incoming' => $request->mobile_incoming,
            'mobile_outgoing' => $request->mobile_outgoing,
            'b2c_wallet' => $request->b2c_wallet,
            'paybill_wallet' => $request->paybill_wallet,
            'bet_minimum' => $request->bet_minimum,
            'bet_maximum' => $request->bet_maximum,
            'win_ratio' => $request->win_ratio,
            'win_maximum' => $request->win_maximum,
            'win_minimum' => $request->win_minimum,
        ];
        $b2cs = Platforms::create($data);

        return redirect()->route('platforms');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ConversionLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard(){
        $users = User::all();
        $most_api_requests = ConversionLog::select('user_id', DB::raw('count(*) as total'))->groupBy('user_id')->orderBy('total', 'DESC')->get();
        $most_conversions = Transaction::select('sender_id', DB::raw('sum(sent_amount) as total'), DB::raw('sum(received_amount) as converted_total'))->groupBy('sender_id')->orderBy('total', 'DESC')->get();
        $highest_conversions = Transaction::select('sender_id', DB::raw('max(sent_amount) as max_amount'))->groupBy('sender_id')->orderBy('max_amount', 'DESC')->get();        
        $latest_transactions = Transaction::OrderBy('id', 'DESC')->limit(5)->get();
        return view('web.dashboard', compact('users', 'most_api_requests', 'most_conversions', 'highest_conversions', 'latest_transactions'));
    }
}

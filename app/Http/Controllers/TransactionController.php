<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Helpers;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index(){     
        // dd(Helpers::getCurrentRate('USD', 'EUR'));
        dd(Helpers::getCurrentRate('EUR', 'USD'));
    }
}

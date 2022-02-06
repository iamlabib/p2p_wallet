<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Helpers;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index(){     
        dd(Helpers::getCurrentRate('EUR', 'USD'));
    }
    
    public function send(Request $request){             

        // validating request 
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required',
            'sent_amount' => 'required'
        ]);
        if($validator->fails()){
           
            return response()->json($validator->errors()->toJson(), 400);
        }   

        // confirming sender and receiver info 
        $sender = auth()->user();        
        $receiver = User::find($request->receiver_id);
        if(!$receiver){
            return response()->json('Invalid receiver', 400);
        }  

        // getting the conversion details 
        $condersion_details = Helpers::getCurrentRate($sender->base_currency, $receiver->base_currency);

        // setting the values 
        $storeData['sender_id'] = $sender->id;        
        $storeData['sent_currency'] = $sender->base_currency;
        $storeData['received_currency'] = $receiver->base_currency;
        if($condersion_details['success']){            
            $storeData['convertion_rate'] = $condersion_details['rate'];
            $storeData['received_amount'] = $condersion_details['rate'] * $request->sent_amount;
            $storeData['stauts'] = 'sent';
            $storeData['converted_from'] = $condersion_details['data_source_url'];
            $storeData['convertion_response'] = $condersion_details['response_collection'];
        } else {
            $storeData['converted_from'] = '';
            $storeData['convertion_response'] = '';
            $storeData['convertion_rate'] = null;
            $storeData['received_amount'] = 0;
            $storeData['stauts'] = 'failed';
        }
                
        dd(array_merge(
            $validator->validated(),
            $storeData,
        ));
        $user = Transaction::create(array_merge(
            $validator->validated(),
            $storeData,
        ));
    }
}

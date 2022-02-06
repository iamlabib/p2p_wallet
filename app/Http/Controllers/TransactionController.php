<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Helpers;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:api');
    }

    public function getRate($from, $to){   
        $response = Helpers::getCurrentRate($from, $to);
        if($response['success']){
            return response()->json(array(
                'success' => true,
                'message' => 'Conversion Rate',
                'data' => $response,
            ), 200);  
        } else {
            return response()->json(array(
                'success' => false,
                'message' => 'Conversion Rate',
                'data' => null,
            ), 200);  
        }
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
        $storeData['transaction_id'] = $this->randomStr(16);        
        $storeData['sender_id'] = $sender->id;        
        $storeData['sent_currency'] = $sender->base_currency;
        $storeData['received_currency'] = $receiver->base_currency;
        if($condersion_details['success']){            
            $storeData['convertion_rate'] = $condersion_details['rate'];
            $storeData['received_amount'] = $condersion_details['rate'] * $request->sent_amount;
            $storeData['stauts'] = 'sent';
            $storeData['converted_from'] = $condersion_details['data_source_url'];
            $storeData['convertion_response'] = json_encode($condersion_details['response_collection']);
        } else {
            $storeData['converted_from'] = '';
            $storeData['convertion_response'] = '';
            $storeData['convertion_rate'] = null;
            $storeData['received_amount'] = 0;
            $storeData['stauts'] = 'failed';
        }            
        $commit_transaction = false;
        DB::beginTransaction();
        try {
            Transaction::create(array_merge(
                $validator->validated(),
                $storeData,
            ));            
            DB::commit();
            $commit_transaction = true;
        } catch (\Throwable $e) {
            DB::rollback();
        }  
        if($commit_transaction == true){
            
            // updating receiver wallet balance 
            $balance = $receiver->balance + $storeData['received_amount'];
            $receiver->balance = $balance;
            $receiver->save();

            // send email notification
            $message = "You received " . $receiver->base_currency . " " . $storeData['received_amount'] . " from " . $sender->email . ". Balance ". $receiver->base_currency ." " . $balance . ". Transaction ID " . $storeData['transaction_id'] . " at " . Carbon::now()->format('M, d Y H:i:s A');
            Helpers::sendEmail($receiver->email, $message);
            
            return response()->json(array(
                'success' => true,
                'message' => 'Transaction successful',
                'data' => null,
            ), 200);
        } else {
            return response()->json(array(
                'success' => false,
                'message' => 'Transaction failed',
                'data' => null,
            ), 200);
        }
    }

    public function randomStr($length = 16) {
        $string = '';
        $is_unique = false;  
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
                
            $bytes = random_bytes($size);
                
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        while($is_unique == false){
            if(!Transaction::where('transaction_id', $string)->first()){
                $is_unique = true;
            }
        }
        return $string;
    }
}

<?php 
namespace App;

use App\Models\ConversionLog;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Helpers
{
    static public function getCurrentRate($from, $to){

        $url = 'http://api.currencylayer.com/live?access_key=8f5ffca1c972f0356b1844c21b0f57e9&format=1';

        $allowed_currency = array(
            'USD',
            'EUR'
        );
        if(!in_array($from, $allowed_currency) || !in_array($to, $allowed_currency)){
            $conversion_rate = collect(
                [
                    'timestamp' => Carbon::now()->format('M, d Y H:i:s A'),
                    'success' => false,
                    'rate' => null,
                    'data_source_url' => false,
                    'response_header' => false,
                    'response_collection' => false
                ]
            );
            return $conversion_rate;
        }        
        $response = Http::get($url);
        if($response->successful() == true){
            $response_body = $response->collect();            
            if($response_body['success'] == true){
                $all_rates = $response_body['quotes'];  
                if($from == 'USD'){
                    $rate = $all_rates[$from.$to];
                } else {
                    $rate = (1/$all_rates[$to.$from]);
                }
                $conversion_rate = collect(
                    [
                        'timestamp' => Carbon::createFromTimestamp($response_body['timestamp'])->format('M, d Y H:i:s A'),
                        'success' => $response_body['success'],
                        'rate' => $rate,
                        'data_source_url' => $url,
                        'response_header' => $response->headers(),
                        'response_collection' => $response->json(),                        
                    ]
                );                
            } else {
                $conversion_rate = collect(
                    [
                        'timestamp' => Carbon::now()->format('M, d Y H:i:s A'),
                        'success' => $response_body['success'],
                        'rate' => null,
                        'data_source_url' => $url,
                        'response_header' => $response->headers(),
                        'response_collection' => $response->json(),                        
                    ]
                );
            }
        }       
        Helpers::storeLog($conversion_rate, $from, $to);
        return $conversion_rate;
    }

    static private function storeLog($data, $from, $to){       
        DB::beginTransaction();
        try {
            $storeData = array();
            $storeData['user_id'] = auth()->user()->id;
            $storeData['sent_currency'] = $from;
            $storeData['received_currency'] = $to;
            $storeData['convertion_rate'] = $data['rate'];
            $storeData['converted_from'] = $data['data_source_url'];
            $storeData['convertion_response'] = json_encode($data['response_collection']);
            ConversionLog::firstOrCreate($storeData);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
        }           
    }

}
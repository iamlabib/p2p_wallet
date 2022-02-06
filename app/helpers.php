<?php 
namespace App;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class Helpers
{
    public static function getCurrentRate($from, $to){
        $url = 'http://api.currencylayer.com/live?access_key=8f5ffca1c972f0356b1844c21b0f57e9&format=1';
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
        return $conversion_rate;
    }


}
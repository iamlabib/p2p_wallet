<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function test_get_conversion_rate()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NDIyODIzOCwiZXhwIjoxNjQ0MjMxODM4LCJuYmYiOjE2NDQyMjgyMzgsImp0aSI6IlNBVUNEbmp6SFl6Y3Vvbk4iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Xd428bcNE-CgnAljIZ6hfylMikj9Xv6daUBCSsID7Ig';
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('get', route('conversion.rate', ['from'=> 'USD', 'to' => 'EUR']), [], $headers)->assertStatus(200);
    }
    
    public function test_send_money()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY0NDIyODIzOCwiZXhwIjoxNjQ0MjMxODM4LCJuYmYiOjE2NDQyMjgyMzgsImp0aSI6IlNBVUNEbmp6SFl6Y3Vvbk4iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Xd428bcNE-CgnAljIZ6hfylMikj9Xv6daUBCSsID7Ig';
        $headers = ['Authorization' => "Bearer $token"];

        $this->json('post', route('transaction.send'), [
            'receiver_id' => 2,
            'sent_amount' => 10,
        ], $headers)->assertStatus(200);
    }
}

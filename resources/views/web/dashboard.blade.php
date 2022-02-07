@extends('layouts.web')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="glyphicon glyphicon-user"></i> Dashboard </h2>
            </div>   
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
                </nav>
            </div>            
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Make Transaction
                </button>


                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Make Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="#" method="post" id="transactionForm">
                                <div class="mb-3">
                                    <label for="">Sender</label>
                                    <select name="sender_id" id="sender_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="mb-3">
                                    <label for="">Receiver</label>
                                    <select name="sender_id" id="receiver_id" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Amount</label>
                                    <input type="text" name="amount" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <input type="submit" name="submit" value="SEND" class="btn btn-sm btn-success">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Most conversion api calls
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Total calls</td>
                            </tr>
                            @foreach($most_api_requests as $most_api_request)
                                <tr>
                                    <td>{{ $most_api_request->user->name }}</td>
                                    <td>{{ $most_api_request->user->email }}</td>
                                    <td>{{ $most_api_request->total }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Most conversions
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Total Sent Amount</td>
                                <!-- <td>Total Converted Amount</td> -->
                            </tr>
                            @foreach($most_conversions as $most_conversion)

                                <tr>
                                    <td>{{ $most_conversion->sender->name }}</td>
                                    <td>{{ $most_conversion->sender->email }}</td>
                                    <td>{{ $most_conversion->sender->base_currency }} {{ $most_conversion->total }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Highest Amount Send by User
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Amount</td>
                            </tr>
                            @foreach($highest_conversions as $highest_conversion)
                                <tr>
                                    <td>{{ $highest_conversion->sender->name }}</td>
                                    <td>{{ $highest_conversion->sender->email }}</td>
                                    <td>{{ $highest_conversion->max_amount }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>            
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Latest Transactions
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Sender</td>
                                <td>Receiver</td>
                                <td>Sent Amount</td>
                                <td>Received Amount</td>
                                <td>Conversion Rate</td>
                                <td>Status</td>
                                <td>Date</td>
                            </tr>
                            @foreach($latest_transactions as $latest_transaction)
                                <tr>
                                    <td>{{ $latest_transaction->sender->name }}</td>
                                    <td>{{ $latest_transaction->receiver->name }}</td>
                                    <td>{{ $latest_transaction->sender->base_currency }} {{ $latest_transaction->sent_amount }}</td>
                                    <td>{{ $latest_transaction->receiver->base_currency }} {{ number_format($latest_transaction->received_amount, 4) }}</td>
                                    <td>{{ number_format($latest_transaction->convertion_rate, 4) }}</td>
                                    <td class="text-capitalize">{{ $latest_transaction->stauts }}</td>
                                    <td>{{ $latest_transaction->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>            
        </div>
    </div>
@endsection

@push('custom-js')
   
@endpush
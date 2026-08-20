@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <example-component></example-component>

            <div class="panel panel-default">
                <div class="panel-heading">
                  Bet List
                </div>

                <div class="panel-body">

                    <div class="table-responsive">
                        <table class="table table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th><small>BET ID</small></th>
                                    <th><small>USER</small></th>
                                    <th><small>TIME</small></th>
                                    <th><small>BET</small></th>
                                    <th><small>PAYOUT</small></th>
                                    <th><small>GAME</small></th>
                                    <th><small>ROLL</small></th>
                                    <th><small>PROFIT</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bets as $bet)
                                    <tr>
                                        <th scope="row">{{ $bet->id }}</th>
                                        <td>{{ $bet->user->name }}</td>
                                        <td>{{ date('H:i', strtotime($bet->created_at)) }}</td>
                                        <td>{{ number_format($bet->value, 4) }}</td>
                                        <td>{{ number_format($bet->payout, 4) }}</td>
                                        <td>< {{ number_format(99 / $bet->payout, 2) }}</td>
                                        <td>{{ $bet->roll }}</td>
                                        <td class="@if($bet->profit <= 0) red @else green @endif">{{ number_format($bet->profit, 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $bets->links() }}
                    </div>

                </div>
            </div>

        </div>
        <div class="col-md-4">
          <chat-component></chat-component>
        </div>
    </div>
</div>
@endsection

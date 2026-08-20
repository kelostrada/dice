<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Bet;

class BetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created bet in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $bet_amount = $request->input('bet_amount');
        $payout = $request->input('payout');

        $roll_under = 99 / $payout;
        $roll = rand(0, 10000) / 100;
        $win = $roll < $roll_under;

        $bet = new Bet;
        $bet->value = $bet_amount;
        $bet->payout = $payout;
        $bet->roll = $roll;
        $bet->user_id = $user->id;

        $user->balance -= $bet->value;

        if ($win) {
            $bet->profit = $bet->value * ($bet->payout - 1);
            $user->balance += $bet->value * $bet->payout;
            $request->session()->flash('success', 'Roll was successful! Profit: ' . number_format($bet->profit, 4));
        } else {
            $bet->profit = -$bet->value;
            $request->session()->flash('error', 'Roll failed. Loss: ' . number_format($bet->profit, 4));
        }

        $bet->save();
        $user->save();

        return redirect()->route('home');
    }

}

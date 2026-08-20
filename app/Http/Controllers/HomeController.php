<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Bet;
use App\Events\MessageReceived;
use JavaScript;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $bets = $user->bets()->paginate(8);
        $bet = $bets->first();

        JavaScript::put(['user' => $user, 'bet' => $bet]);

        return view('home', ['user' => $user, 'bets' => $bets]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\MessageReceived;
use App\Message;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
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
   * Show messages
   */
  public function index()
  {
    $messages = DB::table('messages')->latest()->limit(20)->get();
    $messages = ChatResource::collection($messages);
    return $messages;
  }

  /**
   * Save message
   */
  public function store(Request $request)
  {
    $user = Auth::user();
    $input = $request->input('message');

    $message = new Message;
    $message->message = $input;
    $message->user_id = $user->id;
    $message->save();

    $data = ['username' => $user->name, 'message' => $message->message, 'created_at' => $message->created_at->format('Y-m-d H:i:s')];

    event(new MessageReceived($data));

    return ['result' => 'success'];
  }
}

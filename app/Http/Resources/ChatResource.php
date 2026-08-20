<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

use App\Http\Resources\UserResource;
use App\User;

class ChatResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::find($this->user_id);
        return [
          'id' => $this->id,
          'message' => $this->message,
          'username' => $user->name,
          'created_at' => $this->created_at
        ];
    }
}

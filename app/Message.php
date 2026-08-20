<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'message'
  ];

  /**
   * Get the user that owns the bet.
   */
  public function user()
  {
      return $this->belongsTo('App\User');
  }
}

<?php

class Profile extends Eloquent {

  /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'profiles';
  
  public function user()
  {
    return $this->belongsTo('User');
  }
}

<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	
	/**
	 * Allow a user to be connected to Facebook profiles
	 */
	public function profiles()
	{
		return $this->hasMany('Profile');
	}
	
	public function permissions()
  {
    return $this->hasMany('Permission', 'userid', 'id');
  }
	
	/**
	 * Retrieve the collection of maps this user has permission to view
	 */
	public function viewableMaps()
	{
		$permittedMaps = Map::whereExists(function($query)
		{
			$query->select(DB::raw(1))
				->from('permissions')
				->whereRaw('permissions.mapid = maps.id')
				->whereRaw('permissions.userid = '.$this->id)
				->whereRaw('permissions.view = 1');
		})
		->get();
		return $permittedMaps;
	}
	
	/**
	 * Get the collection of maps this user has right to edit
	 */
	public function editableMaps()
	{
		$permittedMaps = Map::whereExists(function($query)
		{
			$query->select(DB::raw(1))
				->from('permissions')
				->whereRaw('permissions.mapid = maps.id')
				->whereRaw('permissions.userid = '.$this->id)
				->whereRaw('permissions.edit = 1');
		})
		->get();
		return $permittedMaps;
	}
	
	/**
	 * Fetch the collection of maps the user owns
	 */
	public function ownedMaps()
	{
		$permittedMaps = Map::whereExists(function($query)
		{
			$query->select(DB::raw(1))
				->from('permissions')
				->whereRaw('permissions.mapid = maps.id')
				->whereRaw('permissions.userid = '.$this->id)
				->whereRaw('permissions.owner = 1');
		})
		->get();
		return $permittedMaps;
	}

}

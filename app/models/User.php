<?php

class User extends \Eloquent {

	protected $fillable = ['username', 'password'];

	public function subscriptions() {
		return $this->hasMany('Subscription');
	}

	public function devices() {
		return $this->hasMany('Device');
	}

	public function words() {
		return $this->hasMany('Mword');
	}

	public static function validate($input) {
		$rules = array(
			'username' => 'Required',
			'balance' => 'Integer',
			'password' => 'Required'
		);

		return Validator::make($input, $rules);
		
	}

}
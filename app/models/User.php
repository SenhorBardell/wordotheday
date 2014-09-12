<?php

class User extends \Eloquent {

	protected $fillable = ['username', 'password', 'word_id', 'balance', 'device'];

	public function subscriptions() {
        return $this->belongsToMany('Category', 'subscriptions', 'user_id', 'category_id')->withTimestamps();
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
			'balance' => 'Integer'
		);

		return Validator::make($input, $rules);
		
	}

}
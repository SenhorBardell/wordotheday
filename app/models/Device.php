<?php

class Device extends \Eloquent {
	protected $fillable = ['user_id', 'device_id'];

	public function user() {
		return $this->belongsTo('User');
	}
}
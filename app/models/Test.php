<?php

class Test extends \Eloquent {
	protected $fillable = [];

	public function words() {
		return $this->hasMany('WordCard');
	}

	public function category() {
		return $this->belongsTo('Category');
	}

}
<?php

class WordCard extends \Eloquent {
	protected $fillable = ['word', 'answer', 'category_id'];

	public function tests() {
		return $this->belongsTo('Category');
	}

	public function sent() {
		return $this->hasMany('User');
	}
}
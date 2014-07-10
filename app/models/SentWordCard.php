<?php

class SentWordCard extends \Eloquent {
	protected $fillable = ['word_id', 'category_id'];

	public function word() {
		return $this->belongsToMany('WordCard');
	}
}
<?php

class Category extends \Eloquent {
	protected $fillable = ['name', 'subscription_price', 'test_price'];

	public function wordcards() {
		return $this->hasMany('WordCard');
	}

	public function update_amount() {
		$this->words = count($this->wordcards);
	}

	public function sentWordcards() {
		return $this->belongsToMany('WordCard', 'sent_word_cards', 'word_id', 'category_id');
	}

}
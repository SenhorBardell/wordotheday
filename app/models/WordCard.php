<?php

class WordCard extends \Eloquent {
	protected $fillable = ['word', 'answer', 'category_id'];
	protected $hidden = ['created_at', 'updated_at'];

	public function tests() {
		return $this->belongsTo('Category');
	}

	public function sent() {
		return $this->hasMany('User');
	}

	public static function getRandomCards($take = 20, $category = null) {
		$result = [];

		if ($category) {
			$cards = $category->wordcards;
			$count = $category->wordcards()->count();
		} else {
			$cards = WordCard::all();
			$count = WordCard::count();
		}
		while (count($result) < $take) {

			$index = rand(0, $count);
			try {
				$card = $cards[$index];
			} catch (Exception $e) {

			}

			if ($card)
				if (!array_filter($result, function ($oldCard) use ($card) {
					return $oldCard == $card;
				})
				)
					array_push($result, $card->toArray());
		}
		return $result;
	}
}
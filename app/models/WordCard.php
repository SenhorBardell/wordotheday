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

//	private function filterCards($cards, $card) {
//		return array_filter($cards, function($filterCard) use ($card) {
//			return $filterCard == $card;
//		});
//	}

	public static function getRandomCards($sentCards, $take = 20, $category = null) {
		$result = [];

		if ($category) {
			$cards = $category->wordcards;
			$count = $category->wordcards()->count();
		} else {
			$cards = WordCard::all();
			$count = WordCard::count();
		}

		$countdown = $count;

		while (count($result) < $take) {

//			if ($countdown != 0) $countdown--; else return $result;

			if ($cards->count() == 0) return $result;

			$index = mt_rand(0, $count);

			if (array_key_exists($index, $cards->toArray())) {
				$card = $cards[$index];
				unset($cards[$index]);
			}

			if (isset($card)) {

				$sentCardsExists = array_filter($sentCards, function($sentCard) use($card) {
					return $sentCard->id == $card->id;
				});

				$resultCardsExists = array_filter($result, function ($oldCard) use ($card) {
					return $oldCard['id'] == $card->id;
				});

				if (count($sentCardsExists) == 0 && count($resultCardsExists) == 0) {
					array_push($result, $card->toArray());
				}
			}
		}
		return $result;
	}
}
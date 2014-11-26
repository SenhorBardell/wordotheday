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
			$cards = $category->wordcards->toArray();
			$count = $category->wordcards()->count();
		} else {
			$cards = WordCard::all()->toArray();
			$count = WordCard::count();
		}

		$countdown = $count;
		$countdown = 500;

		while (count($result) < $take) {

			if ($countdown != 0) $countdown--; else {
				return $result;
			}

			if (empty($cards)) {
				return $result;
			}

			if (count($cards) < 2) {
				$card = array_shift($cards);
			} else {
				$index = rand(0, $count);
			}

			if (array_key_exists($index, $cards)) {
				$card = $cards[$index];
				unset($cards[$index]);
			} else {
				$card = array_shift($cards);
			}

			if (isset($card)) {

				$sentCardsExists = array_filter($sentCards, function($sentCard) use($card) {
					return $sentCard['word_id'] == $card['id'];
				});

				$resultCardsExists = array_filter($result, function ($oldCard) use ($card) {
					return $oldCard['id'] == $card['id'];
				});

				if (count($sentCardsExists) == 0 && count($resultCardsExists) == 0) {
					array_push($result, $card);
				}
//				var_dump(count($cards));
//				var_dump('=============');
			}
		}
		return $result;
	}

}
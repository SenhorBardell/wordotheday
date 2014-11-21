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
		$countdown = 50;

		while (count($result) < $take) {

			if ($countdown != 0) $countdown--; else {
//				var_dump('===========COUNTDOWN REACHED==========');
				return $result;
			}

			if (empty($cards)) {
//				var_dump('Cards is empty');
				return $result;
			}

			if (count($cards) <= 1) {
				$card = array_shift($cards);
//				var_dump('Grabbing last card');
			} else {
				$index = mt_rand(0, $count);
//				var_dump('Trying to get card from index: ' . $index);
			}

			if (array_key_exists($index, $cards)) {
				$card = $cards[$index];
//				var_dump('Card exist: '.$index.' id: '.$card['id']);
				unset($cards[$index]);
				$count = count($cards);
//				var_dump('Current count: '.$count);
			}

			if (isset($card)) {

				$sentCardsExists = array_filter($sentCards, function($sentCard) use($card) {
					return $sentCard['word_id'] == $card['id'];
				});

				$resultCardsExists = array_filter($result, function ($oldCard) use ($card) {
					return $oldCard['id'] == $card['id'];
				});

//				var_dump('Dublicate from sent - '.count($sentCardsExists));

//				var_dump($sentCardsExists);

//				var_dump('Dublicate from existing - '.count($resultCardsExists));

//				var_dump($resultCardsExists);

				if (count($sentCardsExists) == 0 && count($resultCardsExists) == 0) {
//					var_dump('Pushing');
					array_push($result, $card);
				}
//				var_dump('=============');
			}
		}
		return $result;
	}

}
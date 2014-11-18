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

			if ($countdown != 0) $countdown--; else return $result;

			$index = mt_rand(0, $count);
			try {
				$card = $cards[$index];
			} catch (Exception $e) {

			}

//			var_dump('Got Card: '.$card->id);

			if ($card) {
//				var_dump('Checking '.$card->id);
				$sentCardsExists = false;
				foreach ($sentCards as $sentCard) {
//					var_dump('Checking agains sent '.$sentCard->word_id.' '.$card->id);
					if ($card->id == $sentCard->word_id) {
						$sentCardsExists = true;
//						var_dump('Found match');
					}
				}
//				var_dump($sentCards->count());

//				var_dump('Checking agains result cards');
				$resultCardsExists = array_filter($result, function ($oldCard) use ($card) {
//					var_dump('Checking result '.$oldCard['id'].' '.$card->id);
					return $oldCard['id'] == $card->id;
				});
//				var_dump(count($resultCardsExists));
//				var_dump(count($sentCardsExists) == 0 && count($resultCardsExists) == 0);
//				var_dump('sentcards exists');
//				var_dump($sentCardsExists);
//				var_dump('resultcards '.count($resultCardsExists));

				if (!$sentCardsExists && count($resultCardsExists) == 0) {
//					var_dump('Putting into result array: '.$card->id);
					array_push($result, $card->toArray());
				}
			}
		}
		return $result;
	}
}
<?php

class TestsController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$tests = Test::all();
		return $this->respond($this->transformCollection($tests));
	}

	public function start($user_id) {
		$offset = Input::has('page') ? Input::get('page') : 0;
		$user = User::find($user_id);

		if (!$user)
			return $this->respondNotFound('User not found');

		$balance = $user->balance;

		if (Input::get('category') == 0) {

			$s = Setting::first();

			if ($balance < $s->general_cost)
				return $this->respondInsufficientPrivileges('Not enough money');

			if ($offset == 0)
				$user->balance = $user->balance - $s->general_cost;

//			$words = WordCard::take(20)->skip($offset * 20)->get()->toArray();
			$testWords = DB::table('test_word_cards')->where('category_id', 0)->where('user_id', $user->id)->get();

			$transformedTestWords = array_map(function($testWord) {
				return [
					'id' => $testWord->id,
					'user_id' => $testWord->user_id,
					'word_id' => $testWord->word_id,
					'category_id' => $testWord->category_id
				];
			}, $testWords);

			$words = WordCard::getRandomCards($transformedTestWords, 20);

			if (count($words) == 0) {
				DB::table('test_word_cards')->where('category_id', 0)->where('user_id', $user->id)->delete();
			}

			foreach ($words as $word) {
				$wordsToModel[$word['id']] = ['category_id' => 0];
			}

		} else {

			$category = Category::find(Input::get('category'));

			if (!$category)
				return $this->respondNotFound('Category not found');

			if ($category->wordcards->count() <= 20)
				return $this->respond([
					'status' => 'started',
					'balance' => $user->balance,
					'words' => []
				]);

			if ($balance < $category->test_price)
				return $this->respondInsufficientPrivileges('Not enough money');

			// Begin compiling cards

			$testWords = DB::table('test_word_cards')->where('category_id', $category->id)->where('user_id', $user->id)->get();

			$transformedTestWords = array_map(function($testWord) {
				return [
					'id' => $testWord->id,
					'user_id' => $testWord->user_id,
					'word_id' => $testWord->word_id,
					'category_id' => $testWord->category_id
				];
			}, $testWords);

			$words = WordCard::getRandomCards($transformedTestWords, 20, $category);

			// We got less than 5

			if (count($words) <= 5 && $offset == 0) {

				// remove from history
				DB::table('test_word_cards')->where('category_id', $category->id)->where('user_id', $user->id)->delete();

				$secondIterationWords = WordCard::getRandomCards([], 20 - count($words), $category);

				$words = array_merge($words, $secondIterationWords);

				$words = array_values($words);

			} elseif (count($words) == 0 && $offset != 0) {
				DB::table('test_word_cards')->where('category_id', $category->id)->where('user_id', $user->id)->delete();
			}

			foreach ($words as $word) {
				$wordsToModel[$word['id']] = ['category_id' => $category->id];
			}

			if ($offset == 0 && count($words) > 5)
				$user->balance = $user->balance - $category->test_price;
		}

		if (isset($wordsToModel)) {
			$user->testWords()->attach($wordsToModel);
		}

		$user->save();

		shuffle($words);

		$response['status'] = 'started';
		$response['count'] = count($words);
		$response['balance'] = $user->balance;
		$response['words'] = $this->transformWordsCollection($words);

		return $this->respond($response);
	}

	public function result($id) {
		$user = User::wherePassword(Input::get('auth'))->whereId($id)->first();

		if (!$user)
			return $this->respondNotFound('User not found');

		$user->balance = $user->balance + Input::get('coins');

		if (Input::has('category')) {
			$category = Input::get('category');
			DB::table('test_word_cards')->where('category_id', $category)->where('user_id', $user->id)->delete();
		}

		if ($user->save())
			return $this->respond([
				'id' => $user->id,
				'balance' => $user->balance,
				'max_result' => $user->max_result,
				'overal_standing' => $user->overal_standing
			]);

		return $this->respondServerError();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		$test = Test::find($id);
		$cats = $test->category;

		$resp = (array('test' => $test->toArray(), 'categories' => $cats));

		return $this->respond($test->toArray());
	}

	private function transformCollection($tests) {
		return array_map([$this, 'transform'], $tests->toArray());
	}

	private function transform($test) {
		return [
			'name' => $test['name'],
			'id' => $test['id']
		];
	}

	private function transformWordsCollection($words) {
		return array_map([$this, 'transformWord'], $words);
	}

	private function transformWord($wordcard) {
		return [
			'word' => $wordcard['word'],
			'answer' => $wordcard['answer'],
			'id' => $wordcard['id'],
			'category_id' => $wordcard['category_id']
		];
	}

}

<?php

class TestsController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
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

			if (Input::get('page') == '0')
				$user->balance = $user->balance - $s->general_cost;

//			$words = WordCard::take(20)->skip($offset * 20)->get()->toArray();
			$words = $this->getCards();

		} else {

			$category = Category::find(Input::get('category'));

			if (!$category)
				return $this->respondNotFound('Category not found');

			if ($balance < $category->test_price)
			return $this->respondInsufficientPrivileges('Not enough money');

//			$words = $category->wordcards()->take(20)->skip($offset * 20)->get()->toArray();
			$words = $this->getCards($category);

			if (Input::get('page') == '0' && count($words) < 5)
				$user->balance = $user->balance - $category->test_price;
		}

		$user->save();

		shuffle($words);

		$response['status'] = 'started';
		$response['balance'] = $user->balance;
		$response['words'] = $this->transformWordsCollection($words);

		return $this->respond($response);
	}

	public function result($id) {mysql://b4ccce6110afe6:35364b49@eu-cdbr-west-01.cleardb.com/heroku_928cffd4c5b526d?reconnect=true

		$user = User::wherePassword(Input::get('auth'))->whereId($id)->first();
		$user->balance = $user->balance + Input::get('coins');
		if ($user->save())
			return $this->respond([
				'id' => $user->id,
				'balance' => $user->balance,
				'max_result' => $user->max_result,
				'overal_standing' => $user->overal_standing,
			]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
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

	private function getCards($category = null, $take = 20) {
		$count = WordCard::count();
		$result = [];

		if ($category) {
			$cards = $category->wordcards;
			$count = count($cards);
		} else {
			$cards = WordCard::all();
			$count = count($cards);
		}
		while (count($result) < $take) {
			$index = mt_rand(0, $count);
			$result[] = &$cards[mt_rand(0, $index)];
		}

		return $result;
	}

}
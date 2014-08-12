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

			if (Input::has('page'))
				$user->balance = $user->balance - $s->general_cost;

			$words = WordCard::take(20)->skip($offset * 20)->get()->toArray();

		} else {

			$category = Category::find(Input::get('category'));

			if (!$category)
				return $this->respondNotFound('Category not found');

			if ($balance < $category->test_price)
			return $this->respondInsufficientPrivileges('Not enough money');

			if (Input::has('page'))
				$user->balance = $user->balance - $category->test_price;

			$words = $category->wordcards()->take(20)->skip($offset * 20)->get()->toArray();

		}

		$user->save();

		shuffle($words);

		$response['status'] = 'started';
		$response['balance'] = $user->balance;
		$response['words'] = $this->transformWordsCollection($words);

		return $this->respond($response);
	}

	public function result() {

		return $this->respond(Input::all());
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

}
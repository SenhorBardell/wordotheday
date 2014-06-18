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

	// public function start() {

	// 	$category = Category::find(Input::get('category'));
	// 	$user = User::find(Input::get('user'));

	// 	$balance = $user->balance;
	// 	$words = $category->wordcards->take(5)->toArray();

	// 	$response['status'] = 'test started';
	// 	$response['balance'] = $balance;

	// 	$all_words = WordCard::all();

	// 	foreach($words as $word) {

	// 		$j = 0;
	// 		$i = 0;
	// 		while ($i < 4) {

	// 			$rand = rand(0, 3);
	// 			$rand_global = rand(0, count($all_words) - 1);
	// 			$used_words = array();

	// 			if (!in_array($all_words[$rand_global], $used_words)) {

	// 				$shuffled_words[$rand] = $all_words[$rand_global];
	// 				array_push($used_words, $shuffled_words[$rand]);
	// 				$i++;

	// 			}	

	// 		}

	// 		$generated_words[$j] = array(
	// 			'answer' => $word['answer'],
	// 			'right_word' => $word['word'],
	// 			'words' => array_rand($used_words)
	// 		);

	// 		$j++;
	// 	}

	// 	$response['words'] = $words;

	// 	return Response::json($response);
	// }

	public function start() {
		$category = Category::find(Input::get('category'));
		$user = User::find(Input::get('user'));

		$balance = $user->balance;
		$words = $category->wordcards->take(20)->toArray();

		if ($balance > 0) {
			$response['status'] = 'started';
			$response['balance'] = $user->balance;
			$response['words'] = $this->transformWordsCollection($words);
			return $this->respond($response);
		} else {
			return $this->respondInsufficientPrivileges('Not enough money');
		}

		
	}

	public function result() {

		$user = User::find(Input::get('user'));

		$response = array('status' => 'test ended', 'balance' => $user->balance);

		return $this->respond($response);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

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
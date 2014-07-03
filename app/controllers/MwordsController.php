<?php

use Helpers\Transformers\WordTransformer;

class MwordsController extends ApiController {

	/**
	* @var Bardell\Transformers\WordCardTransformer
	*/
	protected $wordTransformer;

	function __construct(WordTransformer $wordTransformer) {
		$this->wordTransformer = $wordTransformer;
	}

	public function show_all() {
		$words = Mword::where('status', '=', 'waiting')->get();
		
		return $this->respond($this->transform_words($words));
	}

	public function show_words($user_id) {

		$validator = $this->validate_id($user_id);

		if ($validator->fails())
			return $this->respondInsufficientPrivileges($validator->messages()->all());

		$user = User::find($user_id);

		if (!$user)
			return $this->respondNotFound('User not found');

		$words = $user->words;

		if ($words)
			return $this->respond($this->wordTransformer->transformWords($words));

		return $this->respondNotFound('No words');
	}

	public function show($word_id) {
		$validator = $this->validate_id($word_id);

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$word = Mword::find($word_id);

		if ($word) {
			return $this->respond($this->transform_word($word));
		}
	}

	public function add_word($user_id) {
		$validator = Validator::make(array(
			'user_id' => $user_id,
			'word' => Input::get('word'),
			'answer' => Input::get('answer'),
			'category_id' => Input::get('category_id') 

		), array(
			'user_id' => 'numeric',
			'word' => 'alpha_spaces',
			'answer' => 'alpha_spaces',
			'category_id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$word = Mword::create(array(
			'user_id' => $user_id,
			'word' => Input::get('word'),
			'answer' => Input::get('answer'),
			'status' => 'waiting',
			'category_id' => Input::get('category_id')
		));

		if ($word) {
			return $this->respond($this->transform_word($word));
		}

		return $this->respondServerError();
	}

	public function update_word($word_id) {

	}

	public function remove_word($word_id) {
		$validator = $this->validate_id($word_id);

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$word = Mword::find($word_id);

		if ($word->delete()) {
			return $this->respondNoContent();
		}

		return $this->respondServerError();
	}

	public function accept($word_id) {
		$validator = $this->validate_id($word_id);

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$word = Mword::find($word_id);

		if (!(bool)$word) {
			return $this->respondNotFound();
		}
		
		$word->status = 'accepted';

		if ($word->save()) {
			return $this->respond($this->transform_word($word));
		}

		
		return $this->respondServerError();

	}

	public function decline($word_id) {
		$validator = $this->validate_id($word_id);

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$word = Mword::find($word_id);

		if (!(bool)$word) {
			return $this->respondNotFound();
		}
		
		$word->status = 'rejected';

		if ($word->save()) {
			return $this->respond($this->transform_word($word));
		}

		
		return $this->respondServerError();
	}

	public function change_status($word_id) {
		$validator = $this->validate_id($word_id);

		$input_validator = Validator::make(array(
			'status' => Input::get('status'),
			'category_id' => Input::get('category')
		), array(
			'status' => 'in:accepted,rejected,waiting',
			'category_id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		if ($input_validator->fails()) {
			return $this->respondInsufficientPrivileges($input_validator->messages()->all());
		}

		$word = Mword::find($word_id);

		if (!(bool)$word) {
			return $this->respondNotFound();
		}
		
		$word->status = Input::get('status');

		if (Input::get('status') == 'accepted') {
			if ($this->create_word($word, Input::get('category')) && $word->save()) {
				return $this->respond($this->transform_word($word));
			}
		}
		
		return $this->respondServerError();
	}

	public function create_word($word, $category_id) {

		$validator = Validator::make(array(
			'user_id' => $user_id,
			'word' => Input::get('word'),
			'answer' => Input::get('answer'),
			'category_id' => Input::get('category_id') 

		), array(
			'user_id' => 'numeric',
			'word' => 'alpha_spaces',
			'answer' => 'alpha_spaces',
			'category_id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}
		
		$newWord = WordCard::create(array(
			'word' => $word->word,
			'answer' => $word->answer,
			'category_id' => $category_id
		));

		$category = Category::find($category_id);
		$category->update_amount();
		$category->save();

		if ($newWord) return true;

		return false;
	}

	private function validate_id($id) {
		$validator = Validator::make(array(
			'id' => $id
		), array(
			'id' => 'numeric'
		));

		return $validator;
	}

	private function transform_words($words) {
		return array_map([$this, 'transform_word'], $words->toArray());
	}

	private function transform_word($word) {
		return [
			'id' => $word['id'],
			'user_id' => $word['user_id'],
			'word' => $word['word'],
			'answer' => $word['answer'],
			'status' => $word['status'],
			'category_id' => $word['category_id']
		];
	}

}
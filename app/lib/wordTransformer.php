<?php namespace Helpers\Transformers;

class WordTransformer extends Transformer {

	public function transformWord($word) {
		return [
			'word' => $word['word'],
			'answer' => $word['answer'],
			'id' => $word['id'],
			'category_id' => $word['category_id']
		];
	}

	public function transformCategory($category) {
		return [
			'name' => $category['name'],
			'subscription_price' => $category['subscription_price'],
			'id' => $category['id'],
			'test_price' => $category['test_price'],
			'words' => $category['words']
		];
	}

	public function transformUser($user) {
		return [
			'username' => $user['username'],
			// 'password' => $user['password'],
			'max_result' => $user['max_result'],
			'overal_standing' => $user['overal_standing'],
			'balance' => $user['balance'],
			'id' => $user['id']
		];
	}

}
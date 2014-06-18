<?php namespace Helpers\Transformers;

abstract class Transformer {
	
	public function transformWords(array $words) {
		return array_map([$this, 'transformWord'], $words);
	}

	public abstract function transformWord($word);

	public function transformCategories(array $categories) {
		return array_map([$this, 'transformCategory'], $category);
	}

	public abstract function transformCategory($category);

	public function transformUsers(array $users) {
		return array_map([$this, 'transfromUser'], $user);
	}

	public abstract function transformUser($user);

}
<?php

class CategoriesController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$cat =  Category::all();

		return $this->respond($this->transformCollection($cat));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$category = Category::create(array(
			'name' => $input['name'],
			'subscription_price' => $input['subscription_price'],
			'test_price' => $input['test_price'],
			'ua_parameter' => $input['ua_parameter']
		));

		if ($category) return $this->respond($category);
		return $this->respondServerError();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$category = Category::find($id);
		$words = Category::find($id)->wordcards;
		if ($words && $category)
			return $this->respond($this->transform($category));

		return $this->respondNotFound();
	}

	public function show_words($category_id) {
		$category = Category::find($category_id);
		$words = Category::find($category_id)->wordcards;
		if ($words && $category)
			return $this->respond($this->transformWordCollection($words));
		return $this->respondNotFound();
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
		$category = Category::find($id);

		$category->name = $input['name'];
		$category->subscription_price = $input['subscription_price'];
		$category->test_price = $input['test_price'];
		$category->ua_parameter = $input['ua_parameter'];
		$category->update_amount();
		if ($category->save()) {
			return $this->respond($this->transform($category));
		}
		return $this->respondServerError('Error updating category');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$category = Category::find($id);
		if ($category->delete()) {
			return $this->respondNoContent();
		}
		return $this->respondServerError();
	}

	private function transformCollection($categories) {
		return array_map([$this, 'transform'], $categories->toArray());
	}

	private function transform($category) {
		return [
			'name' => $category['name'],
			'subscription_price' => $category['subscription_price'],
			'id' => $category['id'],
			'test_price' => $category['test_price'],
			'words' => $category['words'],
			'ua_parameter' => $category['ua_parameter']
		];
	}

	private function transformWordCollection($wordCards) {
		return array_map([$this, 'transformWord'], $wordCards->toArray());
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
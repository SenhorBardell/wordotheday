<?php

use Helpers\Transformers\WordTransformer;

class WordCardsController extends ApiController {

	/**
	* @var Bardell\Transformers\WordCardTransformer
	*/
	protected $wordTransformer;

	function __construct(WordTransformer $wordTransformer) {
		$this->wordTransformer = $wordTransformer;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$wordcards = WordCard::all();

		return $this->respond($this->wordTransformer->transformWords($wordcards->toArray()));
	}

	public function randomwords() {

		$return = array();
		$amount = 20;

		foreach (Category::with('wordcards')->get() as $category) {
			foreach ($category->wordcards as $wordcard) {
				if (

					rand(0, 2) == 2 && 
					!in_array($wordcard, $return) &&
					count($return) < $amount

					) 
					array_push($return, $wordcard);
			}
		}

		shuffle($return);

		return $this->wordTransformer->transformWords($return);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$wordcard = WordCard::create($input);

		$category = Category::find($input['category_id']);
		$category->update_amount();
		$category->save();

		if ($wordcard) return $this->respond($wordcard);
		else return $response->respondServerError();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$wordcard = WordCard::find($id);

		if (!$wordcard) {
			return $this->respondNotFound('Word does not exist');
		}

		return $this->respond($this->transform($wordcard));
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

		$wordCard = WordCard::find($id);
		$oldCategory = $wordCard->category_id;
		$wordCard->word = $input['word'];
		$wordCard->answer = $input['answer'];
		$wordCard->category_id = $input['category_id'];

		// Update new category
		$category = Category::find($input['category_id']);
		$category->update_amount();
		$category->save();

		// Update old category
		$oldCategory = Category::find($oldCategory);
		$oldCategory->update_amount();
		$oldCategory->save();

		if ($wordCard->save()) {
			return $this->respond($this->transform($wordCard));
		}
		return $this->repondServerError('Error updating user');
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$wordCard = WordCard::find($id);
		$category = Category::find($wordCard->category_id);
		if ($wordCard->delete()) {
			$category->update_amount();
			$category->save();
			return $this->respondNoContent();
		}

		return $this->respondServerError();
	}

	private function transformCollection($wordCards) {
		return array_map([$this, 'transform'], $wordCards->toArray());
	}

	private function transform($wordcard) {
		return [
			'word' => $wordcard['word'],
			'answer' => $wordcard['answer'],
			'id' => $wordcard['id'],
			'category_id' => $wordcard['category_id']
		];
	}

}
<?php

use Helpers\Transformers\WordTransformer;

class WordCardsController extends ApiController {

	/**
	* @var Helpers\Transformers\WordCardTransformer
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
		$wordcards = WordCard::paginate();
		return $wordcards;

		return $this->respond($this->wordTransformer->transformWords($wordcards->toArray()));
	}


	/**
	 * Получение слов для словаря
	 * 
	 * @return Response
	 */
	public function sentwords() {
		$user = User::find(Input::get('user_id'));
		$lastWordID = Input::has('id_last_word') ? Input::get('id_last_word') : -1;
		$words = [];

		if (!$user)
			return $this->respondNotFound('user not found');

		function sortByArray(Array $array, Array $orderArray) {
			$ordered = [];
			foreach ($orderArray as $key => $value) {
				foreach ($array as $elem) {
					if ($elem['id'] == $value)
						$ordered[] = $elem;
				}
			}
			return $ordered;
		}

		$daywordID = Setting::first()->word_id;

		if ($lastWordID == '-1') {

			/* just registered */
			$dayWords = SentWordCard::where('category_id', 0)->take(20)->get();

		} else {

			$lastWord = SentWordCard::where('word_id', $lastWordID)->orderBy('id', 'DESC')->first();

			/* usual case */
			$dayWords = SentWordCard::where('category_id', '0')->where('id', '>', $lastWord->id)->orderBy('id', 'DESC')->take(20)->get();
			//@TODO Bug if subscriptions is more than 20

		}

		if ($lastWordID != $daywordID) {

			foreach ($dayWords as $dayWord) {
				$dayWordIds[] = $dayWord->word_id;
			}

			$tempWords = WordCard::whereIn('id', $dayWordIds)->get()->toArray();
			$properOrder = array_reverse(sortByArray($tempWords, $dayWordIds));

			foreach ($properOrder as $word) {
				$word['type'] = 0;
				$words[] = $word;
			}
			//TODO use array map
		} else {

			// Inluce day word if there is none
			$dw = WordCard::find($daywordID)->toArray();
			$dw['type'] = 0;
			$words[0] = $dw;
		}

		/* Subscriptions */
		foreach ($user->subscriptions as $subscription)
			$subs[] = $subscription->id;

		if (isset($subs)) {
			if (isset($lastWord))
				$catwords = SentWordCard::whereIn('category_id', $subs)->where('id', '>', $lastWord->id)->get();
			else
				$catwords = SentWordCard::whereIn('category_id', $subs)->get();

			if ($catwords->isEmpty())
				return [
					'words' => [],
					'id_dayword' => $daywordID
				];

			foreach ($catwords as $catword) {
				$subCatIDs[] = $catword->word_id;
			}

			$tempCatWords = WordCard::whereIn('id', $subCatIDs)->get()->toArray();

			foreach ($tempCatWords as $catword) {
				$catword['type'] = 1;
				array_push($words, $catword);
			}
		}

		$result['words'] = $words;

		$result['id_dayword'] = $daywordID;

		return $result;
	}

	/**
	 * Get random words
	 *
	 * @deprecated
	 * @return array $words
	 */
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
		else return $this->respondServerError();
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
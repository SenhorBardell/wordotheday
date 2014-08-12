<?php

class SettingsController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Res2ponse
	 */
	public function index()
	{
		$s = Setting::first();
		$word = WordCard::find($s->word_id);
		$settings = array(
			'answer_time' => $s->answer_time,
			'daily_bonus' => $s->daily_bonus,
			'general_cost' => $s->general_cost,
			'top_bonus' => $s->top_bonus,
			'word_cost' => $s->word_cost,
			'life_cost' => $s->life_cost,
			'words_for_the_next_bonus' => $s->words_for_the_next_bonus,
			'word' => array(
				'id' => $s->word_id,
				'word' => $word->word,
				'answer' => $word->answer
			),
			'quiz' => [
				'url' => $s->quiz,
				'added_on' => $s->added_on
			]
		);

		return $this->respond($settings);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$input = Input::all();
		$settings = Setting::first();
		$settings->fill($input);

		if ($settings->save()) {

			$returnedSettings = array(
				'answer_time' => $settings->answer_time,
				'daily_bonus' => $settings->daily_bonus,
				'general_cost' => $settings->general_cost,
				'top_bonus' => $settings->top_bonus,
				'word_cost' => $settings->word_cost,
				'life_cost' => $settings->life_cost,
				'words_for_the_next_bonus' => $settings->words_for_the_next_bonus,
				'word_id' => $settings->word_id
			);
			return $this->respond($returnedSettings);

		};

	}

}
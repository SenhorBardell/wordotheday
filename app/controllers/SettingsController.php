<?php

class SettingsController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$s = Setting::first();
		$settings = array(
			'answer_time' => $s->answer_time,
			'daily_bonus' => $s->daily_bonus,
			'general_cost' => $s->general_cost,
			// 'test_cost' => $s->test_cost,
			'top_bonus' => $s->top_bonus,
			'word_cost' => $s->word_cost,
			'life_cost' => $s->life_cost,
			'words_for_the_next_bonus' => $s->words_for_the_next_bonus
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
				// 'test_cost' => $settings->test_cost,
				'top_bonus' => $settings->top_bonus,
				'word_cost' => $settings->word_cost,
				'life_cost' => $settings->life_cost,
			);
			return $this->respond($returnedSettings);

		};

	}

}
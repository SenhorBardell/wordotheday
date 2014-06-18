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
			'test_cost' => $s->test_cost,
			'top_bonus' => $s->top_bonus,
			'word_cost' => $s->word_cost
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
		$settings->answer_time = $input['answer_time'];
		$settings->daily_bonus = $input['daily_bonus'];
		$settings->general_cost = $input['general_cost'];
		$settings->test_cost = $input['test_cost'];
		$settings->top_bonus = $input['top_bonus'];
		$settings->word_cost = $input['word_cost'];
		$settings->save();

		return $this->respond(array('status' => 'ok'));
	}

}
<?php

class Setting extends \Eloquent {
	protected $fillable = ['answer_time', 'daily_bonus', 'general_cost', 'life_cost', 'test_cost', 'top_bonus', 'word_cost', 'words_for_the_next_bonus', 'word_id'];
	protected $guraded = ['id'];
	public $timestamps = false;
}
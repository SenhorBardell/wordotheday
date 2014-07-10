<?php

class Setting extends \Eloquent {
	protected $fillable = ['answer_time', 'daily_bonus', 'general_cost', 'life_cost', 'test_cost', 'top_bonus', 'word_cost'];
	protected $guraded = ['id'];
	public $timestamps = false;
}
<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class SettingsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
		Setting::create([
			'answer_time' => 10,
			'daily_bonus' => 10,
			'general_cost' => 10,
			'test_cost' => 10,
			'top_bonus' => 10,
			'word_cost' => 10
		]);
	}

}
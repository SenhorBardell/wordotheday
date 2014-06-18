<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PushWords extends Command {

	public $count = 0;
	public $word = array();
	public $words = array();
	public $users = array();

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'push:words';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->base_cat();
	}

	public function base_cat() {
		$users = User::all();
		$this->users = $users;
		$category = Category::find(1);
		$words = $category->wordcards;
		$this->words = $words;
		$count = $words->count();
		$this->count = $count;
		$sent_count = SentWordCard::all()->count();
		$state = true;
		while ($state) {
			$word = $words[rand(0, $count -1)];
			$this->word = $word;

			// Reset SentWordCards data base
			if ($count == $sent_count) {
				SentWordCard::truncate();
				$this->info('Resetting word card notifications');
			}


			// Retrieve users signed categories 

			// Check queue on a category

			if (!SentWordCard::where('word_id', '=', $word['id'])->first()) {

				SentWordCard::create(array(
					'category_id' => $category['id'],
					'word_id' => $word['id']
				));

				// Push wordCard

				$this->push_wordcard($word);
				
				$state = false; 
			}

		}

	}

	public function push_wordcard($word) {
		// foreach ($this->users as $user) {
		// 	$bucket = array(
		// 		'user_id' => $user['id'],
		// 		'word' => $word['word'],
		// 		'answer' => $word['answer']
		// 	);
			PushNotification::app('IOS')
				->to('5e6aeba4ef288e06c426e9fa177bdf713882bd20')
				->send('Hello World, i`m a push message');
			// $this->info('Word "'.$word['word'].'"'.'('.$word['id'].')'.' has pushed to user '.$user['username'] );
		// }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}

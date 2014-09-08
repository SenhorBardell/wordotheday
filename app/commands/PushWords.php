<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Sly\NotificationPusher\PushManager,
    Sly\NotificationPusher\Adapter\Apns as ApnsAdapter;

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
		$rawdevices = [];
		$users = User::all();
		foreach ($users as $user) {
			array_push($rawdevices, PushNotification::Device($user->device, ['badge' => 1]));
		}
		$devices = PushNotification::DeviceCollection($rawdevices);
		$word = WordCard::find(Setting::first()->word_id);
		PushNotification::app('IOS')
			->to($devices)
			->send($word->word." - новое слово для изучения", [
				"custom" => [
					"cdata" => [
						"word_id" => $word->id,
						"cat_id" => $word->cat_id
					]
				]
			]);
		// $this->check();
		// $this->base_cat();
	}
	
	public function check() {
		$pushManager = PushNotification::PushManager('Development');

		$apnsAdapter = PushNotification::ApnsAdapter([
			'certificate' => $_ENV['APNS_CERTIFICATE'],
			'passPhrase'  => $_ENV['APNS_PASSPHRASE'],
		]);

		$feedback = $pushManager->getFeedback($apnsAdapter); // Returns an array of Token + DateTime couples
		var_dump($feedback);
	}

	public function base_cat() {
		$users = User::all();
		$this->users = $users;
		$category = Category::find(211);
		$words = $category->wordcards;
		$this->words = $words;
		$count = $words->count();
		$this->count = $count;
		$sent_count = SentWordCard::all()->count();
		$state = true;
		$settings = Setting::first();
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

		$settings['word_id'] = $this->word['id'];
		$settings->save();

	}

	public function push_wordcard($word) {
		foreach ($this->users as $user) {
		// 	$bucket = array(
		// 		'user_id' => $user['id'],
		// 		'word' => $word['word'],
		// 		'answer' => $word['answer']
		// 	);
			try {
				PushNotification::app('IOS')
					->to($user->username)
					->send('Hello World, i`m a push message');
			} catch (Exception $e){
				$this->error($e->getMessage());
			} finally {
				$this->info('Word "'.$word['word'].'"'.'('.$word['id'].')'.' has pushed to user '.$user['username'] );
				// $user->word_id = $word['id'];
				// $user->save();
			}

			
		}
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

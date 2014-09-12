<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Sly\NotificationPusher\PushManager,
    Sly\NotificationPusher\Adapter\Apns as ApnsAdapter;

class PushWord extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'push:word';

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
        $dayWord = $this->getDayWord();

        $this->pushWord($dayWord);

        $this->updateSettings($dayWord->id);

	}

    /**
     * Update Word of the day in settings
     *
     * @param int $id
     * @return void
     */
    public function updateSettings($id) {
        $settings = Settings::first();
        $settings->word_id = $id;
        $settings->save();
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

    public function getDayWord() {
        $words = WordCard::all();
        $wordsCount = WordCard::count();

        while (true) {

            $randomWord = $words[rand(0, $words->count() - 1)];

            if (!$randomWord)
                continue;

            if (SentWordCard::where('word_id', $randomWord->id)->where('category_id', 0)) {

                if ($wordsCount == SentWordCard::where('category_id', 0)->count())
                    SentWordCard::where('category_id', 0)->delete();

                if ($dayWord = SentWordCard::create(['category_id' => 0, 'word_id' => $randomWord->id]))
                    break;

            }

        }

        return $dayWord->toArray();
    }

    /**
     * @param $id Category id
     * @param $words array words from this very category
     * @return array pushWords
     */
    public function getSentWords($id, $words) {
        $pushWords = [];
        while (count($pushWords) < 3) {

            $wordsCount = $words->count();

            if ($wordsCount < 3)
                return [];

            $randomWord = $words[rand(0, $wordsCount - 1)];

            if (!$randomWord)
                continue;

            if (SentWordCard::where('word_id', $randomWord->id)->where('category_id', $id)) {

                if ($wordsCount == SentWordCard::where('category_id', $id)->count())
                    SentWordCard::where('category_id', $id)->delete();

                if ($newPushWord = SentWordCard::create(['category_id' => $id, 'word_id' => $randomWord->id]))
                    array_push($pushWords, $newPushWord->toArray());
            }
        }

        return $pushWords;
    }

	public function pushWord($word) {
        $users = User::all();

        foreach ($users as $user) {
            $rawdevices[] = PushNotification::Device($user->device, ['badge' => 1]);
        }
        $devices = PushNotification::DeviceCollection($rawdevices);

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

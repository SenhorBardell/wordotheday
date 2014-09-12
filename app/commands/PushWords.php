<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Sly\NotificationPusher\PushManager,
    Sly\NotificationPusher\Adapter\Apns as ApnsAdapter;

class PushWords extends Command {

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
        $categories = Category::all();
        $cardsArr = [];

        foreach ($categories as $category) {
            $cardsArr = array_merge($cardsArr, $this->getSentWords($category->id, $category->wordcards));
        }

        $this->pushWords($cardsArr);
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

                if ($newPushWord = SentWordCard::create(['category_id' => $id, 'word_id' => $randomWord->id])) {

                }
                    array_push($pushWords, [
                        'word_id' => $newPushWord->id,
                        'category_id' => $newPushWord->category_id,
                    ]);
            }
        }

        return $pushWords;
    }

	public function pushWords($words) {

        function implode_r($glue, array $arr) {
            $ret = '';
            foreach ($arr as $piece) {
                if (is_array($piece))
                    $ret .= $glue.implode_r($glue, $piece);
                else
                    $ret .= $glue.$piece;
            }

            return $ret;
        }

        $users = User::whereNotNull('device')->get();

        foreach ($users as $user) {
            $rawdevices[] = PushNotification::Device($user->device, ['badge' => 1]);
        }

        $devices = PushNotification::DeviceCollection($rawdevices);
// @TODO Proper push message body
        PushNotification::app('IOS')
            ->to($devices)
            ->send("Пора знакомится с новыми словами", [
                "custom" => [
                    "cdata" => $words
                ]
            ]);
        $this->info('Pushed wordcards');
        $this->info(implode_r(', ', $words));
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

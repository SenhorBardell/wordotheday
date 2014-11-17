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

        $dayWord = WordCard::find(Setting::first()->word_id);
        array_push($cardsArr, ['word_id' => $dayWord->id, 'category_id' => $dayWord->category_id]);

        foreach ($categories as $category) {
            $this->info('Looking at '.$category->id);
            $card = $this->getSentWords($category->id, $category->wordcards);

            if (empty($card))
                continue;

            array_push($cardsArr, $card[0]);
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
        $state = 1;
        while ($state <= 1) {

            $wordsCount = $words->count();

            if ($wordsCount == 0)
                return [];

            $randomWord = $words[rand(0, $wordsCount - 1)];

            if (!$randomWord)
                continue;

            $this->info("Picked random word: ".$randomWord->word.' ('.$randomWord->id.')');
            if (SentWordCard::where('word_id', $randomWord->id)->where('category_id', $id)) {

                if ($wordsCount == SentWordCard::where('category_id', $id)->count()) {
                    SentWordCard::where('category_id', $id)->delete();
                    $this->info('Count reached');
                }

                if ($newPushWord = SentWordCard::create(['category_id' => $id, 'word_id' => $randomWord->id])) {

                    $this->comment('New sent word created: '.$randomWord->id.' ('.$id.')');
                    if ($state == 1) {
                        array_push($pushWords, [
                            'word_id' => $newPushWord->word_id,
                            'category_id' => $newPushWord->category_id,
//                            'type' => 1
                        ]);
                        $this->info('Sent word pushed to queue: '.$randomWord->id.' ('.$id.')');
                    }

                }

                $state++;
            }
        }

        return $pushWords;
    }

	public function pushWords($words) {

        function implode_r($glue, array $arr) {
            $ret = '';
            foreach ($arr as $piece) {
                if (is_array($piece))
                    $ret .= '; '.implode_r($glue, $piece);
                else
                    $ret .= $glue.$piece;
            }

            return $ret;
        }

        $users = User::where('device', '<>', '')->with('subscriptions')->get();

        foreach ($users as $user) {
            if ($user->subscriptions()->count() > 0)
                $rawdevices[] = PushNotification::Device($user->device, ['badge' => 1]);
        }

        $devices = PushNotification::DeviceCollection($rawdevices);
        PushNotification::app('IOS')
            ->to($devices)
            ->send("Пора знакомится с новыми словами", [
                "custom" => [
                    "cdata" => $words,
                    "type" => 1
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

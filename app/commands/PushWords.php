<?php

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
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
	public function fire3()
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

		$apnsAdapter = new ApnsAdapter([
			'certificate' => $_ENV['APNS_CERTIFICATE'],
			'passPhrase'  => $_ENV['APNS_PASSPHRASE'],
		]);

		$feedback = $pushManager->getFeedback($apnsAdapter); // Returns an array of Token + DateTime couples

		foreach ($feedback as $row) {
			$user = User::where('device', $row['devtoken'])->first();
			$user->device = '';
			$user->save();
		}

		var_dump($feedback);
	}

	public function fire() {
		$pushWords = new Collection;
		$dayWord = WordCard::find(Setting::first()->word_id);
		$pushWords->push(['word_id' => $dayWord->id, 'category_id' => $dayWord->category_id]);
		Category::all()->each(function ($category) use ($pushWords) {
			$this->info("looking at category {$category->id}");
			$word = $this->getSentWord($category);

			if ($word)
				$pushWords->push(['word_id' => $word->id, 'category_id' => $category->id]);
			else
				$this->error('Empty category');
		});

		$this->check();
		$this->pushWords2($pushWords);
	}

    public function getSentWord($category) {
		$words = $category->wordcards;
		$this->info('Starting cycle');

		while ($words->count() >= 1) {
			$this->info($words->count());

			if ($words->count() < 2) {
				$this->error('Too small to pick random');
				$word = $words->pop();
			} else {
				$randKey = rand(0, $words->count() -1);
				$this->info("Words size: {$words->count()}");
				$this->info("Key: {$randKey}");
				$word = $words->pull($randKey);
			}

			if (!$word)
				continue;

			$this->info("Got {$word->id}:{$word->word}");

			if (SentWordCard::byCat($category)->count() >= $words->count()) {
				SentWordCard::byCat($category)->delete();
				$this->error('Count reached');
			}

			if (!$word->isSent()) {
				$this->comment('Passed');

				return SentWordCard::create(['category_id' => $category->id, 'word_id' => $word->id]);
			}
			$this->comment('Skiped');
			$words->values();
		}
    }

	/**
	 * Push via collection
	 *
	 * @param $words
	 */
	public function pushWords2($words) {
		$this->info('Done preparing. Pushing');

//		$users = User::has('subscriptions', '>', 0)->where('device', '<>', '')->groupBy('device')->get();
//		$devices = $users->map(function ($user) {
//			return PushNotification::Device($user->device, ['badge', 1]);
//		})->toArray();
//		dd($users->map(function ($user) {
//			if ($user->device == '4f2da0157aee52ea22718fd988df0dda80830b902c774f38f16dbf5faf2069b9')
//				$this->comment('Found, '.$user->subscriptions->count());
//			return $user->device;
//		}));
		$devices = PushNotification::DeviceCollection(User::has('subscriptions', '>', 0)->where('device', '<>', '')
			->groupBy('device')
			->get()
			->map(function ($user) {
				$this->info($user->device);
				return PushNotification::Device($user->device, ['badge', 1]);
			})
			->toArray());

		PushNotification::app('IOS')->to($devices)
			->send("Пора знакомится с новыми словами", [
				"custom" => [
					"cdata" => $words->toArray(),
					"type" => 1
				]
			]);
		$this->info('Done');
	}

	/**
	 * Push by user
	 *
	 * @param $words
	 */
	public function pushWords3($words) {
		User::where('device', '<>', '')->with('subscriptions')->groupBy('device')->get()
			->filter(function ($user) {
				return $user->subscriptions->count() > 0;
			})->each(function ($user) use($words){
				$this->send($user->device, $words);
			});
	}

	public function send($device, $words) {
		try {
			PushNotification::app('IOS')
				->to($device)
				->send("Пора знакомится с новыми словами", [
					"custom" => [
						"cdata" => $words,
						"type" => 1
					]
				]);
			$this->comment('Sending to '.$device);
		} catch (Exception $e) {
			$this->error($e);
		}
	}

    /**
     * We know what words to push by looking in sentwords
     * @param $id Category id
     * @param $words array words from this very category
     * @return array pushWords
     */
    public function getSentWordsOrigin($id, $words) {
        $pushWords = [];
        $state = 1;
//        $c = new Illuminate\Database\Eloquent\Collection;
//        dd($c);
        while ($state <= 1) {

            $wordsCount = $words->count();

            if ($wordsCount == 0)
                return [];

            $randomWord = $words[rand(0, $wordsCount - 1)];

            if (!$randomWord)
                continue;

            $this->info("Picked random word: ".$randomWord->word.' ('.$randomWord->id.')');
            if (SentWordCard::where('word_id', $randomWord->id)->where('category_id', $id)->first()) { //GET()?

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

        $rawdevices = $users->filter(function ($user) {
            return $user->subscriptions->count() > 0;
        })->map(function ($user) {
            return PushNotification::Device($user->device, ['badge' => 1]);
        });

        dd($rawdevices->toArray());

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
